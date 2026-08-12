<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\Snacks;
use Carbon\Carbon;

class Lineage extends Model {
  protected $fillable = [
    'code',
    'code_supplements',
    'code_systems',
    'inputs',
    'name',
    'january',
    'february',
    'march',
    'april',
    'may',
    'june',
    'july',
    'august',
    'september',
    'october',
    'november',
    'december',
  ];
  public function supplement() {
    return $this->belongsTo(Supplement::class, 'code_supplements', 'code');
  }
  public function system() {
    return $this->belongsTo(System::class, 'code_systems', 'code');
  }
  public function snacks() {
    return $this->belongsTo(Snacks::class, 'code_snacks', 'code');
  }
  public static function addLineage($code_supplements = null, $code_systems = null, $inputs = null, $IncomeStatement = null, $code_snacks = null, $name, $lineage) {
    $date = strtolower(Carbon::now('Africa/Cairo')->format('F'));
    $months = [
      'january', 'february', 'march', 'april', 'may', 'june',
      'july', 'august', 'september', 'october', 'november', 'december'
    ];
    $rand = random_int(100000, 999999999);
    $row = self::firstOrNew(['name' => $name]);
    $currentIndex = array_search($date, $months);
    if (!$row->exists) {
      $row->code = (string) $rand;
      $row->name = $name;
      $row->code_supplements = $code_supplements;
      $row->code_systems = $code_systems;
      $row->inputs = $inputs;
      $row->IncomeStatement = $IncomeStatement;
      $row->code_snacks = $code_snacks;
      foreach ($months as $i => $m) {
        if ($i < $currentIndex) {
          $row->$m = 0;
        }
        if ($i === $currentIndex) {
          $row->$m = is_numeric($lineage) ? (float) $lineage : 0;
        };
      };
      $row->save();
    } else {
      foreach ($months as $i => $m) {
        if ($i < $currentIndex && (!isset($row->$m) || $row->$m === null)) {
          $row->$m = 0;
        };
      };
      $current = is_numeric($row->{$date}) ? (float) $row->{$date} : 0;
      $row->{$date} = $current + (is_numeric($lineage) ? (float) $lineage : 0);
      if ($code_supplements !== null) $row->code_supplements = $code_supplements;
      if ($code_systems !== null) $row->code_systems = $code_systems;
      if ($inputs !== null) $row->inputs = $inputs;
      $row->save();
    }
  }
}
