<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

/**
 * Client-area authentication guard.
 *
 * Identity comes from the server-side authenticated session ONLY. The legacy
 * `login_client` cookie is accepted only as a hint that MUST agree with the
 * authenticated session identity; a spoofed cookie alone never authenticates.
 */
class CustomerVerification {
  public function handle(Request $request, Closure $next): Response {
    $client = Auth::guard('client')->user();
    if (!$client) {
      return redirect()->route('loginPage');
    }
    $cookieCode = Cookie::get('login_client');
    if ($cookieCode !== null && $cookieCode !== $client->code) {
      Cookie::queue(Cookie::forget('login_client'));
    }
    return $next($request);
  }
}