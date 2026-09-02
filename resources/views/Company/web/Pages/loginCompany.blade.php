<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="google" content="notranslate">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="{{ __('messages.meta-description-login-company') }}">
  <link rel="icon" href="{{asset("images/header/Team-Gym.png")}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="{{asset("css/Company/pages/loginCompany.css")}}">
  <link rel="stylesheet" href="{{asset("css/notification.css")}}">
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <title>Team Gym | Company Login</title>
</head>
<body>
  <x-components::notifications />
  <main>
    <div class="img">
      <img src="{{asset("images/content/login-company.png")}}" alt="No Img Login">
    </div>
    <form action="{{route("company.forget")}}" method="post" class="hidden">
      @csrf
      <div class="head">
        <img src="{{asset("images/header/Team-Gym.png")}}" alt="Team Gym Logo">
        <h1>{{__('messages.form-forget-my-password')}}</h1>
      </div>
      <div class="nebula-input">
        <input type="email" name="email" id="company-forget-email" class="input" autocomplete="email" />
        <label class="user-label" for="company-forget-email">{{__('messages.form-email')}}</label>
      </div>
      <button type="submit">{{__('messages.form-send')}}</button>
    </form>
    <form action="{{route("login")}}" method="post">
      @csrf
      <div class="head">
        <img src="{{asset("images/header/Team-Gym.png")}}" alt="Team Gym Logo">
        <h1>Company Login</h1>
      </div>
      @if ($errors->any())
        <div class="login-error" role="alert">{{ $errors->first() }}</div>
      @endif
      @if (session('company_reset_email'))
        <div class="reset-note">{{__('messages.reset-code-sent')}}</div>
      @endif
      <div class="nebula-input">
        <input type="email" class="input" name="email" id="company-login-email" value="{{ old('email') }}" autocomplete="username" />
        <label class="user-label" for="company-login-email">{{__('messages.form-email')}}</label>
      </div>
      @if (session('company_reset_email'))
        <div class="nebula-input">
          <input type="text" name="reset_code" id="company-reset-code" class="input" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" />
          <label class="user-label" for="company-reset-code">{{__('messages.form-code')}}</label>
        </div>
        <div class="nebula-input">
          <input type="password" name="new_password" id="company-new-password" class="input" minlength="8" autocomplete="new-password" />
          <label class="user-label" for="company-new-password">{{__('messages.form-new-password')}}</label>
        </div>
        <div class="nebula-input">
          <input type="password" name="new_password_confirmation" id="company-new-password-confirm" class="input" minlength="8" autocomplete="new-password" />
          <label class="user-label" for="company-new-password-confirm">{{__('messages.form-confirm-password')}}</label>
        </div>
      @else
        <div class="nebula-input">
          <input type="password" name="password" id="company-login-password" class="input" autocomplete="current-password" />
          <label class="user-label" for="company-login-password">{{__('messages.form-password')}}</label>
        </div>
      @endif
      <button type="button" class="forget">{{__('messages.form-forget-my-password')}}</button>
      <button type="submit">{{__('messages.form-login')}}</button>
    </form>
  </main>
  <script src="{{asset("js/Company/pages/loginCompany.js")}}"></script>
  <script src="{{asset("js/notification.js")}}"></script>
</body>
</html>