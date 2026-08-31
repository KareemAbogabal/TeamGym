<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;

class WorkoutPlanExercise extends Model {
  protected $fillable = [
    'workout_plan_day_id', 'exercise_name', 'sets', 'repetitions', 'weight',
    'rest_seconds', 'duration_minutes', 'coach_note', 'position',
  ];
  protected $casts = [
    'sets' => 'integer',
    'repetitions' => 'integer',
    'weight' => 'float',
    'rest_seconds' => 'integer',
    'duration_minutes' => 'integer',
    'position' => 'integer',
  ];

  public function day() {
    return $this->belongsTo(WorkoutPlanDay::class, 'workout_plan_day_id', 'id');
  }
}
