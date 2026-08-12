<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\CustomerRequests;
use App\Traits\Warning;
use App\Traits\GetLineage;

class ArticleController extends Controller {
  use Warning, GetLineage;
  public function article(Request $request) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $clientRequests = CustomerRequests::where("code", Cookie::get('code_request_client'))->get();
    if ($client) {
      foreach ($clientRequests as $r) {
        if ($r->code_client == null) {
          $r->code_client = $client->code;
          $r->save();
        };
      };
    };
    $lineages = $this->getlineages();
    $muscle = $this->getArray(LineageInBody::class, "SMM");
    $fat = $this->getArray(LineageInBody::class, "fat_mass");
    $water = $this->getArray(LineageInBody::class, "water");
    if ($client && $clientRequests) {
      $customerRequests = CustomerRequests::where("code_client", $client->code)->get();
    } else {
      $customerRequests = CustomerRequests::where("code", Cookie::get('code_request_client'))->get();
    };
    $systems = System::with("features")->whereNot("defult", "true")->get();
    $supplements = Supplement::whereHas('imports', function ($q) {
      $q->where('quantity', '>', 0);
    })->with(['imports' => function ($q) {
      $q->where('quantity', '>', 0);
    }])->get();
    return view('Website.web.Pages.article', compact("client", "lineages", "muscle", "fat", "water", "systems", "supplements", "customerRequests"));
  }
}
