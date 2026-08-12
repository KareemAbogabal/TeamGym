<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use Carbon\Carbon;

trait GetLineage {
  public function get($ModelClass, $name, $state = true) {
    $lineage;
    $nameLineages = mb_strtolower($name);
    if ($state) {
      $item = $ModelClass::where("code", Cookie::get('login_client'))->where("name", "$name")->first();
    } else {
      $item = $ModelClass::whereRaw('LOWER(name) = ?', "$nameLineages")->first();
    };
    $date = strtolower(Carbon::now('Africa/Cairo')->format('F'));
    $months = [
      'january','february','march','april','may','june',
      'july','august','september','october','november','december'
    ];
    foreach ($months as $m) {
      if ($m == $date) {
        $lineage = $item->{$m} ?? null;
        break;
      };
    };
    return $lineage;
  }
  public function getArray($ModelClass, $name, $state = true):array {
    $lineage = [];
    $nameLineages = mb_strtolower($name);
    if ($state) {
      $item = $ModelClass::where("code", Cookie::get('login_client'))->where("name", "$name")->first();
    } else {
      $item = $ModelClass::whereRaw('LOWER(name) = ?', "$nameLineages")->first();
    };
    $months = [
      'january','february','march','april','may','june',
      'july','august','september','october','november','december'
    ];
    foreach ($months as $m) {
      $lineage[] = $item->{$m} ?? null;
    };
    return $lineage;
  }
  public function getlineages():array {
    $basis = [];
    $itemsBasis = [
      "weight", "BMI", "PBF", "SMM", "KCAL", "water", "fat_mass", "protein"
    ];
    foreach ($itemsBasis as $b) {
      $basis[$b] = $this->get(LineageInBody::class, $b);
    };
    return $basis;
  }
}
