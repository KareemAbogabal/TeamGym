<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Back\Order;

class ContactUs extends Controller {
  public function index(Request $request) {
    $orders = Order::orderBy("created_at", "desc")->get();
    return view('Company.Dashboard.Pages.contactUs', compact("orders"));
  }
  public function destroy(Request $request) {
    $request->validate([
      'code' => ['required', 'string'],
    ]);
    $order = Order::where("code", $request->input("code"))->first();
    if ($order) {
      $order->delete();
    };
    notifySuccess(__('messages.delete-done'));
    return back();
  }
}
