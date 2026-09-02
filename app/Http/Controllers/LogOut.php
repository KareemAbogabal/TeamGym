<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

/**
 * Logout terminates the server-side session lifecycle:
 * guard cleared, session invalidated + regenerated, CSRF token rotated,
 * authentication cookies removed.
 */
class LogOut extends Controller {
  public function logOutEmployee(Request $request) {
    Auth::guard('employee')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('loginCompany');
  }

  public function logOutClient(Request $request) {
    Auth::guard('client')->logout();
    Cookie::queue(Cookie::forget('login_client'));
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('front');
  }
}