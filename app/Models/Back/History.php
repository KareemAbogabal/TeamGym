<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;

class History extends Model {
  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function employee() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }
  public function recordHistory($name, $code_client = null, $code_employee = null, $state, $amount = null, $attachment = null, $registered_entity = null) {
    $rand = rand(100000, time());
    $this->code = $rand;
    $this->name = $name;
    $this->code_client = $code_client;
    $this->code_employee = $code_employee;
    $this->state = $state;
    $this->amount = $amount;
    $this->attachment = $attachment;
    $this->registered_entity = $registered_entity;
    $this->save();
  }
}
