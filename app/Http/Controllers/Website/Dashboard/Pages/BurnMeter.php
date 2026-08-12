<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\Cardio;

class BurnMeter extends Controller {
  public function index(Request $request) {
    $code_client = Cookie::get('login_client');
    $cardios = Cardio::where("code_client", $code_client)->get();
    $client = Client::where("code", Cookie::get('login_client'))->first();
    return view('Website.Dashboard.Pages.burnMeter', compact("client", "code_client", "cardios"));
  }
  public function saveDataCardio(Request $request) {
    $rand = rand(100000, time());
    $cardio = new Cardio();
    $cardio->code = $rand;
    $cardio->code_client = $request->input("code_client");
    $cardio->name = $request->input("name");
    $cardio->minutes = $request->input("minutes");
    $cardio->distance = $request->input("distance");
    $cardio->start_latitude = $request->input("start_latitude");
    $cardio->start_longitude = $request->input("start_longitude");
    if ($request->input("end_latitude") !== null && $request->input("end_longitude") !== null) {
      $cardio->end_latitude = $request->input("end_latitude");
      $cardio->end_longitude = $request->input("end_longitude");
    };
    $cardio->save();
  }
}
