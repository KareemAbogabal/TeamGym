@extends('Website.Dashboard.homePage')

@section('title', "Burn Meter")

@section('class', "burn-meter")

@section('content')
  <div class="running-status">
    <div class="main-meters">
      <div class="head">
        <h1>{{__('messages.meter')}}</h1>
      </div>
      <div class="meter">
        <div class="meter-speed">
          <div class="index"></div>
          <span></span>
        </div>
        {{-- <div class="meter-time" role="main" aria-labelledby="title">
          <div class="gauge-wrap" aria-hidden="false">
            <svg id="gauge" width="100%" height="100%" viewBox="0 0 340 340">
              <defs>
                <linearGradient id="gGrad" x1="0" x2="1">
                  <stop offset="0" stop-color="#6C63FF" stop-opacity="1" />
                  <stop offset="1" stop-color="#00D4FF" stop-opacity="1" />
                </linearGradient>
                <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
                  <feGaussianBlur stdDeviation="6" result="b"/>
                  <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                </filter>
              </defs>
              <g transform="translate(170,170)">
                <circle r="140" fill="none" stroke="rgba(255,255,255,0.02)" stroke-width="12" />
                <circle id="arc-fg"
                  r="140" cx="0" cy="0"
                  fill="none"
                  stroke="url(#gGrad)"
                  stroke-width="18"
                  stroke-linecap="round"
                  style="filter:url(#glow); transform-origin: 0px 0px; transform: rotate(-90deg);"
                  pathLength="100"
                  stroke-dasharray="100 0"
                  stroke-dashoffset="100"
                />
              </g>
            </svg>
            <div class="center-display" aria-live="polite">
              <p id="timeText" class="time" contenteditable="true">00:00.00</p>
            </div>
          </div>
        </div> --}}
      </div>
      <div class="main-ratios">
        <div class="ratios main-btn-start">
          <i class="fa-solid fa-shoe-prints"></i>
          <div class="content">
            <p>{{__('messages.steps')}}</p>
            <p><span class="step">0</span> {{__('messages.step')}}</p>
          </div>
        </div>
        <div class="ratios">
          <i class="fa-solid fa-fire"></i>
          <div class="content">
            <p>{{__('messages.kcals')}}</p>
            <p><span class="kcal">0</span> {{__('messages.kcal')}}</p>
          </div>
        </div>
        <div class="ratios">
          <i class="fa-solid fa-person"></i>
          <div class="content">
            <p>{{__('messages.fats')}}</p>
            <p><span class="fat">0</span> g</p>
          </div>
        </div>
      </div>
    </div>
    <div class="wither broken-clouds">
      <div class="head">
        <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
          <path d="M12 2c3.9 0 7 3.1 7 7 0 5-7 13-7 13S5 14 5 9c0-3.9 3.1-7 7-7z" fill="#3d3d3d"/>
          <circle cx="12" cy="9" r="2.2" fill="#808080"/>
        </svg>
        <p class="location">Damietta</p>
      </div>
      <div class="main-content">
        <div class="date">
          <h1 class="day">sunday</h1>
          <span class="date">18 sep, 2025</span>
        </div>
        <img src="{{asset("images/content/02d2x.png")}}" class="icon" alt="">
        <div class="state">
          <div class="deg">
            <h1><span class="deg">28</span> °C</h1>
            <p class="name-state">rainy</p>
          </div>
          <div class="winds">
            <p><i class="fa-solid fa-wind"></i> <span class="speed">1</span> KM / h</p>
            <p><i class="fa-solid fa-droplet"></i> <span class="humidity">31</span> %</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="programs">
    <div class="program" style="--colorprogram: rgba(226, 173, 52, 0.72); --bgprogram: rgba(255, 232, 130, 0.70);">
      <i class="fa-solid fa-shoe-prints"></i>
      <div class="content">
        <div class="text">
          <h1>{{__('messages.runing')}}</h1>
          <p>20 {{__('messages.minutes')}}</p>
        </div>
        <button class="btn-start-program">{{__('messages.start')}}</button>
      </div>
    </div>
    <div class="program" style="--colorprogram: rgba(208, 190, 58, 0.72); --bgprogram: rgba(246, 241, 150, 0.70);">
      <i class="fa-solid fa-person-walking"></i>
      <div class="content">
        <div class="text">
          <h1>{{__('messages.treadmill')}}</h1>
          <p>20 {{__('messages.minutes')}}</p>
        </div>
        <button class="btn-start-program">{{__('messages.start')}}</button>
      </div>
    </div>
    <div class="program" style="--colorprogram: #9466ffb7; --bgprogram: #bfa3ffb7;">
      <i class="fa-regular fa-map"></i>
      <div class="content">
        <div class="text">
          <h1>{{__('messages.running-distance')}}</h1>
          <p>20 {{__('messages.minutes')}}</p>
        </div>
        <button class="btn-start-program">{{__('messages.start')}}</button>
      </div>
    </div>
  </div>
  <div class="main-table-meter">
    <div class="content">
      <h1>{{__('messages.history')}}</h1>
      <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
    </div>
    <div class="main">
      <x-components::table :header="[__('messages.name'), __('messages.minutes'), __('messages.distance'), __('messages.date'), __('messages.details')]">
        @if ($cardios)
          @foreach ($cardios as $item)
            <div class="row">
              <div class="content">
                <p class="search">{{$item->name}}</p>
                <p>{{$item->minutes}} minutes</p>
                <p>{{$item->distance ?? 0}} KG / h</p>
                <p>{{$item->created_at}}</p>
                <p>
                  <button class="btn-details">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 48 48">
                      <circle cx="24" cy="24" r="22" stroke="var(--colorSVG2)" stroke-width="2.5" fill="none"/>
                      <path d="M16 20 L24 30 L32 20" fill="none" stroke="var(--colorSVG2)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                </p>
              </div>
              <div class="details">
                @if ($item->end_latitude)
                  <iframe
                    width="400"
                    height="300"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?saddr={{$item->start_latitude}},{{$item->start_longitude}}&daddr={{$item->end_latitude}},{{$item->end_longitude}}&output=embed">
                  </iframe>
                @else
                  <iframe
                    width="400"
                    height="300"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q={{$item->start_latitude}},{{$item->start_longitude}}&z=19&output=embed">
                  </iframe>
                @endif
              </div>
            </div>
          @endforeach
        @endif
      </x-components::table>
    </div>
  </div>
  <script>
    let code_client = @json($code_client);
    const translations = {
      weather: {
        clear_sky: "{{ __('messages.clear_sky') }}",
        few_clouds: "{{ __('messages.few_clouds') }}",
        scattered_clouds: "{{ __('messages.scattered_clouds') }}",
        broken_clouds: "{{ __('messages.broken_clouds') }}",
        shower_rain: "{{ __('messages.shower_rain') }}",
        rain: "{{ __('messages.rain') }}",
        thunderstorm: "{{ __('messages.thunderstorm') }}",
        snow: "{{ __('messages.snow') }}",
        mist: "{{ __('messages.mist') }}",
      }
    };
  </script>
  <script src="{{asset("js/Website/Dashboard/pages/burnMeter.js")}}"></script>
@endsection
