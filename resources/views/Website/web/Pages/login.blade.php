<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="google" content="notranslate">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="{{ __('messages.meta-description-login') }}">
  <link rel="icon" href="{{asset("images/header/Team-Gym.png")}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="{{asset("css/Website/web/public.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/pages/login.css")}}">
  <link rel="stylesheet" href="{{asset("css/notification.css")}}">
  <title>Team Gym | Login</title>
</head>
<body>
  @php
    $showVerify = session('client_reset_email') && !session('client_reset_verified');
    $showReset = session('client_reset_email') && session('client_reset_verified');
  @endphp
  <x-components::notifications />
  <main>
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>

    <form action="{{route("signUp")}}" method="post" class="panel-form panel-signup hidden">
      @csrf
      <div class="panel-header">
        <h1>{{__('messages.sigin-up-h1')}}</h1>
        <p>{{__('messages.card-p-sigin-up')}}</p>
      </div>
      <div class="row">
        <div class="nebula-input">
          <input type="text" name="fname" id="signup-fname" class="input" autocomplete="given-name" />
          <label class="user-label" for="signup-fname">{{__('messages.form-fname')}}</label>
        </div>
        <div class="nebula-input">
          <input type="text" name="lname" id="signup-lname" class="input" autocomplete="family-name" />
          <label class="user-label" for="signup-lname">{{__('messages.form-lname')}}</label>
        </div>
      </div>
      <div class="nebula-input">
        <input type="text" name="phone" id="signup-phone" class="input" autocomplete="tel" />
        <label class="user-label" for="signup-phone">{{__('messages.form-phone')}}</label>
      </div>
      <div class="nebula-input">
        <input type="email" name="email" id="signup-email" class="input" autocomplete="email" />
        <label class="user-label" for="signup-email">{{__('messages.form-email')}}</label>
      </div>
      <div class="nebula-input">
        <input type="password" name="password" id="signup-password" class="input" autocomplete="new-password" />
        <label class="user-label" for="signup-password">{{__('messages.form-password')}}</label>
      </div>
      <button type="submit" class="form-submit">{{__('messages.form-create-account')}}</button>
    </form>

    <div class="card @if($showVerify || $showReset) hidden @endif">
      <div class="card-orbs">
        <span class="orb orb-a"></span>
        <span class="orb orb-b"></span>
        <span class="orb orb-c"></span>
      </div>
      <div class="card-content">
        <div class="brand">
          <i class="fas fa-dumbbell"></i>
        </div>
        <div class="card-text">
          <div class="card-copy card-copy-login">
            <h1>{{__('messages.card-h1-friend')}}</h1>
            <p>{{__('messages.card-p-login')}}</p>
          </div>
          <div class="card-copy card-copy-signup">
            <h1>{{__('messages.card-h1-buddy')}}</h1>
            <p>{{__('messages.card-p-sigin-up')}}</p>
          </div>
        </div>
      </div>
      <button type="button" class="switch">
        <span class="switch-label switch-login">{{__('messages.card-button-sigin-up')}}</span>
        <span class="switch-label switch-signup">{{__('messages.card-button-login')}}</span>
        <span class="switch-arrow"><i class="fas fa-arrow-right"></i></span>
      </button>
    </div>

    <form action="{{route("client.login")}}" method="post" class="panel-form panel-login @if($showVerify || $showReset) hidden @endif">
      @csrf
      <div class="panel-header">
        <h1>{{__('messages.login-h1')}}</h1>
        <p>{{__('messages.card-p-login')}}</p>
      </div>
      @if ($errors->any())
        <div class="login-error" role="alert">{{ $errors->first() }}</div>
      @endif
      <div class="nebula-input">
        <input type="email" name="email" id="login-email" class="input" value="{{ old('email') }}" autocomplete="email" />
        <label class="user-label" for="login-email">{{__('messages.form-email')}}</label>
      </div>
      <div class="nebula-input">
        <input type="password" name="password" id="login-password" class="input" autocomplete="current-password" />
        <label class="user-label" for="login-password">{{__('messages.form-password')}}</label>
      </div>
      <button type="submit" class="form-submit">{{__('messages.card-button-login')}}</button>
      <button type="button" class="forget">{{__('messages.form-forget-my-password')}}</button>
    </form>

    <form action="{{route("client.forget")}}" method="post" class="hidden overlay-form">
      @csrf
      <h1>{{__('messages.login-h1')}}</h1>
      <div class="nebula-input">
        <input type="email" name="email" id="forget-email" class="input" autocomplete="email" />
        <label class="user-label" for="forget-email">{{__('messages.form-email')}}</label>
      </div>
      <button type="submit">{{__('messages.form-send')}}</button>
      <button type="button" class="form-back">{{__('messages.form-back-to-login')}}</button>
    </form>
    <form action="{{route("client.verifyCode")}}" method="post" class="@if(!$showVerify) hidden @endif overlay-form">
      @csrf
      <h1>{{__('messages.form-verification-code')}}</h1>
      @if ($errors->any())
        <div class="login-error" role="alert">{{ $errors->first() }}</div>
      @endif
      <div class="nebula-input">
        <input type="text" name="code" id="verify-code" class="input code-input" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" />
        <label class="user-label" for="verify-code">{{__('messages.form-code')}}</label>
      </div>
      <button type="submit">{{__('messages.form-send')}}</button>
      <button type="button" class="form-back">{{__('messages.form-back-to-login')}}</button>
    </form>
    <form action="{{route("client.resetPassword")}}" method="post" class="@if(!$showReset) hidden @endif overlay-form">
      @csrf
      <h1>{{__('messages.form-new-password')}}</h1>
      @if ($errors->any())
        <div class="login-error" role="alert">{{ $errors->first() }}</div>
      @endif
      <div class="nebula-input">
        <input type="password" name="password" id="reset-password" class="input" minlength="8" autocomplete="new-password" />
        <label class="user-label" for="reset-password">{{__('messages.form-new-password')}}</label>
      </div>
      <div class="nebula-input">
        <input type="password" name="password_confirmation" id="reset-password-confirm" class="input" minlength="8" autocomplete="new-password" />
        <label class="user-label" for="reset-password-confirm">{{__('messages.form-confirm-password')}}</label>
      </div>
      <button type="submit">{{__('messages.form-send')}}</button>
      <button type="button" class="form-back">{{__('messages.form-back-to-login')}}</button>
    </form>
  </main>
  <script src="{{asset("js/login.js")}}"></script>
  <script src="{{asset("js/notification.js")}}"></script>
</body>
</html>