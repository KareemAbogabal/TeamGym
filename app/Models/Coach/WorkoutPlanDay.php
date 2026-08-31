<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;

class WorkoutPlanDay extends Model {
  protected $fillable = ['workout_plan_id', 'day_name', 'position', 'coach_note'];
  protected $casts = ['position' => 'integer'];

  public function plan() {
    return $this->belongsTo(WorkoutPlan::class, 'workout_plan_id', 'id');
  }
  public function exercises() {
    return $this->hasMany(WorkoutPlanExercise::class, 'workout_plan_day_id', 'id')->orderBy('position');
  }
}
