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
  <link rel="stylesheet" href="{{asset("css/Company/pages/homePageCompany.css")}}">
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
  <div class="blur-page"></div>
  <div class="main-blur"></div>
  <header>
    @include('Company.Dashboard.Tires.nav')
  </header>
  <div class="warnings-container">
    @if (session('success'))
      <x-components::warning :text="session('success')" />
    @endif
    @foreach ($errors->all() as $error)
      <x-components::warning :text="$error" />
    @endforeach
  </div>
  @php
    $employee = Auth::guard('employee')->user();
  @endphp
  <main>
    <x-components::main-card state="add" dataFollow="add-employee">
      <div class="header-bg">
          <img src="{{ asset('images/bg-profile-clients/bg-clients.jpg') }}" alt="No Img" loading="lazy">
          <button type="button">
            <svg width="40" height="40" viewBox="0 0 64 64" aria-hidden="true">
              <g stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
                <line x1="12" y1="12" x2="52" y2="52"/>
                <line x1="52" y1="12" x2="12" y2="52"/>
              </g>
            </svg>
          </button>
        </div>
        <div class="body-card">
          <div class="img">
            <img src="{{optional($employee)->img ? asset('images/employee/' . optional($employee)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img Logo" loading="lazy">
            <div class="content">
              <h1>{{$employee->fname}} {{$employee->lname}}</h1>
              <p>{{$employee->job_role}}</p>
            </div>
            <label for="upload-img" class="upload-img">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 48" width="40" height="19" role="img" aria-label="simple camera face">
                <rect x="6" y="8" width="52" height="32" rx="6" ry="6" fill="none" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                <rect x="10" y="4" width="10" height="6" rx="1.2" fill="#fff"/>
                <circle cx="50" cy="6" r="3" fill="#fff"/>
                <circle cx="32" cy="24" r="9" fill="none" stroke="#fff" stroke-width="2"/>
                <circle cx="32" cy="24" r="4" fill="#fff"/>
                <circle cx="36" cy="20" r="1.2" fill="#fff" opacity="0.9"/>
              </svg>
            </label>
          </div>
          <form action="{{route("addEmployee")}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" id="upload-img" name="img">
            <div class="main-input">
              <label for="full-name">{{__('messages.form-full-name')}}</label>
              <div class="row-input">
                <input type="text" id="full-name" class="fname" name="fname" placeholder="{{__('messages.form-fname')}}">
                <input type="text" class="lname" name="lname" placeholder="{{__('messages.form-lname')}}">
              </div>
            </div>
            <div class="main-input">
              <label for="job-role">{{__('messages.form-job-role')}}</label>
              <input type="text" id="job-role" class="job-role" name="job_role" placeholder="{{__('messages.form-job-role')}}">
            </div>
            <div class="main-input">
              <label for="communication">{{__('messages.form-communication')}}</label>
              <div class="row-input">
                <input type="text" id="communication" class="email" name="email" placeholder="{{__('messages.form-email')}}">
                <input type="text" class="phone" name="phone" placeholder="{{__('messages.form-phone')}}">
              </div>
            </div>
            <div class="main-input">
              <label for="password">{{__('messages.form-password')}}</label>
              <input type="text" id="password" class="password" name="password" placeholder="{{__('messages.form-password')}}">
            </div>
            <div class="main-switch">
              <div>
                <div class="item-title">{{__('messages.form-documentation')}}</div>
                <div class="item-sub">{{__('messages.form-documentation-d')}}</div>
              </div>
              <label class="switch">
                <input type="checkbox" name="documentation" checked />
                <span class="slider"></span>
              </label>
            </div>
            <div class="button-row-card">
              <div class="buttons">
                <button type="button" class="close-profile">{{__('messages.card-profile-button-close')}}</button>
                <button type="submit" class="view-profile">{{__('messages.form-send')}}</button>
              </div>
            </div>
          </form>
        </div>
      </x-components::main-card>
    @include('Company.Dashboard.Tires.sideBar')
    <section class="section-@yield('class')">
      @yield('content')
    </section>
  </main>
  <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.13.1/dist/echo.iife.js"></script>
  <script>
    const KEY = '{{ env('PUSHER_APP_KEY') }}';
    const CLUSTER = '{{env('PUSHER_APP_CLUSTER')}}';
    window.USER_ID = {{ auth()->id() ?? 'null' }};
  </script>
  <script src="{{asset("js/Company/pages/homePageCompany.js")}}"></script>
  <script src="{{asset("js/Company/public.js")}}"></script>
  <script src="{{asset("js/warning.js")}}"></script>
</body>
</html>
