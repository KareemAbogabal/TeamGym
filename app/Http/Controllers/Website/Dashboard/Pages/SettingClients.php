<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Front\Client;
use App\Models\Front\SettingClient;
use App\Http\Requesters\Website\Dashboard\UpdateProfile\UpdateProfileRequest;

class SettingClients extends Controller {
  public function clientCode(): ?string {
    return Auth::guard('client')->user()?->code;
  }
  public function index(Request $request) {
    $client = Auth::guard('client')->user();
    return view('Website.Dashboard.Pages.settings', compact("client"));
  }
  public function updateProfile(UpdateProfileRequest $request) {
    if (!$request->filled('fname')) {
      return back()->withErrors(['all' => __('messages.failed-fname')]);
    };
    if (!$request->filled('lname')) {
      return back()->withErrors(['all' => __('messages.failed-lname')]);
    };
    if (!$request->filled('email')) {
      return back()->withErrors(['all' => __('messages.failed-email')]);
    };
    if (!$request->filled('phone')) {
      return back()->withErrors(['all' => __('messages.failed-phone')]);
    };
    if ($request->hasFile('profile_image') && !$request->file('profile_image')->isValid()) {
      return back()->withErrors(['all' => __('messages.failed-img')]);
    };
    if (!$request->has('action')) {
      return back()->withErrors(['all' => __('messages.failed-action')]);
    };
    $client = Auth::guard('client')->user();
    if (!$client) {
      return back()->withErrors(['all' => __('messages.unauthorized')]);
    }
    if ($request->input('action') !== "removePhoto") {
      if ($request->hasFile('profile_image')) {
        $oldPath = public_path('images/subscribers/'.$client->img);
        if ($client->img && File::exists($oldPath)) {
          File::delete($oldPath);
        };
        $imageName = time().'.'.$request->file('profile_image')->getClientOriginalExtension();
        $request->file('profile_image')->move(public_path('images/subscribers'), $imageName);
        $client->img = $imageName;
      };
      $client->fname = $request->fname;
      $client->lname = $request->lname;
      $client->email = $request->email;
      $client->phone = $request->phone;
      if ($request->has("password")) {
        $client->password = bcrypt($request->input("password"));
      };
      $client->save();
      $settings = $client->settings ?? new SettingClient();
      $settings->code_client = $client->code;
      $settings->class_reminders = $request->has('class_reminders') ? 1 : 0;
      $settings->payment_date = $request->has('payment_date') ? 1 : 0;
      $settings->promotions = $request->has('promotions') ? 1 : 0;
      $settings->save();
    } else {
      $oldPath = public_path('images/subscribers/'.$client->img);
      if ($client->img && File::exists($oldPath)) {
        File::delete($oldPath);
      };
      $client->img = null;
      $client->save();
    };
    notifySuccess(__('messages.updated-successfully'));
    return back();
  }
}
