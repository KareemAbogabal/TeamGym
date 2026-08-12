<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\Employee;

class SettingCompany extends Model {
  public function employee() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }
}
