<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('requests.{userId}', function ($user, $userId) {
  return Auth::guard('employee')->check();
});
