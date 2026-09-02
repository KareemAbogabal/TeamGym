<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
    $code = $this->clientCode();
    $imgInBody = $code ? ImgInBody::where("code", $code)->first() : null;
    $client = Auth::guard('client')->user();
    return view('Website.Dashboard.Pages.health', compact('client', 'basis', 'minor', 'muscles', 'fat', 'water', 'imgInBody'));
  }
  public function saveImgInBody(Request $request) {
    $request->validate([
      'img' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
    ]);
    try {
      $code = $this->clientCode();
      if (!$code) {
        return response()->json(['status' => 'unauthorized'], 401);
      }
      $img = $request->file('img');
      if (!$img->isValid() || in_array($img->getClientOriginalExtension(), ['php', 'phtml', 'phar'], true)) {
        Log::warning('saveImgInBody: rejected suspicious image', [
          'code' => $code,
          'original' => $img->getClientOriginalName(),
        ]);
        return response()->json(['status' => 'invalid'], 422);
      }
      $imgInBody = ImgInBody::where("code", $code)->first();
      $new_name = time() . '_' . bin2hex(random_bytes(8)) . '.' . $img->getClientOriginalExtension();
      $targetDir = public_path("Images/inBody");
      if (!File::exists($targetDir)) {
        File::makeDirectory($targetDir, 0777, true, true);
      }
      if ($imgInBody && $imgInBody->img && File::exists($targetDir . '/' . $imgInBody->img)) {
        File::delete($targetDir . '/' . $imgInBody->img);
      };
      $img->move($targetDir, $new_name);
      $imgInBody = ImgInBody::where("code", $code)->first();
      if (!$imgInBody) {
        $imgInBody = new ImgInBody();
        $imgInBody->addImgInBody($new_name);
      } else {
        $imgInBody->img = $new_name;
        $imgInBody->save();
      };
      return response()->json(['status' => 'ok']);
    } catch (\Throwable $e) {
      Log::error('saveImgInBody error', [
        'code' => $this->clientCode(),
        'message' => $e->getMessage(),
      ]);
      return response()->json(['status' => 'error'], 500);
    }
  }
  public function addLineagesClient(Request $request) {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'lineage' => ['required', 'numeric'],
    ]);
    try {
      $name = $request->input('name');
      $lineage = $request->input('lineage');
      $LineageInBody = new LineageInBody();
      $LineageInBody->addLineages($name, $lineage);
      return response()->json(['status' => 'ok']);
    } catch (\Throwable $e) {
      Log::error('addLineagesClient error', [
        'code' => $this->clientCode(),
        'message' => $e->getMessage(),
      ]);
      return response()->json(['status' => 'error'], 500);
    }
  }
}
