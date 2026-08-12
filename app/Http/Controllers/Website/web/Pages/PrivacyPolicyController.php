<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Traits\Warning;
use App\Traits\GetLineage;

class PrivacyPolicyController extends Controller {
  use Warning, GetLineage;
  public function privacyPolicy(Request $request) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $lineages = $this->getlineages();
    $muscle = $this->getArray(LineageInBody::class, "SMM");
    $fat = $this->getArray(LineageInBody::class, "fat_mass");
    $water = $this->getArray(LineageInBody::class, "water");
    return view('Website.web.Pages.privacyPolicy', compact("client", "lineages", "muscle", "fat", "water"));
  }
}
