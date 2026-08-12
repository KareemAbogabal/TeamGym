<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Back\Supplement;
use App\Models\Back\System;

class Notification extends Model {
  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function employee() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }
  public function supplement() {
    return $this->belongsTo(Supplement::class, 'code_supplements', 'code');
  }
  public function system() {
    return $this->belongsTo(System::class, 'code_systems', 'code');
  }
}
