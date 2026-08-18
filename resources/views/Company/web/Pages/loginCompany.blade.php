<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="google" content="notranslate">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{asset("images/header/Team-Gym.png")}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="{{asset("css/Company/pages/loginCompany.css")}}">
  <link rel="stylesheet" href="{{asset("css/notification.css")}}">
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <title>Team Gym | Login Company</title>
</head>
<body>
  <x-components::notifications />
  <main>
    <div class="img">
      <img src="{{asset("images/content/login-company.png")}}" alt="No Img Login">
    </div>
    <form action="{{route("company.forget")}}" method="post"  class="hidden">
      @csrf
      <div class="head">
        <img src="{{asset("images/header/Team-Gym.png")}}" alt="No Logo">
        <h1>Hello Welcome Back</h1>
      </div>
      <div class="nebula-input">
        <input type="text" name="email" class="input" />
        <label class="user-label">{{__('messages.form-email')}}</label>
      </div>
      <button type="submit">{{__('messages.form-send')}}</button>
    </form>
    <form action="{{route("login")}}" method="post">
      @csrf
      <div class="head">
        <img src="{{asset("images/header/Team-Gym.png")}}" alt="No Logo">
        <h1>Hello Welcome Back</h1>
      </div>
      <div class="nebula-input">
        <input type="text" class="input" name="fname" />
        <label class="user-label">{{__('messages.form-fname')}}</label>
      </div>
      <div class="nebula-input">
        <input type="text" class="input" name="email" />
        <label class="user-label">{{__('messages.form-email')}}</label>
      </div>
      <div class="nebula-input">
        <input type="password" class="input" name="password" />
        <label class="user-label">{{__('messages.form-password')}}</label>
      </div>
      @if (Cookie::get('temporary_company'))
        <div class="nebula-input">
          <input type="text" name="new_password" class="input" />
          <label class="user-label">new password</label>
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
