<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\Supplement;
use App\Models\Back\Snacks;
use App\Models\Back\Employee;

class Imports extends Model {
  public function employee() {
    return $this->belongsTo(Employee::class, 'code_employee', 'code');
  }
  public function supplement() {
    return $this->belongsTo(Supplement::class, 'code_supplements', 'code');
  }
  public function snacks() {
    return $this->belongsTo(Snacks::class, 'code_snaks', 'code');
  }
  public static function updateQuantit($code_supplements = null, $code_snaks = null, $quantity = null) {
    $supplement = self::where("code_supplements", $code_supplements)->first();
    $snak = self::where("code_snaks", $code_snaks)->first();
    if ($supplement) {
      $searchProduct = $supplement;
    } else {
      $searchProduct = $snak;
    };
    if ($searchProduct->quantity !== 0 || $searchProduct->quantity <= 0) {
      $searchProduct->quantity -= $quantity ? $quantity : 1;
      $searchProduct->save();
    };
  }
}
