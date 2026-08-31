<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Coach\CoachAssignment;
use App\Models\Coach\CoachProfile;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Enums\CoachRequestStatus;
use App\Enums\CoachRequestDirection;

/**
 * Orchestrates the coach request / assignment lifecycle.
 *
 * Invariants enforced here (in the application layer, cross-DB safe):
 *  - A client can have at most ONE pending request per direction at a time.
 *  - A client can have at most ONE active assignment at a time.
 *  - A coach cannot exceed their active client capacity.
 *  - Approving/ending an assignment is atomic (wrapped in a transaction).
 */
class CoachService {
  /**
   * A client requests to be coached by a given coach.
   */
  public function clientRequestsCoach(Client $client, string $codeCoach, ?string $reason = null): CoachAssignment {
    $coach = $this->findCoach($codeCoach);

    return DB::transaction(function () use ($client, $coach, $reason) {
      $this->assertNoPending($client->code, CoachRequestDirection::ClientToCoach);

      return CoachAssignment::create([
        'code_client' => $client->code,
        'code_coach' => $coach->code,
        'requested_by_type' => 'client',
        'requested_by_id' => $client->getKey(),
        'direction' => CoachRequestDirection::ClientToCoach->value,
        'status' => CoachRequestStatus::Pending->value,
        'reason' => $reason,
        'requested_at' => now(),
      ]);
    });
  }

  /**
   * A coach requests to take on a specific client.
   */
  public function coachRequestsClient(Employee $coach, string $codeClient, ?string $reason = null): CoachAssignment {
    $client = $this->findClient($codeClient);

    return DB::transaction(function () use ($coach, $client, $reason) {
      $this->assertNoPending($client->code, CoachRequestDirection::CoachToClient);
      $this->assertCapacity($coach->code);

      return CoachAssignment::create([
        'code_client' => $client->code,
        'code_coach' => $coach->code,
        'requested_by_type' => 'coach',
        'requested_by_id' => $coach->getKey(),
        'direction' => CoachRequestDirection::CoachToClient->value,
        'status' => CoachRequestStatus::Pending->value,
        'reason' => $reason,
        'requested_at' => now(),
      ]);
    });
  }

  /**
   * Admin approves a pending request, ending any previous active assignment.
   */
  public function approve(CoachAssignment $assignment, string $approvedBy): CoachAssignment {
    return DB::transaction(function () use ($assignment, $approvedBy) {
      $this->assertPending($assignment);
      $this->assertCapacity($assignment->code_coach);

      $this->endActiveAssignment($assignment->code_client);

      $assignment->status = CoachRequestStatus::Active->value;
      $assignment->approved_at = now();
      $assignment->approved_by = $approvedBy;
      $assignment->started_at = now();
      $assignment->save();

      return $assignment;
    });
  }

  /**
   * Admin (or requester role) rejects a pending request.
   */
  public function reject(CoachAssignment $assignment, string $rejectedBy, ?string $reason = null): CoachAssignment {
    $this->assertPending($assignment);

    $assignment->status = CoachRequestStatus::Rejected->value;
    $assignment->rejected_at = now();
    $assignment->rejected_by = $rejectedBy;
    $assignment->rejection_reason = $reason;
    $assignment->save();

    return $assignment;
  }

  /**
   * The requester cancels their own pending request.
   */
  public function cancel(CoachAssignment $assignment, ?string $issuedBy = null): CoachAssignment {
    $this->assertPending($assignment);

    $assignment->status = CoachRequestStatus::Cancelled->value;
    $assignment->cancelled_at = now();
    $assignment->save();

    return $assignment;
  }

  /**
   * Ends an active assignment (coach stops training the client).
   */
  public function end(CoachAssignment $assignment): CoachAssignment {
    return DB::transaction(function () use ($assignment) {
      $this->assertActive($assignment);

      $assignment->status = CoachRequestStatus::Ended->value;
      $assignment->ended_at = now();
      $assignment->save();

      return $assignment;
    });
  }

  public function pendingForClient(string $codeClient): ?CoachAssignment {
    return CoachAssignment::where('code_client', $codeClient)
      ->where('status', CoachRequestStatus::Pending->value)
      ->latest('requested_at')
      ->first();
  }

  public function activeForClient(string $codeClient): ?CoachAssignment {
    return CoachAssignment::where('code_client', $codeClient)
      ->where('status', CoachRequestStatus::Active->value)
      ->latest('started_at')
      ->first();
  }

  protected function findCoach(string $codeCoach): Employee {
    $coach = Employee::where('code', $codeCoach)->first();
    if (!$coach) {
      throw new \InvalidArgumentException('Coach not found.');
    }
    return $coach;
  }

  protected function findClient(string $codeClient): Client {
    $client = Client::where('code', $codeClient)->first();
    if (!$client) {
      throw new \InvalidArgumentException('Client not found.');
    }
    return $client;
  }

  protected function assertNoPending(string $codeClient, CoachRequestDirection $direction): void {
    $exists = CoachAssignment::where('code_client', $codeClient)
      ->where('direction', $direction->value)
      ->where('status', CoachRequestStatus::Pending->value)
      ->exists();
    if ($exists) {
      throw new \RuntimeException('A pending request already exists for this client.');
    }
  }

  protected function assertNoActive(string $codeClient): void {
    $exists = CoachAssignment::where('code_client', $codeClient)
      ->where('status', CoachRequestStatus::Active->value)
      ->exists();
    if ($exists) {
      throw new \RuntimeException('This client already has an active coach.');
    }
  }

  protected function assertCapacity(string $codeCoach): void {
    $profile = CoachProfile::where('code_employee', $codeCoach)->first();
    if (!$profile || !$profile->is_active) {
      throw new \RuntimeException('This coach is not available for new clients.');
    }
    if (!$profile->hasCapacity()) {
      throw new \RuntimeException('This coach has reached their maximum active client capacity.');
    }
  }

  protected function endActiveAssignment(string $codeClient): void {
    CoachAssignment::where('code_client', $codeClient)
      ->where('status', CoachRequestStatus::Active->value)
      ->update([
        'status' => CoachRequestStatus::Ended->value,
        'ended_at' => now(),
      ]);
  }

  protected function assertPending(CoachAssignment $assignment): void {
    if ($assignment->status !== CoachRequestStatus::Pending->value) {
      throw new \RuntimeException('This request is not pending.');
    }
  }

  protected function assertActive(CoachAssignment $assignment): void {
    if ($assignment->status !== CoachRequestStatus::Active->value) {
      throw new \RuntimeException('This assignment is not active.');
    }
  }
}
