<?php

if (!function_exists('notify')) {
  /**
   * Flash a toast notification of the given type.
   *
   * @param  string  $type    info | success | warning | error
   * @param  string  $message
   * @return void
   */
  function notify(string $type, string $message): void {
    session()->flash('notification', [
      'type' => $type,
      'message' => $message,
    ]);
  }
}

if (!function_exists('notifyInfo')) {
  function notifyInfo(string $message): void {
    notify('info', $message);
  }
}

if (!function_exists('notifySuccess')) {
  function notifySuccess(string $message): void {
    notify('success', $message);
  }
}

if (!function_exists('notifyError')) {
  function notifyError(string $message): void {
    notify('error', $message);
  }
}
