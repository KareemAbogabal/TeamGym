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
    $incomeStatementCountType = IncomeStatement::whereRaw('LOWER(type) = ?', "$name")->get();
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
    $totalAll = $totalExpenses + $totalRevenues;
    $expensesPct = $max > 0 ? round(($totalExpenses / $max) * 100, 2) : 0;
    $revenuesPct = $max > 0 ? round(($totalRevenues / $max) * 100, 2) : 0;
    $totalAmount = 0;
    $lineage = 0;
    $state = 0;
    if ($type == "revenues") {
      $incomeStatementCount = IncomeStatement::whereRaw('LOWER(type) = ?', "expenses")->get();
      $incomeStatementCountInput = IncomeStatement::whereRaw('LOWER(type) = ?', "input")->get();
      $expenses = 0;
      $input = 0;
      $total = 0;
      foreach ($incomeStatementCountType as $i) {
        $totalAmount += $i->amount;
      };
      foreach ($incomeStatementCount as $i) {
        $expenses += $i->amount;
      };
      // foreach ($incomeStatementCountInput as $i) {
      //   $input += $i->amount;
      // };
      // $totalAmount = $total - ($expenses - $input);
      $lineage = $revenuesPct;
      if ($totalAmount > $expenses) {
        $state = 1;
      };
      return [
        "total" => $totalAmount,
        "lineage" => $lineage,
        "state" => $state,
      ];
    } else {
      $incomeStatementCount = IncomeStatement::whereRaw('LOWER(type) = ?', "revenues")->get();
      $revenues = 0;
      foreach ($incomeStatementCount as $i) {
        $revenues += $i->amount;
      };
      foreach ($incomeStatementCountType as $i) {
        $totalAmount += $i->amount;
      };
      $lineage = $expensesPct;
      if ($totalAmount > $revenues) {
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
