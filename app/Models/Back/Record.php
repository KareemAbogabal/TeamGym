<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Traits\Payments;

class Record extends Model {
  use Payments;
  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function employee() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }
  public function record($code_client, $name_client, $state = "entrance", $amount, $attachment = null, $code_employee = "system", $name_employee = "system", $phone_employee = "system", $job_role_employee = "system", $state_payment = true) {
    $this->code_client = $code_client;
    $this->name_client = $name_client;
    $this->state = $state;
    $this->amount = $amount;
    $this->attachment = $attachment;
    if (isset($attachment) && $state_payment == true) {
      $this->registration($code_client, $attachment, $amount);
    };
    $this->code_employee = $code_employee;
    $this->name_employee = $name_employee;
    $this->phone_employee = $phone_employee;
    $this->job_role_employee = $job_role_employee;
    $this->save();
  }
}
