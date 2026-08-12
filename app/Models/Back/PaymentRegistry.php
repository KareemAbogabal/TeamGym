<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\Payment;
use App\Models\Back\Employee;

class PaymentRegistry extends Model {
  public function payments() {
    return $this->belongsTo(Payment::class, 'code_payments', 'code');
  }
  public function employee() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }
}
