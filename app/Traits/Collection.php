<?php

namespace App\Traits;
use App\Models\Back\Imports;
use App\Models\Back\Supplement;
use App\Models\Back\IncomeStatement;
use App\Models\Front\Client;

trait Collection {
  public function collectionOfRatios($Model, $column, $type) {
    $incomeStatementCountType = $Model::where("$column", "$type")->get();
    $max = 20000;
    $toFloatSafe = function ($v) {
      if ($v === null) return 0.0;
      $s = trim((string) $v);
      if ($s === '') return 0.0;
      $s = str_replace('٫', '.', $s);
      $s = str_replace([',', ' '], '', $s);
      return is_numeric($s) ? (float) $s : 0.0;
    };
    $amounts = $incomeStatementCountType->pluck('amount');
    $total = $amounts->reduce(fn($carry, $item) => $carry + $toFloatSafe($item), 0.0);
    $totalAmount = 0;
    $lineage = $max > 0 ? round(($total / $max) * 100, 2) : 0;
    $state = 0;
    foreach ($incomeStatementCountType as $i) {
      $totalAmount += $i->amount;
    };
    if ($totalAmount > $max) {
      $state = 1;
    };
    return [
      "total" => $totalAmount,
      "lineage" => $lineage,
      "state" => $state,
    ];
  }
}
