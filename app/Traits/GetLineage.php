<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use Carbon\Carbon;

trait GetLineage {
  public function get($ModelClass, $name, $state = true) {
    $lineage = null;
    $nameLineages = mb_strtolower($name);
    if ($state) {
      $item = $ModelClass::where("code", Cookie::get('login_client'))->where("name", "$name")->first();
    } else {
      $item = $ModelClass::whereRaw('LOWER(name) = ?', ["$nameLineages"])->first();
    };
    if (!$item) {
      return null;
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
  public function getArray($ModelClass, $name, $state = true, $currentMonthOnly = false):array {
    $lineage = [];
    $nameLineages = mb_strtolower($name);
    if ($state) {
      $item = $ModelClass::where("code", Cookie::get('login_client'))->where("name", "$name")->first();
    } else {
      $item = $ModelClass::whereRaw('LOWER(name) = ?', ["$nameLineages"])->first();
    };
    if (!$item) {
      return array_fill(0, 12, null);
    };
    $months = [
      'january','february','march','april','may','june',
      'july','august','september','october','november','december'
    ];
    $date = strtolower(Carbon::now('Africa/Cairo')->format('F'));
    foreach ($months as $m) {
      if ($currentMonthOnly) {
        $lineage[] = ($m === $date) ? ($item->{$m} ?? null) : null;
      } else {
        $lineage[] = $item->{$m} ?? null;
      };
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
