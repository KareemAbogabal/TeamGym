<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Front\Client;
use App\Models\Front\Cardio;

class BurnMeter extends Controller {
  public function index(Request $request) {
    $code_client = Auth::guard('client')->user()?->code;
    $cardios = $code_client ? Cardio::where("code_client", $code_client)->get() : collect();
    $client = Auth::guard('client')->user();
    return view('Website.Dashboard.Pages.burnMeter', compact("client", "code_client", "cardios"));
  }
  public function saveDataCardio(Request $request) {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'minutes' => ['required', 'numeric', 'min:0', 'max:100000'],
      'distance' => ['nullable', 'numeric'],
      'start_latitude' => ['nullable', 'numeric', 'between:-90,90'],
      'start_longitude' => ['nullable', 'numeric', 'between:-180,180'],
      'end_latitude' => ['nullable', 'numeric', 'between:-90,90'],
      'end_longitude' => ['nullable', 'numeric', 'between:-180,180'],
    ]);
    $code = Auth::guard('client')->user()?->code;
    if (!$code) {
      return response()->json(['status' => 'unauthorized'], 401);
    }
    $rand = rand(100000, time());
    $cardio = new Cardio();
    $cardio->code = $rand;
    $cardio->code_client = $code;
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
    return response()->json(['status' => 'ok']);
  }
}
