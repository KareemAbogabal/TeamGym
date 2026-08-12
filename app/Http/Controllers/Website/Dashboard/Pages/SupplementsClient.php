<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Back\Supplement;
use App\Models\Back\Payment;

class SupplementsClient extends Controller {
  public function index(Request $request) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $supplements = Client::where("code", Cookie::get('login_client'))->with(['payment' => function($query) {
      $query->where('type', 'supplement')->with('supplement');
    }])->first();
    $registriePaymentSupplements = Client::where("code", Cookie::get('login_client'))->with(['payment' => function($query) {
      $query->where('type', 'supplement')->with('registries');
    }])->first();
    $amount = 0;
    $paid = 0;
    $remaining = 0;
    if ($registriePaymentSupplements && $registriePaymentSupplements->payment->isNotEmpty()) {
      $payments = $registriePaymentSupplements->payment;
      $amount = $payments->sum(function($p) {
        return is_numeric($p->amount) ? (float) $p->amount : 0;
      });
      $paid = $payments->sum(function($p) {
        return is_numeric($p->paid) ? (float) $p->paid : 0;
      });
      $remaining = $amount - $paid;
    };
    return view('Website.Dashboard.Pages.supplementStore', compact("client", "supplements", "registriePaymentSupplements", "amount", "paid", "remaining"));
  }
}
