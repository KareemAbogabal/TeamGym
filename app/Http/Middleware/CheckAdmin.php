<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin {
  public function handle(Request $request, Closure $next): Response {
    if (Gate::allows('admin')) {
      return $next($request);
    };
    return back()->withError(["error" => __('messages.error-admin')]);
  }
}
