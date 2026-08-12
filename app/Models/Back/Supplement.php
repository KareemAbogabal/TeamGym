<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;
use App\Models\Back\Payment;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Lineage;
use App\Models\Back\Imports;

class Supplement extends Model {
  public function payment() {
    return $this->hasMany(Payment::class, 'code_supplements', 'code');
  }
  public function requestsPayment() {
    return $this->hasMany(RequestsPayment::class, 'code_supplements', 'code');
  }
  public function lineage() {
    return $this->hasMany(Lineage::class, 'code_supplements', 'code');
  }
  public function imports() {
    return $this->hasOne(Imports::class, 'code_supplements', 'code');
  }
  public function notifications() {
    return $this->hasMany(Notification::class, 'code_supplements', 'code');
  }
}
