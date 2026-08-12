<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Back\Lineage;
use App\Models\Back\IncomeStatement;
use App\Models\Back\Supplement;
use App\Models\Front\Client;
use App\Models\Back\Payment;
use App\Traits\GetLineage;
use App\Traits\IncomeStatements;

class Analytics extends Controller {
  use GetLineage, IncomeStatements;
  public function index(Request $request) {
    $categories = ['system', 'supplement', 'input', 'revenues', 'expenses'];
    $dataGetArray = [];
    $dataGetLineage = [];
    foreach ($categories as $c) {
      $dataGetArray[$c] = $this->getArray(Lineage::class, "$c", false);
    };
    foreach ($categories as $c) {
      $dataGetLineage[$c] = $this->get(Lineage::class, "$c", false);
    };
    $systems = Client::all();
    $supplements = Payment::where("type", "supplement")->with("employee")->get();
    $incomeStatement = IncomeStatement::all();
    $rows = IncomeStatement::select('name', 'state', 'type', 'amount')->get();
    $toFloat = function($v) {
      if ($v === null) return 0.0;
      $s = (string) $v;
      $s = trim($s);
      if ($s === '') return 0.0;
      return is_numeric($s) ? (float) $s : 0.0;
    };
    $groupProdToCat = $rows->groupBy(function($r){
      return sprintf('%s|%s', $r->name, $r->state);
    });
    $links = [];
    foreach ($groupProdToCat as $key => $group) {
      $first = $group->first();
      if (empty($first->name) || empty($first->state)) continue;
      $flow = $group->reduce(function($carry, $item) use ($toFloat) {
        return $carry + $toFloat($item->amount);
      }, 0.0);
      if ($flow <= 0) continue;
      $links[] = [
        'from' => (string) $first->name,
        'to' => (string) $first->state,
        'flow' => $flow,
      ];
    }
    $groupTypeToCat = $rows->groupBy(function($r){
      return sprintf('%s|%s', $r->type, $r->state);
    });
    foreach ($groupTypeToCat as $g) {
      $first = $g->first();
      if (empty($first->type) || empty($first->state)) continue;
      $flow = $g->reduce(function($carry, $item) use ($toFloat) {
          return $carry + $toFloat($item->amount);
      }, 0.0);
      if ($flow <= 0) continue;
      $links[] = [
        'from' => (string) $first->type,
        'to' => (string) $first->state,
        'flow' => $flow,
      ];
    }
    $merged = [];
    foreach ($links as $l) {
      $k = $l['from'] . '|' . $l['to'];
      if (!isset($merged[$k])) $merged[$k] = 0.0;
      $merged[$k] += (float) $l['flow'];
    };
    $finalLinks = [];
    foreach ($merged as $k => $flow) {
      [$from, $to] = explode('|', $k, 2);
      $finalLinks[] = ['from' => $from, 'to' => $to, 'flow' => $flow];
    };
    $revenues = $this->stateIncomeStatement("revenues");
    $expenses = $this->stateIncomeStatement("expenses");
    return view('Company.Dashboard.Pages.analytics', compact('dataGetArray', 'dataGetLineage', 'systems', 'supplements', 'incomeStatement', 'finalLinks', 'revenues', 'expenses'));
  }
}
