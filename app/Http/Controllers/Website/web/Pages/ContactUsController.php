<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Models\Back\Order;
use App\Traits\Warning;
use App\Traits\GetLineage;

class ContactUsController extends Controller {
  use Warning, GetLineage;
  public function index(Request $request) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $lineages = $this->getlineages();
    $muscle = $this->getArray(LineageInBody::class, "SMM");
    $fat = $this->getArray(LineageInBody::class, "fat_mass");
    $water = $this->getArray(LineageInBody::class, "water");
    return view('Website.web.Pages.contactUs', compact("client", "lineages", "muscle", "fat", "water"));
  }
  public function store(Request $request) {
    $request->validate([
      'name' => ['required', 'string'],
      'phone' => ['required', 'string'],
      'subject' => ['required', 'string'],
    ]);
    $order = new Order();
    $order->code = rand(100000, time());
    $order->name = $request->input("name");
    $order->phone = $request->input("phone");
    $order->subject = $request->input("subject");
    $order->save();
    notifySuccess(__('messages.contact-sent'));
    return redirect()->back();
  }
}
