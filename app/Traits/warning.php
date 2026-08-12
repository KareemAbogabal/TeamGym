<?php

namespace App\Traits;

trait Warning {
  function warning($request, $text) {
    if (empty($request)) {
      return redirect()->back()->with('error', $text);
    };
  }
}
