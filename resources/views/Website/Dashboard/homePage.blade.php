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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@200..800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{asset("css/Website/Dashboard/pages/homePage.css")}}">
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script>
    let lableCanava = "{{__('messages.card-profile-lable')}}";
    let months = [
      "{{ __('messages.card-profile-Jan') }}",
      "{{ __('messages.card-profile-Feb') }}",
      "{{ __('messages.card-profile-Mar') }}",
      "{{ __('messages.card-profile-Apr') }}",
      "{{ __('messages.card-profile-May') }}",
      "{{ __('messages.card-profile-Jun') }}",
      "{{ __('messages.card-profile-Jul') }}",
      "{{ __('messages.card-profile-Aug') }}",
      "{{ __('messages.card-profile-Sep') }}",
      "{{ __('messages.card-profile-Oct') }}",
      "{{ __('messages.card-profile-Nov') }}",
      "{{ __('messages.card-profile-Dec') }}",
    ];
  </script>
  <title>Team Gym | @yield('title')</title>
</head>
<body>
  <div class="main-blur"></div>
  <header>
    @include('Website.Dashboard.Tires.nav')
  </header>
  <div class="warnings-container">
    @foreach ($errors->all() as $error)
      <x-components::warning :text="$error" />
    @endforeach
  </div>
  <main>
    @include('Website.Dashboard.Tires.sideBar')
    <section class="section-@yield('class')">
      @yield('content')
    </section>
  </main>
  <script src="{{asset("js/Website/Dashboard/homePage.js")}}"></script>
  <script src="{{asset("js/warning.js")}}"></script>
</body>
</html>