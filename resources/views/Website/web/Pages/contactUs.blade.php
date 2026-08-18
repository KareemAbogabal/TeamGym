<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="google" content="notranslate">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="icon" href="{{asset("images/header/Team-Gym.png")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/public.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/components/header.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/pages/contactUs.css")}}">
  <link rel="stylesheet" href="{{asset("css/notification.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/components/footer.css")}}">
  <title>Team Gym | Contact Us</title>
</head>
<body>
  <div class="main-blur"></div>
  @if (Cookie::has('login_client'))
    <x-web::profile name="{{$client->fname}} {{$client->lname}}" state="{{$client->category}}" documentation="{{$client->documentation}}" img="{{$client->img}}" :lineages="$lineages" :muscles="$muscle" :fats="$fat" :water="$water"/>
  @endif
  <x-components::notifications />
  <header>
    @include('Website.web.Tires.navContactUs')
  </header>
  @include('Website.web.Sections.contactUs.section_1')
  @include('Website.web.Sections.footer')
  <script>
    let lableCanava = "{{__('messages.card-profile-lable')}}";
    let cardProfileMonths = [
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{asset("js/Website/web/pages/contactUs.js")}}"></script>
  <script src="{{asset("js/Website/Dashboard/pages/profileCard.js")}}"></script>
  <script src="{{asset("js/Website/web/public.js")}}"></script>
  <script src="{{asset("js/notification.js")}}"></script>
</body>
</html>
