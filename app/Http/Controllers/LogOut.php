<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LogOut extends Controller {
  public function logOutEmployee(Request $request) {
    Auth::guard('employee')->logout();
    return view('Company.web.Pages.loginCompany');
  }
  public function logOutClient(Request $request) {
    Cookie::queue(Cookie::forget('login_client'));
    return redirect()->route('front');
  }
}
