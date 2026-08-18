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
  <link rel="stylesheet" href="{{asset("css/Website/web/components/footer.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/pages/store.css")}}">
  <link rel="stylesheet" href="{{asset("css/notification.css")}}">
  <title>Team Gym | Store</title>
</head>
<body>
  <div class="main-blur"></div>
  @if (Cookie::has('login_client'))
    <x-web::profile name="{{$client->fname}} {{$client->lname}}" state="{{$client->category}}" documentation="{{$client->documentation}}" img="{{$client->img}}" :lineages="$lineages" :muscles="$muscle" :fats="$fat" :water="$water"/>
  @endif
  <x-components::notifications />
  <header>
    @include('Website.web.Tires.navStore')
  </header>
  <x-components::main-card state="show" dataFollow="show-card">
    <div class="body-card">
        <div class="img img-card">
          <img src="{{optional($client)->img ? asset('images/subscribers/' . optional($client)->img) : asset('images/header/Team-Gym.png')}}" class="img-card" alt="No Img" loading="lazy">
          <div class="content">
            <h1>{{optional($client)->fname ?? 'Team'}} {{optional($client)->lname ?? 'Gym'}}</h1>
            <p>{{optional($client)->category ?? 'Gym'}}</p>
          </div>
          @if ($client && $client->documentation == "true")
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" width="20" height="20">
              <defs>
                <path id="tooth" d="M 0,-110 C 5,-106 10,-98 14,-84 L 6,-62 C 3,-56 0,-54 0,-54 C 0,-54 -3,-56 -6,-62 L -14,-84 C -10,-98 -5,-106 0,-110 Z" />
                <linearGradient id="yellow-white-yellow-45" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stop-color="#f6d93b"/>
                  <stop offset="50%" stop-color="#ffffff"/>
                  <stop offset="100%" stop-color="#f6d93b"/>
                </linearGradient>
              </defs>
              <g transform="translate(128 128)">
                <g fill="url(#yellow-white-yellow-45)" stroke-linejoin="round" transform="scale(1.02)">
                  <use href="#tooth" transform="rotate(0)"/>
                  <use href="#tooth" transform="rotate(10)"/>
                  <use href="#tooth" transform="rotate(20)"/>
                  <use href="#tooth" transform="rotate(30)"/>
                  <use href="#tooth" transform="rotate(40)"/>
                  <use href="#tooth" transform="rotate(50)"/>
                  <use href="#tooth" transform="rotate(60)"/>
                  <use href="#tooth" transform="rotate(70)"/>
                  <use href="#tooth" transform="rotate(80)"/>
                  <use href="#tooth" transform="rotate(90)"/>
                  <use href="#tooth" transform="rotate(100)"/>
                  <use href="#tooth" transform="rotate(110)"/>
                  <use href="#tooth" transform="rotate(120)"/>
                  <use href="#tooth" transform="rotate(130)"/>
                  <use href="#tooth" transform="rotate(140)"/>
                  <use href="#tooth" transform="rotate(150)"/>
                  <use href="#tooth" transform="rotate(160)"/>
                  <use href="#tooth" transform="rotate(170)"/>
                  <use href="#tooth" transform="rotate(180)"/>
                  <use href="#tooth" transform="rotate(190)"/>
                  <use href="#tooth" transform="rotate(200)"/>
                  <use href="#tooth" transform="rotate(210)"/>
                  <use href="#tooth" transform="rotate(220)"/>
                  <use href="#tooth" transform="rotate(230)"/>
                  <use href="#tooth" transform="rotate(240)"/>
                  <use href="#tooth" transform="rotate(250)"/>
                  <use href="#tooth" transform="rotate(260)"/>
                  <use href="#tooth" transform="rotate(270)"/>
                  <use href="#tooth" transform="rotate(280)"/>
                  <use href="#tooth" transform="rotate(290)"/>
                  <use href="#tooth" transform="rotate(300)"/>
                  <use href="#tooth" transform="rotate(310)"/>
                  <use href="#tooth" transform="rotate(320)"/>
                  <use href="#tooth" transform="rotate(330)"/>
                  <use href="#tooth" transform="rotate(340)"/>
                  <use href="#tooth" transform="rotate(350)"/>
                  <circle r="92" fill="url(#yellow-white-yellow-45)" stroke-width="1.4"/>
                </g>
                <path d="M -34 0 L -4 40 L 56 -20" fill="none" transform="translate(-15, -6)" stroke="#000" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
              </g>
            </svg>
          @endif
        </div>
        <form action="{{route("addRequestCustomer")}}" method="post" class="form-buy">
          @csrf
          <div class="inputs">
          </div>
          <div class="main-input">
            <label for="full-name-card">{{__('messages.form-full-name')}}</label>
            <div class="row-input">
              <input type="text" id="full-name-card" class="fname-card" name="fname">
              <input type="text" class="lname-card" name="lname">
            </div>
          </div>
          <div class="main-input">
            <label for="phone">{{__('messages.form-phone')}}</label>
            <input type="text" id="phone-card" class="phone-card" name="phone">
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <button type="button" class="close-profile close-card" data-follow="show-card">{{__('messages.card-profile-button-close')}}</button>
              <button type="submit" class="view-profile">{{__('messages.form-send')}}</button>
            </div>
          </div>
        </form>
      </div>
    </x-components::main-card>
  <article>
    <div class="product-choose">
    </div>
    <div class="buttons">
      <button class="remove">Remove All</button>
      <button class="buy show" data-follow="show-card">Request products</button>
    </div>
  </article>
  @include('Website.web.Sections.store.section_1')

  @include('Website.web.Sections.store.section_2')

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
  <script src="{{asset("js/Website/web/pages/store.js")}}"></script>
  <script src="{{asset("js/Website/web/public.js")}}"></script>
  <script src="{{asset("js/Website/Dashboard/pages/profileCard.js")}}"></script>
  <script src="{{asset("js/notification.js")}}"></script>
</body>
</html>
