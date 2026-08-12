<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Models\Back\Activity;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\Payment;
use App\Models\Back\Imports;
use App\Models\Back\History;
use App\Traits\Warning;
use App\Traits\GetLineage;

class WelcomeController extends Controller {
  use Warning, GetLineage;
  public function front(Request $request) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $subscribers = Client::where("documentation", "true")->whereNotNull("img")->get();
    $lineages = $this->getlineages();
    $muscle = $this->getArray(LineageInBody::class, "SMM");
    $fat = $this->getArray(LineageInBody::class, "fat_mass");
    $water = $this->getArray(LineageInBody::class, "water");
    $activities = Activity::where("code_client", Cookie::get('login_client'))->where("state", "exercise")->get();
    $sets = count($activities);
    $systems = System::with("features")->whereNot("defult", "true")->get();
    $usedCodes = Payment::whereNotNull('code_supplements')->pluck('code_supplements')->unique();
    $supplements = Supplement::whereHas('imports', function ($q) {
      $q->where('quantity', '>', 0);
    })->with(['imports' => function ($q) {
      $q->where('quantity', '>', 0);
    }])->get();
    return view('Website.web.Pages.welcome', compact("client", "lineages", "sets", "systems", "supplements", "muscle", "fat", "water", "subscribers"));
  }
}