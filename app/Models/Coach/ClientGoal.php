<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;

class ClientGoal extends Model {
  protected $fillable = [
    'code_client', 'code_coach', 'title', 'description', 'target_value',
    'current_value', 'unit', 'start_date', 'target_date', 'status',
  ];
  protected $casts = [
    'target_value' => 'float',
    'current_value' => 'float',
    'start_date' => 'date',
    'target_date' => 'date',
  ];

  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function coach() {
    return $this->belongsTo(Employee::class, 'code_coach', 'code');
  }
}
