<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Front\LineageInBody;
use App\Models\Front\Client;
use App\Models\Front\ImgInBody;
use App\Traits\GetLineage;
use Carbon\Carbon;

class Health extends Controller {
  use GetLineage;
  public function index(Request $request) {
    $basis = [];
    $minor = [];
    $itemsBasis = [
      "weight", "BMI", "PBF", "SMM", "KCAL", "water", "fat_mass", "protein"
    ];
    $itemsMinor = [
      "left_arm_lean", "right_arm_lean", "right_leg_lean", "left_leg_lean",
      "left_arm_fat", "right_arm_fat", "right_leg_fat", "left_leg_fat"
    ];
    foreach ($itemsBasis as $b) {
      $basis[$b] = $this->get(LineageInBody::class, $b);
    };
    foreach ($itemsMinor as $m) {
      $minor[$m] = $this->get(LineageInBody::class, $m);
    };
    $muscles = $this->getArray(LineageInBody::class, "SMM", true, false);
    $fat = $this->getArray(LineageInBody::class, "fat_mass", true, false);
    $water = $this->getArray(LineageInBody::class, "water", true, false);
    $imgInBody = ImgInBody::where("code", Cookie::get('login_client'))->first();
    $client = Client::where("code", Cookie::get('login_client'))->first();
    return view('Website.Dashboard.Pages.health', compact('client', 'basis', 'minor', 'muscles', 'fat', 'water', 'imgInBody'));
  }
  public function saveImgInBody(Request $request) {
    try {
      $imgInBody = ImgInBody::where("code", Cookie::get('login_client'))->first();
      if ($request->has("img")) {
        date_default_timezone_set("Africa/Cairo");
        $d = date_create();
        $new_name = date_format($d, "Y-m-j_g-i_A") . "." . $request->img->extension();
        if (!$imgInBody) {
          $request->img->move(public_path("Images/inBody"), $new_name);
          $imgInBody = new ImgInBody();
          $imgInBody->addImgInBody($new_name);
        } else {
          if ($imgInBody && File::exists(public_path("Images/inBody/" . $imgInBody->img))) {
            File::delete(public_path("Images/inBody/" . $imgInBody->img));
          };
          $request->img->move(public_path("Images/inBody"), $new_name);
          $imgInBody = new ImgInBody();
          $imgInBody->addImgInBody($new_name);
        };
      } else {
        return back()->withErrors(['all' => __('messages.failed-img')]);
      }
      return response()->json(['status' => 'ok']);
    } catch (\Throwable $e) {
      Log::error('saveImgInBody error: '.$e->getMessage(), [
        'exception' => $e
      ]);
      return response()->json([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ], 500);
    }
  }
  public function addLineagesClient(Request $request) {
    try {
      $name = $request->input('name');
      $lineage = $request->input('lineage');
      $img = $request->input('img');
      $LineageInBody = new LineageInBody();
      $LineageInBody->addLineages($name, $lineage);
      return response()->json(['status' => 'ok']);
    } catch (\Throwable $e) {
      Log::error('addLineagesClient error: '.$e->getMessage(), [
        'exception' => $e
      ]);
      return response()->json([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ], 500);
    }
  }
}
