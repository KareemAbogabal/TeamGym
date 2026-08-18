<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Back\FeatureSystem;
use App\Models\Back\System;
use App\Models\Back\Snacks;
use App\Models\Back\Supplement;
use App\Models\Back\Imports;
use App\Models\Front\Client;
use App\Models\Notification;
use App\Traits\Notifications;

class Publications extends Controller {
  use Notifications;
  public function index(Request $request) {
    $systems = System::with("features")->whereNot("defult", "true")->get();
    $supplements = Supplement::all();
    $systemDefult = System::where("defult", "true")->first();
    return view('Company.Dashboard.Pages.publications', compact("systems", "supplements", "systemDefult"));
  }
  public function addSystem(Request $request) {
    $request->validate([
      'name_system' => ['required', 'string'],
      'price_system' => ['required', 'string'],
      'duration_system' => ['required', 'string'],
      'feature' => ['required','array'],
      'feature.*' => ['required'],
    ]);
    $randSystem = rand(100000, time());
    $randFeature = rand(100000, time());
    // $systems = System::all()->count();
    // if ($systems == 4) {
    //   return back()->withErrors(['error' => 'No other system can be added.']);
    // };
    $system = new System();
    $system->code = $randSystem;
    $system->name = $request->input("name_system");
    $system->amount = $request->input("price_system");
    $system->code_features = $randFeature;
    $system->duration = $request->input("duration_system");
    $system->defult = $request->input("defult") ? "true" : "false";
    $features = $request->input("feature", []);
    $system->save();
    foreach ($features as $f) {
      $featureSystem = new FeatureSystem();
      $featureSystem->code = $randFeature;
      $featureSystem->name = $f;
      $featureSystem->state = "false";
      $featureSystem->code_system = $randSystem;
      $featureSystem->save();
    };
    notifySuccess(__('messages.saved-successfully'));
    return back();
  }
  public function updateSystem(Request $request) {
    $request->validate([
      'system_code' => ['required', 'string'],
      'system_title' => ['required', 'string'],
      'system_price' => ['required', 'string'],
      'system_duration' => ['required', 'string'],
      'feature_new' => ['nullable','array'],
      'feature_new.*' => ['nullable'],
      'feature' => ['nullable','array'],
      'feature.*' => ['nullable'],
    ]);
    $randFeature = rand(100000, time());
    $system = System::where("code", $request->input("system_code"))->first();
    $featureSystem = FeatureSystem::where("code_system", $system->code)->first();
    $codeNew = $featureSystem->code;
    $system->name = Str::lower($request->input("system_title"));
    $system->amount = (int) $request->input("system_price");
    $system->duration = (int) $request->input("system_duration");
    $features = $request->input("feature", []);
    $featuresNew = $request->input("feature_new", []);
    $featuresName = $request->input("feature_name", []);
    $system->save();
    if ($featuresNew) {
      foreach ($featuresNew as $f) {
        $featureSystem = new FeatureSystem();
        $featureSystem->code = $codeNew;
        $featureSystem->name = $f;
        $featureSystem->state = "true";
        $featureSystem->code_system = $system->code;
        $featureSystem->save();
      };
    } else {
      foreach ($featuresName as $index => $name) {
        $val = $features[$index] ?? 'false';
        if ($val === 'true' || $val === '1' || $val === 1) {
          $fs = FeatureSystem::where('code_system', $system->code)->where('name', $name)->first();
          if ($fs) {
            $fs->state = "true";
            $fs->save();
          };
        } else {
          $fs = FeatureSystem::where('code_system', $system->code)->where('name', $name)->first();
          if ($fs) {
            $fs->delete();
          };
        };
      };
    };
    notifySuccess(__('messages.updated-successfully'));
    return back();
  }
  public function removeSystem(Request $request) {
    $request->validate([
      'code' => ['required', 'string'],
    ]);
    $system = System::where("code", $request->input("code"))->first();
    $system->delete();
    notifySuccess(__('messages.deleted-successfully'));
    return back();
  }
  // public function addSupplement(Request $request) {
  //   $request->validate([
  //     'img' => ['required', 'mimes:png,jpg,jpeg', 'max:5120'],
  //     'name_product' => ['required', 'string'],
  //     'price_product' => ['required', 'string'],
  //     'content_product' => ['required', 'string'],
  //     'discount_product' => ['nullable']
  //   ]);
  //   $rand = rand(100000, time());
  //   $nameLower = mb_strtolower($request->input("name_product"));
  //   $client = Client::all();
  //   $supplement = new Supplement();
  //   $supplement->code = $rand;
  //   $supplement->name = $request->input("name_product");
  //   $supplement->description = (string) $request->input("content_product");
  //   if ($request->has("img")) {
  //     date_default_timezone_set("Africa/Cairo");
  //     $d = date_create();
  //     $new_name = date_format($d, "Y-m-j_g-i_A") . "." . $request->img->extension();
  //     $request->img->move(public_path("images/products"), $new_name);
  //     $supplement->img = $new_name;
  //   };
  //   $supplement->amount = $request->input("price_product");
  //   if ($request->has("discount_product")) {
  //     $supplement->discount = $request->input("discount_product");
  //     $name = __('messages.discount_name');
  //     $description = __('messages.discount_description', ['name' => $supplement->name, 'amount' => $supplement->amount, 'discount' => $supplement->discount]);
  //     foreach ($client as $c) {
  //       $this->makeNotification($name, "discount", $description, $c->code, null, "iconSupplement");
  //     };
  //   };
  //   $supplement->save();
  //   $searchProduct = Imports::whereRaw('LOWER(name) = ?', "$nameLower")->first();
  //   if ($searchProduct) {
  //     $searchProduct->code_supplements = $supplement->code;
  //     $searchProduct->save();
  //   };
  //   return back();
  // }
  public function updateSupplement(Request $request) {
    $request->validate([
      'img' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
      'code_product' => ['required', 'string'],
      'name_product' => ['required', 'string'],
      'price_product' => ['required', 'string'],
      'content_product' => ['required', 'string'],
      'discount_product' => ['nullable']
    ]);
    $supplement = Supplement::where("code", $request->input("code_product"))->first();
    $client = Client::all();
    $supplement->name = $request->input("name_product");
    $supplement->description = (string) $request->input("content_product");
    if ($request->hasFile("img")) {
      if (!empty($supplement->img)) {
        $oldPath = public_path('images/products/' . $supplement->img);
        if (File::exists($oldPath) && is_file($oldPath)) {
          File::delete($oldPath);
          date_default_timezone_set("Africa/Cairo");
          $d = date_create();
          $new_name = date_format($d, "Y-m-j_g-i_A") . "." . $request->img->extension();
          $request->img->move(public_path("images/products"), $new_name);
          $supplement->img = $new_name;
        };
      };
    };
    $supplement->amount = $request->input("price_product");
    if ($request->has("discount_product")) {
      $supplement->discount = $request->input("discount_product");
      $name = __('messages.discount_name');
      $description = __('messages.discount_description', ['name' => $supplement->name, 'amount' => $supplement->amount, 'discount' => $supplement->discount]);
      foreach ($client as $c) {
        $this->makeNotification($name, "discount", $description, $c->code, null, "iconSupplement");
      };
    };
    $supplement->save();
    notifySuccess(__('messages.updated-successfully'));
    return back();
  }
  public function destroySupplements(Request $request) {
    $request->validate([
      'code' => ['required', 'string'],
    ]);
    $supplement = Supplement::where("code", $request->input("code"))->first();
    $dir = public_path("images/products/" . $supplement->img);
    if (File::exists($dir) && is_dir($dir)) {
      File::delete($dir);
    };
    $supplement->delete();
    notifySuccess(__('messages.deleted-successfully'));
    return back();
  }
  // public function addSnack(Request $request) {
  //   $request->validate([
  //     'name_snack' => ['required', 'string'],
  //     'price_snack' => ['required', 'string'],
  //   ]);
  //   $rand = rand(100000, time());
  //   $nameLower = mb_strtolower($request->input("name_snack"));
  //   $snacks = new Snacks();
  //   $snacks->code = $rand;
  //   $snacks->name = $request->input("name_snack");
  //   $snacks->amount = $request->input("price_snack");
  //   $snacks->save();
  //   $searchProduct = Imports::whereRaw('LOWER(name) = ?', "$nameLower")->first();
  //   if ($searchProduct) {
  //     $searchProduct->code_snaks = $rand;
  //     $searchProduct->save();
  //   };
  //   return back();
  // }
  public function notificationDiscount(Request $request) {
    $notifications = Notification::where("type", "discount")->get();
    return json_encode($notifications);
  }
}
