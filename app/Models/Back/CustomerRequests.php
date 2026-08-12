<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Front\Client;
use App\Models\Back\Payment;

class CustomerRequests extends Model {
  public function client() {
    return $this->belongsTo(Client::class, 'code_client', 'code');
  }
  public function payment() {
    return $this->belongsTo(Payment::class, 'code_payment', 'code');
  }
}
