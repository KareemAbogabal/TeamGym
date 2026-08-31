<?php

namespace App\Models\Coach;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;

class CoachNote extends Model {
  protected $fillable = ['code_client', 'code_coach', 'note', 'visibility'];

  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function coach() {
    return $this->belongsTo(Employee::class, 'code_coach', 'code');
  }
}
