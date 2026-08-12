<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Back\History;

class Historys extends Controller {
  public function index(Request $request) {
    $histories = History::with(['client','employee'])->get();
    return view('Company.Dashboard.Pages.history', compact("histories"));
  }
}
