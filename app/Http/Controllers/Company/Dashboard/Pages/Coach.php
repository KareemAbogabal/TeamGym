<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Back\Employee;
use App\Models\Front\Client;
use App\Models\Coach\CoachProfile;
use App\Models\Coach\CoachAssignment;
use App\Services\CoachService;
use App\Enums\CoachRequestStatus;
use App\Enums\CoachRequestDirection;
use App\Http\Requesters\Company\Dashboard\Coach\ManageCoachRequest;
use App\Http\Requesters\Company\Dashboard\Coach\CoachRequestsClientRequest;

class Coach extends Controller {
  public function __construct(private CoachService $coachService) {
  }

  /**
   * Company back office: list + manage all coach requests and assignments.
   */
  public function index(Request $request) {
    $employee = $this->employee();

    $coachRequests = CoachAssignment::where('status', CoachRequestStatus::Pending->value)
      ->where('direction', CoachRequestDirection::ClientToCoach->value)
      ->with(['client', 'coach'])
      ->latest('requested_at')
      ->get();

    $clientRequests = CoachAssignment::where('status', CoachRequestStatus::Pending->value)
      ->where('direction', CoachRequestDirection::CoachToClient->value)
      ->with(['client', 'coach'])
      ->latest('requested_at')
      ->get();

    $active = CoachAssignment::where('status', CoachRequestStatus::Active->value)
      ->with(['client', 'coach'])
      ->latest('started_at')
      ->get();

    $history = CoachAssignment::whereIn('status', [
      CoachRequestStatus::Ended->value,
      CoachRequestStatus::Cancelled->value,
      CoachRequestStatus::Rejected->value,
    ])
      ->with(['client', 'coach'])
      ->latest('requested_at')
      ->limit(100)
      ->get();

        $coachEmployees = Employee::whereIn('job_role', ['coach', 'trainer'])->get();
    $codes = $coachEmployees->pluck('code');
    $profiles = CoachProfile::whereIn('code_employee', $codes)->get()->keyBy('code_employee');
    $activeCounts = CoachAssignment::whereIn('code_coach', $codes)
      ->where('status', CoachRequestStatus::Active->value)
      ->selectRaw('code_coach, COUNT(*) as total')
      ->groupBy('code_coach')
      ->pluck('total', 'code_coach');

    $coaches = $coachEmployees->map(fn ($coach) => [
      'employee' => $coach,
      'profile' => $profiles->get($coach->code),
      'activeClients' => (int) ($activeCounts[$coach->code] ?? 0),
    ]);

    $clients = Client::all();

    $clientCoaches = CoachAssignment::where('status', CoachRequestStatus::Active->value)
      ->with('coach')
      ->get()
      ->mapWithKeys(function ($a) {
        return [$a->code_client => $a->coach ? ($a->coach->fname . ' ' . $a->coach->lname) : __('messages.coach')];
      });

    return view('Company.Dashboard.Pages.coach', compact('employee', 'coachRequests', 'clientRequests', 'active', 'history', 'coaches', 'clients', 'clientCoaches'));
  }

  /**
   * A coach requests a specific client (direction coach_to_client).
   */
  public function requestClient(CoachRequestsClientRequest $request) {
    $employee = $this->employee();
    if (!$employee || !Gate::forUser($employee)->allows('coach')) {
      notifyError(__('messages.unauthorized'));
      return back();
    }

    try {
      $coach = Employee::where('code', $request->input('code_coach'))->first();
      if (!$coach) {
        throw new \InvalidArgumentException(__('messages.unauthorized'));
      }
      $this->coachService->coachRequestsClient($coach, $request->input('code_client'), $request->input('reason'));
      notifySuccess(__('messages.coach-requested'));
    } catch (\Throwable $e) {
      notifyError($e->getMessage());
    }
    return back();
  }

  /**
   * Admin (or the coach themselves) approve/reject/cancel/end an assignment.
   */
  public function manage(ManageCoachRequest $request) {
    $employee = $this->employee();
    $assignment = CoachAssignment::find($request->input('assignment_id'));
    if (!$employee || !$assignment) {
      notifyError(__('messages.unauthorized'));
      return back();
    }

    $action = $request->input('action');
    $allowed = match ($action) {
      'approve', 'reject' => Gate::forUser($employee)->allows('manage', $assignment),
      'cancel' => Gate::forUser($employee)->allows('cancel', $assignment),
      'end' => Gate::forUser($employee)->allows('end', $assignment),
      default => false,
    };

    if (!$allowed) {
      notifyError(__('messages.unauthorized'));
      return back();
    }

    try {
      switch ($action) {
        case 'approve':
          $this->coachService->approve($assignment, $employee->code);
          notifySuccess(__('messages.coach-approved'));
          break;
        case 'reject':
          $this->coachService->reject($assignment, $employee->code, $request->input('note'));
          notifySuccess(__('messages.coach-rejected'));
          break;
        case 'cancel':
          $this->coachService->cancel($assignment, $employee->code);
          notifySuccess(__('messages.coach-request-cancelled'));
          break;
        case 'end':
          $this->coachService->end($assignment);
          notifySuccess(__('messages.coach-ended'));
          break;
      }
    } catch (\Throwable $e) {
      notifyError($e->getMessage());
    }
    return back();
  }

  protected function employee(): ?Employee {
    return Auth::guard('employee')->user();
  }
}
