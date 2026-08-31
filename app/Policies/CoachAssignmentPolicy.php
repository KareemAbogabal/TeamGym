<?php

namespace App\Policies;

use App\Models\Coach\CoachAssignment;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Enums\CoachRequestStatus;
use Illuminate\Support\Facades\Gate;

/**
 * Model-scoped authorization for a single coach assignment.
 *
 * Actor is typed loosely (Client|Employee|null) so the same policy serves both
 * the Website client portal and the Company back office.
 */
class CoachAssignmentPolicy {
  /**
   * A pending request can be cancelled by its requester or by an admin.
   */
  public function cancel(Client|Employee|null $actor, CoachAssignment $assignment): bool {
    if ($assignment->status !== CoachRequestStatus::Pending->value) {
      return false;
    }
    if ($actor instanceof Client) {
      return $assignment->requested_by_type === 'client'
        && (string) $assignment->requested_by_id === (string) $actor->getKey();
    }
    if ($actor instanceof Employee) {
      return $this->isAdmin($actor);
    }
    return false;
  }

  /**
   * Only admins can approve or reject a pending request.
   */
  public function manage(Client|Employee|null $actor, CoachAssignment $assignment): bool {
    return $actor instanceof Employee && $this->isAdmin($actor);
  }

  /**
   * An active assignment can be ended by its coach or by an admin.
   */
  public function end(Client|Employee|null $actor, CoachAssignment $assignment): bool {
    if ($assignment->status !== CoachRequestStatus::Active->value) {
      return false;
    }
    if (!$actor instanceof Employee) {
      return false;
    }
    if ($this->isAdmin($actor)) {
      return true;
    }
    return $assignment->code_coach === $actor->code && Gate::forUser($actor)->allows('coach');
  }

  protected function isAdmin(Employee $actor): bool {
    return Gate::forUser($actor)->allows('admin');
  }
}
