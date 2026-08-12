<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Back\Record;
use Carbon\Carbon;

class Plans extends Controller {
  public function index(Request $request) {
    $plan = Client::where("code", Cookie::get('login_client'))->with(['payment' => function($query) {
      $query->where('type', 'system');
    }])->first();
    $registriePayments = Client::where('code', Cookie::get('login_client'))->with('payment.registries.employee')->first();
    $records = Record::where('code_client', Cookie::get('login_client'))->with('employee')->get();
    $client = Client::where("code", Cookie::get('login_client'))->first();
    return view('Website.Dashboard.Pages.plans', compact("client", "plan", "registriePayments", "records"));
  }
}
