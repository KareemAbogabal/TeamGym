<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;

class WorkoutPlan extends Model {
  protected $fillable = [
    'code_client', 'code_coach', 'title', 'description', 'version', 'status', 'created_by',
  ];
  protected $casts = [
    'version' => 'integer',
  ];

  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function coach() {
    return $this->belongsTo(Employee::class, 'code_coach', 'code');
  }
  public function days() {
    return $this->hasMany(WorkoutPlanDay::class, 'workout_plan_id', 'id')->orderBy('position');
  }
}
