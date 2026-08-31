<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Enums\CoachRequestStatus;
use App\Enums\CoachRequestDirection;

/**
 * Represents both a coach request and its lifecycle into an active assignment.
 *
 * A row starts as a "pending" request (direction = client_to_coach or coach_to_client).
 * On admin approval it becomes "active" (started_at set). The previous active
 * assignment is ended first, preserving history.
 */
class CoachAssignment extends Model {
  protected $table = 'coach_assignments';
  protected $fillable = [
    'code_client', 'code_coach', 'requested_by_type', 'requested_by_id',
    'direction', 'status', 'reason', 'admin_note', 'rejection_reason',
    'requested_at', 'approved_at', 'rejected_at', 'cancelled_at',
    'started_at', 'ended_at', 'approved_by', 'rejected_by',
  ];
  protected $casts = [
    'requested_at' => 'datetime',
    'approved_at' => 'datetime',
    'rejected_at' => 'datetime',
    'cancelled_at' => 'datetime',
    'started_at' => 'datetime',
    'ended_at' => 'datetime',
  ];

  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function coach() {
    return $this->belongsTo(Employee::class, 'code_coach', 'code');
  }

  public function scopePending($q) {
    return $q->where('status', CoachRequestStatus::Pending->value);
  }
  public function scopeActive($q) {
    return $q->where('status', CoachRequestStatus::Active->value);
  }
}
