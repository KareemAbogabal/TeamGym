<?php

namespace App\Traits;
use App\Models\Back\IncomeStatement;
use App\Models\Back\Lineage;
use App\Traits\GetLineage;

trait IncomeStatements {
  use GetLineage;
  public function addStatements($name, $state, $type, $amount) {
    $rand = rand(100000, time());
    $incomeStatement = new IncomeStatement();
    $incomeStatement->code = $rand;
    $incomeStatement->name = $name;
    $incomeStatement->state = $state;
    $incomeStatement->type = $type;
    $incomeStatement->amount = $amount;
    $incomeStatement->save();
    Lineage::addLineage(null, null, null, $rand, null, $type, 1);
  }
  public function stateIncomeStatement($type) {
    $name = mb_strtolower($type);
    $currentAmounts = IncomeStatement::whereRaw('LOWER(type) = ?', "$name")->pluck('amount');
    $max = 20000;
    $toFloatSafe = function ($v) {
      if ($v === null) return 0.0;
      $s = trim((string) $v);
      if ($s === '') return 0.0;
      $s = str_replace('٫', '.', $s);
      $s = str_replace([',', ' '], '', $s);
      return is_numeric($s) ? (float) $s : 0.0;
    };
    $expensesAmounts = IncomeStatement::where('type', 'Expenses')->pluck('amount');
    $totalExpenses = $expensesAmounts->reduce(fn($carry, $item) => $carry + $toFloatSafe($item), 0.0);
    $revenuesAmounts = IncomeStatement::where('type', 'Revenues')->pluck('amount');
    $totalRevenues = $revenuesAmounts->reduce(fn($carry, $item) => $carry + $toFloatSafe($item), 0.0);
    $expensesPct = $max > 0 ? round(($totalExpenses / $max) * 100, 2) : 0;
    $revenuesPct = $max > 0 ? round(($totalRevenues / $max) * 100, 2) : 0;
    $lineage = 0;
    $state = 0;
    $totalAmount = $currentAmounts->reduce(fn($carry, $item) => $carry + $item, 0);
    if ($type == "revenues") {
      $expensesSum = IncomeStatement::whereRaw('LOWER(type) = ?', "expenses")->pluck('amount')
        ->reduce(fn($carry, $item) => $carry + $item, 0);
      $lineage = $revenuesPct;
      if ($totalAmount > $expensesSum) {
        $state = 1;
      };
      return [
        "total" => $totalAmount,
        "lineage" => $lineage,
        "state" => $state,
      ];
    } else {
      $revenuesSum = IncomeStatement::whereRaw('LOWER(type) = ?', "revenues")->pluck('amount')
        ->reduce(fn($carry, $item) => $carry + $item, 0);
      $lineage = $expensesPct;
      if ($totalAmount > $revenuesSum) {
        $state = 1;
      };
      return [
        "total" => $totalAmount,
        "lineage" => $lineage,
        "state" => $state,
      ];
    }
  }
}
