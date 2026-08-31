<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Notification;
use App\Models\Front\Cardio;
use App\Models\Front\LineageInBody;
use App\Models\Front\SettingClient;
use App\Models\Back\Activity;
use App\Models\Back\Record;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Payment;
use App\Models\Back\History;
use App\Models\Back\CustomerRequests;
use App\Models\Back\Employee;
use App\Models\Coach\CoachAssignment;
use App\Models\Coach\CoachNote;
use App\Models\Coach\ClientGoal;
use App\Models\Coach\ClientQrCode;
use App\Models\Coach\Membership;
use App\Models\Coach\AttendanceSession;
use App\Models\Coach\WorkoutPlan;
use App\Models\Coach\InbodyMeasurement;
use App\Services\ClientQrService;

class Client extends Authenticatable {
  protected $fillable = ['code', 'fname', 'lname', 'email', 'phone', 'state', 'category', 'documentation', 'img', 'year_inbody',];
  protected $hidden = ['password', 'remember_token'];
  protected static function booted(): void {
    static::creating(function ($client) {
      if (empty($client->year_inbody)) {
        $client->year_inbody = (int) now()->format('Y');
      };
    });
    static::created(function ($client) {
      if (!empty($client->code)) {
        $service = app(ClientQrService::class);
        $service->ensureForClient($client);
        $service->ensureAttendanceBarcode($client);
      };
    });
  }
  public function activities() {
    return $this->hasMany(Activity::class, 'code_client', 'code');
  }
  public function records() {
    return $this->hasMany(Record::class, 'code_client', 'code');
  }
  public function requestsPayments() {
    return $this->hasMany(RequestsPayment::class, 'code_client', 'code');
  }
  public function payment() {
    return $this->hasMany(Payment::class, 'code_client', 'code');
  }
  public function history() {
    return $this->hasMany(History::class, 'code_client', 'code');
  }
  public function lineageInBodies() {
    return $this->hasMany(LineageInBody::class, 'code', 'code');
  }
  public function settings() {
    return $this->hasOne(SettingClient::class, 'code_client', 'code');
  }
  public function notifications() {
    return $this->hasMany(Notification::class, 'code_client', 'code');
  }
  public function customerRequests() {
    return $this->hasMany(CustomerRequests::class, 'code_client', 'code');
  }
  public function cardio() {
    return $this->hasMany(Cardio::class, 'code_client', 'code');
  }
  public function activeCoachAssignment() {
    return $this->hasOne(CoachAssignment::class, 'code_client', 'code')->where('status', 'active');
  }
  public function coachAssignments() {
    return $this->hasMany(CoachAssignment::class, 'code_client', 'code');
  }
  public function activeCoach() {
    return $this->hasOneThrough(Employee::class, CoachAssignment::class, 'code_client', 'code', 'code', 'code_coach')
      ->where('coach_assignments.status', 'active');
  }
  public function currentCoach() {
    return $this->activeCoachAssignment();
  }
  public function qrCodes() {
    return $this->hasMany(ClientQrCode::class, 'code_client', 'code');
  }
  public function activeQrCodes() {
    return $this->hasMany(ClientQrCode::class, 'code_client', 'code')->where('status', 'active');
  }
  public function memberships() {
    return $this->hasMany(Membership::class, 'code_client', 'code');
  }
  public function coachNotes() {
    return $this->hasMany(CoachNote::class, 'code_client', 'code');
  }
  public function goals() {
    return $this->hasMany(ClientGoal::class, 'code_client', 'code');
  }
  public function attendanceSessions() {
    return $this->hasMany(AttendanceSession::class, 'code_client', 'code');
  }
  public function activeCoachAssignments() {
    return $this->hasMany(CoachAssignment::class, 'code_client', 'code')->where('status', 'active');
  }
  public function workoutPlans() {
    return $this->hasMany(WorkoutPlan::class, 'code_client', 'code');
  }
  public function inbodyMeasurements() {
    return $this->hasMany(InbodyMeasurement::class, 'code_client', 'code');
  }
}
