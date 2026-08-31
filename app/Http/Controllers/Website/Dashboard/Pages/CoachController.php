<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Back\Employee;
use App\Models\Front\Client;
use App\Models\Coach\CoachProfile;
use App\Models\Coach\CoachAssignment;
use App\Services\CoachService;
use App\Http\Requesters\Website\Dashboard\Coach\CoachRequestRequest;
use App\Http\Requesters\Website\Dashboard\Coach\CancelCoachRequest;

class CoachController extends Controller {
  public function __construct(private CoachService $coachService) {
  }

  /**
   * Client portal: list / browse available coaches and the client's own
   * relationship (pending request or active coach).
   */
  public function index(Request $request) {
    $client = $this->client();
    if (!$client) {
      return redirect()->route('front');
    }

    $coaches = Employee::where('job_role', 'coach')
      ->orWhere('job_role', 'trainer')
      ->get()
      ->map(function ($coach) {
        $profile = CoachProfile::where('code_employee', $coach->code)->first();
        return [
          'employee' => $coach,
          'profile' => $profile,
        ];
      });

    $pending = $this->coachService->pendingForClient($client->code);
    $active = $this->coachService->activeForClient($client->code);
    $history = CoachAssignment::where('code_client', $client->code)
      ->latest('requested_at')
      ->get();

    return view('Website.Dashboard.Pages.coach', compact('client', 'coaches', 'pending', 'active', 'history'));
  }

  /**
   * Client submits a request to be trained by a coach.
   */
  public function requestCoach(CoachRequestRequest $request) {
    $client = $this->client();
    if (!$client || !Gate::forUser($client)->allows('client')) {
      notifyError(__('messages.unauthorized'));
      return back();
    }

    try {
      $this->coachService->clientRequestsCoach($client, $request->input('code_coach'), $request->input('reason'));
      notifySuccess(__('messages.coach-requested'));
    } catch (\Throwable $e) {
      notifyError($e->getMessage());
    }
    return back();
  }

  /**
   * Client cancels their own pending coach request.
   */
  public function cancelCoach(CancelCoachRequest $request) {
    $client = $this->client();
    $assignment = CoachAssignment::find($request->input('assignment_id'));
    if (!$client || !$assignment || !Gate::forUser($client)->allows('cancel', $assignment)) {
      notifyError(__('messages.unauthorized'));
      return back();
    }

    try {
      $this->coachService->cancel($assignment);
      notifySuccess(__('messages.coach-request-cancelled'));
    } catch (\Throwable $e) {
      notifyError($e->getMessage());
    }
    return back();
  }

  protected function client(): ?Client {
    return Auth::guard('client')->user();
  }
}
