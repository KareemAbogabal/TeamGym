<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;

class InbodyMeasurement extends Model {
  protected $fillable = [
    'code_client', 'measured_at', 'weight', 'bmi', 'pbf', 'smm', 'kcal', 'water',
    'fat_mass', 'protein', 'left_arm_lean', 'right_arm_lean', 'left_leg_lean',
    'right_leg_lean', 'left_arm_fat', 'right_arm_fat', 'left_leg_fat',
    'right_leg_fat', 'source', 'image_path', 'created_by',
  ];
  protected $casts = [
    'measured_at' => 'datetime',
    'weight' => 'float',
    'bmi' => 'float',
    'pbf' => 'float',
    'smm' => 'float',
    'kcal' => 'float',
    'water' => 'float',
    'fat_mass' => 'float',
    'protein' => 'float',
    // lean/fat fields are all float
    'left_arm_lean' => 'float',
    'right_arm_lean' => 'float',
    'left_leg_lean' => 'float',
    'right_leg_lean' => 'float',
    'left_arm_fat' => 'float',
    'right_arm_fat' => 'float',
    'left_leg_fat' => 'float',
    'right_leg_fat' => 'float',
  ];

  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
}
