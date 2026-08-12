<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\PaymentRegistry;
use App\Models\Back\RequestsPayment;
use App\Models\Back\CustomerRequests;

class Payment extends Model {
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
  public function registries() {
    return $this->hasMany(PaymentRegistry::class, 'code_payments', 'code');
  }
  public function requestsPayment() {
    return $this->belongsTo(RequestsPayment::class, 'code_request_payment', 'code');
  }
  public function customerRequests() {
    return $this->hasMany(CustomerRequests::class, 'code_payment', 'code');
  }
}
