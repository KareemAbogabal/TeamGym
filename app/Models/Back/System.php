<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;
use App\Models\Back\FeatureSystem;
use App\Models\Back\Payment;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Lineage;

class System extends Model {
  public function features() {
    return $this->hasMany(FeatureSystem::class, 'code_system', 'code');
  }
  public function payment() {
    return $this->hasMany(Payment::class, 'code_systems', 'code');
  }
  public function requestsPayment() {
    return $this->hasMany(RequestsPayment::class, 'code_systems', 'code');
  }
  public function lineage() {
    return $this->hasMany(Lineage::class, 'code_systems', 'code');
  }
  public function notifications() {
    return $this->hasMany(Notification::class, 'code_systems', 'code');
  }
}
