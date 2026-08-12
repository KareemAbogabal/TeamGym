<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Models\Back\Supplement;
use App\Traits\Warning;
use App\Traits\GetLineage;

class StoreController extends Controller {
  use Warning, GetLineage;
  public function stores(Request $request) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $lineages = $this->getlineages();
    $muscle = $this->getArray(LineageInBody::class, "SMM");
    $fat = $this->getArray(LineageInBody::class, "fat_mass");
    $water = $this->getArray(LineageInBody::class, "water");
    $supplements = Supplement::whereHas('imports', function ($q) {
      $q->where('quantity', '>', 0);
    })->with(['imports' => function ($q) {
      $q->where('quantity', '>', 0);
    }])->get();
    return view('Website.web.Pages.store', compact("client", "lineages", "muscle", "fat", "water", "supplements"));
  }
}
