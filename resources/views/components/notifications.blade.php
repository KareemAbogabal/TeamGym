@php
  $notifications = [];
  $flash = session('notification');
  if (is_array($flash)) {
    if (isset($flash['type']) || isset($flash['message'])) {
      $notifications[] = $flash;
    } else {
      foreach ($flash as $n) {
        if (is_array($n) && (isset($n['type']) || isset($n['message']))) {
          $notifications[] = $n;
        }
      }
    }
  } elseif (is_string($flash) && $flash !== '') {
    $notifications[] = ['type' => 'info', 'message' => $flash];
  }
  if (session('success')) {
    $notifications[] = ['type' => 'success', 'message' => session('success')];
  }
  if (session('error')) {
    $notifications[] = ['type' => 'error', 'message' => session('error')];
  }
  if (isset($errors) && $errors instanceof Illuminate\Support\ViewErrorBag) {
    foreach ($errors->all() as $error) {
      $notifications[] = ['type' => 'error', 'message' => $error];
    }
  }
@endphp
@if (count($notifications))
  <div class="toasts-container">
    @foreach ($notifications as $n)
      <x-components::notification :type="$n['type'] ?? 'info'" :message="$n['message']" />
    @endforeach
  </div>
@endif
