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
  <link rel="stylesheet" href="{{asset("css/Website/web/pages/privacyPolicy.css")}}">
  <link rel="stylesheet" href="{{asset("css/notification.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/components/footer.css")}}">
  <title>Team Gym | Privacy Policy</title>
</head>
<body>
  <div class="main-blur"></div>
  @if (Cookie::has('login_client'))
    <x-web::profile name="{{$client->fname}} {{$client->lname}}" state="{{$client->category}}" documentation="{{$client->documentation}}" img="{{$client->img}}" :lineages="$lineages" :muscles="$muscle" :fats="$fat" :water="$water" :client="$client"/>
  @endif
  <x-components::notifications />
  <header>
    @include('Website.web.Tires.navPrivacyPolicy')
  </header>
  <div class="privacy-page" role="main">
    <div class="group">
      <h2 class="section-title">{{ __('messages.information_we_collect') }}</h2>
      <div class="cards-grid">
        <article class="card" aria-labelledby="c1">
          <div class="card-icon accent--gold" aria-hidden="true">
            <svg width="34" height="34" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="cookie">
              <circle cx="12" cy="12" r="9" fill="var(--colorSVG2)" />
              <circle cx="9.5" cy="9.0" r="1.15" fill="var(--colorSVG1)" />
              <circle cx="6.8" cy="13.2" r="1.05" fill="var(--colorSVG1)" />
              <circle cx="13.2" cy="13.0" r="1.2"  fill="var(--colorSVG1)" />
            </svg>
          </div>
          <div class="card-content">
            <div id="c1" class="card-title">{{ __('messages.c1_title') }}</div>
            <div class="card-text">{{ __('messages.c1_text') }}</div>
          </div>
        </article>
        <article class="card" aria-labelledby="c2">
          <div class="card-icon accent--green" aria-hidden="true">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
              <path d="M12 2l7 4v5c0 5-3.6 9-7 11-3.4-2-7-6-7-11V6l7-4z" fill="var(--colorSVG2)"/>
              <path d="M12 8v6l4 2" stroke="var(--colorSVG1)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="card-content">
            <div id="c2" class="card-title">{{ __('messages.c2_title') }}</div>
            <div class="card-text">{{ __('messages.c2_text') }}</div>
          </div>
        </article>
        <article class="card" aria-labelledby="c3">
          <div class="card-icon accent--blue" aria-hidden="true">
            <svg width="40" height="40" viewBox="0 8 64 64" fill="none">
              <g fill="var(--colorSVG1)">
                <path d="M32 20c1.1 0 2 .9 2 2v2a15 15 0 0 1 4.2 1.6l1.4-1.4a2 2 0 0 1 2.8 0l2.5 2.5a2 2 0 0 1 0 2.8l-1.4 1.4a15 15 0 0 1 1.6 4.2h2a2 2 0 0 1 2 2v3.5a2 2 0 0 1-2 2h-2a15 15 0 0 1-1.6 4.2l1.4 1.4a2 2 0 0 1 0 2.8l-2.5 2.5a2 2 0 0 1-2.8 0l-1.4-1.4a15 15 0 0 1-4.2 1.6v2a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-2a15 15 0 0 1-4.2-1.6l-1.4 1.4a2 2 0 0 1-2.8 0l-2.5-2.5a2 2 0 0 1 0-2.8l1.4-1.4a15 15 0 0 1-1.6-4.2h-2a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2h2a15 15 0 0 1 1.6-4.2l-1.4-1.4a2 2 0 0 1 0-2.8l2.5-2.5a2 2 0 0 1 2.8 0l1.4 1.4a15 15 0 0 1 4.2-1.6v-2c0-1.1.9-2 2-2h3.5z"/>
              </g>
              <circle cx="30.5" cy="38.5" r="6" fill="var(--colorSVG2)"/>
            </svg>
          </div>
          <div class="card-content">
            <div id="c3" class="card-title">{{ __('messages.c3_title') }}</div>
            <div class="card-text">{{ __('messages.c3_text') }}</div>
          </div>
        </article>
        <article class="card" aria-labelledby="c4">
          <div class="card-icon accent--orange" aria-hidden="true">
            <svg fill="var(--colorSVG2)" width="40" height="40" viewBox="0 0 24 24" class="svg-icon">
              <g clip-rule="evenodd" fill-rule="evenodd" stroke="none" stroke-linecap="round" stroke-width="2">
                <path d="m3 7h17c.5523 0 1 .44772 1 1v11c0 .5523-.4477 1-1 1h-16c-.55228 0-1-.4477-1-1z"></path>
                <path d="m3 4.5c0-.27614.22386-.5.5-.5h6.29289c.13261 0 .25981.05268.35351.14645l2.8536 2.85355h-10z"></path>
              </g>
            </svg>
          </div>
          <div class="card-content">
            <div id="c4" class="card-title">{{ __('messages.c4_title') }}</div>
            <div class="card-text">{{ __('messages.c4_text') }}</div>
          </div>
        </article>
      </div>
    </div>
    <div class="group">
      <div class="cards-grid">
        <article class="card" aria-labelledby="c5">
          <div class="card-icon accent--purple" aria-hidden="true">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="3.2" fill="var(--colorSVG2)"/>
              <path d="M5.5 20c.8-4 4.2-6 6.5-6s5.7 2 6.5 6" stroke="var(--colorSVG2)" stroke-width="1.1" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="card-content">
            <div id="c5" class="card-title">{{ __('messages.c5_title') }}</div>
            <div class="card-text">{{ __('messages.c5_text') }}</div>
          </div>
        </article>
        <article class="card" aria-labelledby="c6">
          <div class="card-icon accent--teal" aria-hidden="true">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
              <path d="M12 2c3.9 0 7 3.1 7 7 0 5-7 13-7 13S5 14 5 9c0-3.9 3.1-7 7-7z" fill="var(--colorSVG2)"/>
              <circle cx="12" cy="9" r="2.2" fill="var(--colorSVG1)"/>
            </svg>
          </div>
          <div class="card-content">
            <div id="c6" class="card-title">{{ __('messages.c6_title') }}</div>
            <div class="card-text">{{ __('messages.c6_text') }}</div>
          </div>
        </article>
      </div>
    </div>
    <div class="group">
      <h2 class="section-title">{{ __('messages.privacy_policy_of_global') }}</h2>
      <div class="cards-grid">
        <article class="card" aria-labelledby="g1">
          <div class="card-icon accent--gold" aria-hidden="true">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
              <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z" fill="var(--colorSVG2)"/>
              <path d="M2 12h20M12 2c2.8 3.8 2.8 10 0 14M12 22c-2.8-3.8-2.8-10 0-14" stroke="var(--colorSVG1)" stroke-width="1" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="card-content">
            <div id="g1" class="card-title">{{ __('messages.g1_title') }}</div>
            <div class="card-text">{{ __('messages.g1_text') }}</div>
          </div>
        </article>
        <article class="card" aria-labelledby="g2">
          <div class="card-icon accent--green" aria-hidden="true">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
              <rect x="3" y="10" width="4" height="9" rx="1" fill="var(--colorSVG2)" />
              <rect x="9" y="6" width="4" height="13" rx="1" fill="var(--colorSVG2)" />
              <rect x="15" y="2" width="4" height="17" rx="1" fill="var(--colorSVG2)" />
            </svg>
          </div>
          <div class="card-content">
            <div id="g2" class="card-title">{{ __('messages.g2_title') }}</div>
            <div class="card-text">{{ __('messages.g2_text') }}</div>
          </div>
        </article>
        <article class="card" aria-labelledby="g3">
          <div class="card-icon accent--blue" aria-hidden="true">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
              <rect x="5" y="11" width="14" height="9" rx="2" fill="var(--colorSVG2)" />
              <path d="M8 11V8a4 4 0 0 1 8 0v3" stroke="var(--colorSVG1)" stroke-width="1.1" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="card-content">
            <div id="g3" class="card-title">{{ __('messages.g3_title') }}</div>
            <div class="card-text">{{ __('messages.g3_text') }}</div>
          </div>
        </article>
      </div>
    </div>
    <div class="cta" role="region" aria-label="Contact CTA">
      <div class="content">
        <div class="cta-title">{{ __('messages.cta_title') }}</div>
        <div class="cta-desc">{{ __('messages.cta_desc') }}</div>
      </div>
      <div>
        <a href="{{ route('contactUs') }}" class="cta-btn">{{ __('messages.cta_btn') }}</a>
      </div>
    </div>
  </div>
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
  <script src="{{asset("js/Website/web/pages/privacyPolicy.js")}}"></script>
  <script src="{{asset("js/Website/Dashboard/pages/profileCard.js")}}"></script>
  <script src="{{asset("js/notification.js")}}"></script>
</body>
</html>
