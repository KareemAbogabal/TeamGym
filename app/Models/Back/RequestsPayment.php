<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Back\Payment;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\Snacks;

class RequestsPayment extends Model {
  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function employee() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }
  public function payment() {
    return $this->hasMany(Payment::class, 'code_request_payment', 'code');
  }
  public function supplement() {
    return $this->belongsTo(Supplement::class, 'code_supplements', 'code');
  }
  public function system() {
    return $this->belongsTo(System::class, 'code_systems', 'code');
  }
  public function addRequest($code_client, $order_name, $code_attachment, $code_snacks, $amount, $payday, $code_employee) {
    $rand = rand(100000, time());
    $getClient = Client::where("code", $code_client)->first();
    if (!is_string($code_employee)) {
      $getEmployee = Employee::where("code", $code_employee)->first();
    };
    $getSupplement = Supplement::where("code", $code_attachment)->first();
    $getSystem = System::where("code", $code_attachment)->first();
    $getSnacks = Snacks::where("code", $code_snacks)->first();
    $this->code = $rand;
    if ($getClient) {
      $this->fname = $getClient->fname;
      $this->lname = $getClient->lname;
      $this->code_client = $getClient->code;
    };
    $this->order_name = $order_name;
    if ($getSupplement) {
      $this->code_supplements = $getSupplement->code;
    } else if ($getSystem) {
      $this->code_systems = $getSystem->code;
    } else if ($getSnacks) {
      $this->code_snacks = $getSnacks->code;
    };
    $this->amount = $amount;
    $this->payday = $payday;
    $this->state = "request";
    if (!is_string($code_employee)) {
      $this->code_employee = $getEmployee->code;
    } else {
      $this->code_employee = $code_employee;
    };
    $this->save();
  }
}
