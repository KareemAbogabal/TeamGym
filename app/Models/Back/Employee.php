<?php

namespace App\Models\Back;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Notification;
use App\Models\Back\Record;
use App\Models\Back\Payment;
use App\Models\Back\History;
use App\Models\Back\Imports;
use App\Models\Back\SettingEmployee;
use App\Models\Back\SettingCompany;
use App\Models\Back\Activity;
use App\Models\Coach\CoachProfile;
use App\Models\Coach\CoachAssignment;
use App\Models\Coach\CoachNote;

class Employee extends Authenticatable {
  use Notifiable;
  protected $fillable = ['fname', 'lname', 'job_role', 'phone', 'img', 'email', 'password', 'documentation', 'code'];
  protected $hidden = ['password'];
  public function activities() {
    return $this->hasMany(Activity::class, 'code_employee', 'code');
  }
  public function records() {
    return $this->hasMany(Record::class, 'code_employee', 'code');
  }
  public function payment() {
    return $this->hasMany(Payment::class, 'code_employee', 'code');
  }
  public function paymentRegistries() {
    return $this->hasMany(PaymentRegistry::class, 'code_employee', 'code');
  }
  public function history() {
    return $this->hasMany(History::class, 'code_employee', 'code');
  }
  public function imports() {
    return $this->hasMany(Imports::class, 'code_supplements', 'code');
  }
  public function setting() {
    return $this->hasOne(SettingEmployee::class, 'code_employee', 'code');
  }
  public function settingAdmin() {
    return $this->hasOne(SettingCompany::class, 'code_employee', 'code');
  }
  public function notifications() {
    return $this->hasMany(Notification::class, 'code_employee', 'code');
  }
  public function coachProfile() {
    return $this->hasOne(CoachProfile::class, 'code_employee', 'code');
  }
  public function coachAssignments() {
    return $this->hasMany(CoachAssignment::class, 'code_coach', 'code');
  }
  public function activeCoachAssignments() {
    return $this->hasMany(CoachAssignment::class, 'code_coach', 'code')->where('status', 'active');
  }
  public function coachNotes() {
    return $this->hasMany(CoachNote::class, 'code_coach', 'code');
  }
  public function isCoach(): bool {
    $roles = array_map('strtolower', (array) config('roles.coach', []));
    return in_array(strtolower(trim((string) $this->job_role ?? '')), $roles);
  }
}
