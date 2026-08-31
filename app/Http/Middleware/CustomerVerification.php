<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\Models\Front\Client;

class CustomerVerification {
  public function handle(Request $request, Closure $next): Response {
    if (Cookie::get('login_client')) {
      $client = Client::where("code", Cookie::get('login_client'))->first();
      if (!$client) {
        return redirect()->route('front');
      }
      Auth::guard('client')->login($client);
    } else {
      return redirect()->route('front');
    };
    return $next($request);
  }
}
