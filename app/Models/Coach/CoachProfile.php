<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\Employee;

class CoachProfile extends Model {
  protected $fillable = [
    'code_employee', 'specialization', 'max_active_clients', 'is_active', 'availability',
  ];
  protected $casts = [
    'is_active' => 'boolean',
    'max_active_clients' => 'integer',
  ];

  public function coach() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }

  public function activeClientCount(): int {
    return CoachAssignment::where('code_coach', $this->code_employee)
      ->where('status', 'active')
      ->count();
  }

  public function hasCapacity(): bool {
    return $this->activeClientCount() < $this->max_active_clients;
  }
}
