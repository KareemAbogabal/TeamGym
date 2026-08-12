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
  <link rel="stylesheet" href="{{asset("css/Website/web/public.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/components/header.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/components/footer.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/pages/article.css")}}">
  <title>Team Gym | About Us</title>
</head>
<body>
  <div class="main-blur"></div>
  @if (Cookie::has('login_client'))
    <x-web::profile name="{{$client->fname}} {{$client->lname}}" state="{{$client->category}}" documentation="{{$client->documentation}}" img="{{$client->img}}" :lineages="$lineages" :muscles="$muscle" :fats="$fat" :water="$water"/>
  @endif
  <div class="warnings-container">
    @foreach ($errors->all() as $error)
      <x-components::warning :text="$error" />
    @endforeach
  </div>
  <header>
    @include('Website.web.Tires.navArticle')
  </header>
  <x-components::main-card state="show" dataFollow="show-card">
    <div class="body-card">
      <div class="img img-card">
        <img src="{{optional($client)->img ? asset('images/subscribers/' . optional($client)->img) : asset('images/header/Team-Gym.png')}}" class="img-card" alt="No Img" loading="lazy">
        <div class="content">
          <h1>{{optional($client)->fname ?? 'Team'}} {{optional($client)->lname ?? 'Gym'}}</h1>
          <p>{{optional($client)->category ?? 'Gym'}}</p>
        </div>
      </div>
      <form action="{{route("addRequestCustomer")}}" method="post">
        @csrf
        <input type="hidden" class="type" name="type[]">
        <input type="hidden" class="code_order" name="code[]">
        <input type="hidden" class="order_name" name="order_name[]">
        <input type="hidden" class="amount" name="amount[]">
        <input type="hidden" class="quantity" name="quantity[]">
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
  @include('Website.web.Sections.article.section_1')

  @include('Website.web.Sections.article.section_2')

  @include('Website.web.Sections.article.section_3')

  @include('Website.web.Sections.article.section_4')

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
  <script src="{{asset("js/Website/Dashboard/pages/profileCard.js")}}"></script>
  <script src="{{asset("js/Website/web/pages/article.js")}}"></script>
  <script src="{{asset("js/Website/web/public.js")}}"></script>
  <script src="{{asset("js/warning.js")}}"></script>
</body>
</html>
