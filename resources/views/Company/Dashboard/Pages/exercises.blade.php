@extends('Company.Dashboard.homePageCompany')

@section('title', "Exercises")

@section('class', "exercises")

@section('content')
  <div class="exercises-hero">
    <div class="hero-stats">
      <div class="stat-card stat-supplements">
        <div class="stat-icon">
          <svg width="36" height="36" viewBox="0 0 64 32" xmlns="http://www.w3.org/2000/svg" fill="none">
            <rect x="0"  y="8"  width="6" height="16" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="8"  y="4"  width="6" height="24" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="16" y="2"  width="6" height="28" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="24" y="14" width="16" height="4" rx="2" fill="#ffff"/>
            <rect x="40" y="2"  width="6" height="28" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="48" y="4"  width="6" height="24" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="56" y="8"  width="6" height="16" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
          </svg>
        </div>
        <div class="stat-info">
          <p>{{__('messages.nutritional_supplements')}}</p>
          <h1>{{$dataGetLineage["supplement"] ?? 0}}%</h1>
        </div>
        <div class="stat-chart">
          <canvas class="chart-line" data-points='@json($dataGetArray["supplement"])'></canvas>
        </div>
      </div>
      <div class="stat-card stat-inputs">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -14 100 100" width="50" height="50" fill="none">
            <rect x="4" y="4" width="92" height="62" rx="5" stroke="#ffff" stroke-width="2" fill="none"/>
            <polyline points="44,24 50,30 56,24" stroke="#ffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <polyline points="44,34 50,40 56,34" stroke="#ffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
          </svg>
        </div>
        <div class="stat-info">
          <p>{{__('messages.inputs')}}</p>
          <h1>{{$dataGetLineage["input"] ?? 0}}%</h1>
        </div>
        <div class="stat-chart">
          <canvas class="chart-line" data-points='@json($dataGetArray["input"])'></canvas>
        </div>
      </div>
      <div class="stat-card stat-expenses">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 80" width="50" height="50" fill="none">
            <path d="M4 8 H44 L56 20 V72 H4 Z" fill="none" stroke="#ffff" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>
            <path d="M44 8 L56 20" fill="none" stroke="#ffff" stroke-width="3" stroke-linecap="round"/>
            <polygon points="44,8 56,20 44,19" fill="none" stroke="#ffff" stroke-width="2" stroke-linejoin="round"/>
            <text x="30" y="44" text-anchor="middle" dominant-baseline="middle" font-family="Arial" font-weight="700" font-size="20" fill="#ffff">$</text>
          </svg>
        </div>
        <div class="stat-info">
          <p>{{__('messages.expenses')}}</p>
          <h1>{{$dataGetLineage["expenses"] ?? 0}}%</h1>
        </div>
        <div class="stat-chart">
          <canvas class="chart-line" data-points='@json($dataGetArray["expenses"])'></canvas>
        </div>
      </div>
      <div class="stat-card stat-revenues">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 48" width="44" height="36" fill="none" stroke="#ffff" stroke-width="2" stroke-linejoin="round" stroke-linecap="round">
            <rect x="4" y="6" width="56" height="36" rx="8" fill="none"/>
            <rect x="10" y="12" width="45" height="6" rx="3" fill="#ffff" stroke="none"/>
            <polygon points="12,24 53,24 49,32" fill="#ffff" stroke="#ffff"/>
          </svg>
        </div>
        <div class="stat-info">
          <p>{{__('messages.revenues')}}</p>
          <h1>{{$dataGetLineage["revenues"] ?? 0}}%</h1>
        </div>
        <div class="stat-chart">
          <canvas class="chart-line" data-points='@json($dataGetArray["revenues"])'></canvas>
        </div>
      </div>
    </div>
  </div>

  <x-components::main-card state="foode" dataFollow="food-card">
    <div class="body-card">
      <div class="img">
        <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-card" alt="No Img" loading="lazy">
        <div class="content">
          <h1 class="full-name-card"></h1>
          <p>client</p>
        </div>
      </div>
      <form action="{{route("addFoods")}}" method="post" class="foode-form">
        @csrf
        <div class="main-inputs main-inputs-foods">
          <div class="main-input">
            <label for="full-name-foods">{{__('messages.form-full-name')}}</label>
            <div class="row-input">
              <input type="text" id="full-name-foods" class="fname">
              <input type="text" class="lname">
            </div>
          </div>
          <div class="main-input">
            <label for="">{{__('messages.name-of-the-dish')}}</label>
            <div class="row-input">
              <input type="text" placeholder="{{__('messages.meal-name')}}" name="meal[]">
              <input type="text" placeholder="{{__('messages.how-often')}}" name="often[]">
              <input type="text" placeholder="{{__('messages.quantity')}}" name="quantity[]">
              <button type="button" class="remove">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="30" height="30" aria-label="X in circle">
                  <circle cx="32" cy="32" r="28" fill="none" />
                  <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                  <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div class="main-input input-add-row">
          <label for="">{{__('messages.name-of-the-dish')}}</label>
          <div class="row-input">
            <label for="" class="add-shape-foods">
              <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
                <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
              </svg>
              <span>{{__('messages.add-meal')}}</span>
            </label>
          </div>
        </div>
        <div class="button-row-card">
          <div class="buttons">
            <x-components::close-button follow="food-card" />
            <button type="submit" class="view-profile">{{__('messages.form-send')}}</button>
          </div>
        </div>
      </form>
    </div>
  </x-components::main-card>

  <x-components::main-card state="list" dataFollow="list-card">
    <div class="body-card">
      <div class="img img-card">
        <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-card" alt="No Img" loading="lazy">
        <div class="content">
          <h1 class="full-name-card"></h1>
          <p>client</p>
        </div>
      </div>
      <div class="main-t">
      </div>
      <div class="button-row-card">
        <div class="buttons">
          <x-components::close-button follow="list-card" />
        </div>
      </div>
    </div>
  </x-components::main-card>

  <x-components::main-card state="exercise" dataFollow="exercise-card">
    <div class="body-card">
      <div class="img">
        <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-card" alt="No Img" loading="lazy">
        <div class="content">
          <h1 class="full-name-card"></h1>
          <p>client</p>
        </div>
      </div>
      <form action="{{route("addExercises")}}" method="post" enctype="multipart/form-data" class="exercise-form">
        @csrf
        <div class="main-inputs main-inputs-exercises">
          <div class="main-input">
            <label for="full-name-exercises">{{__('messages.form-full-name')}}</label>
            <div class="row-input">
              <input type="text" id="full-name-exercises" class="fname">
              <input type="text" class="lname">
            </div>
          </div>
          <div class="main-input">
            <label for="exercise-name">{{__('messages.exercise-name')}}</label>
            <div class="row-input">
              <input type="text" id="exercise-name" class="" name="exercise_name[0][]">
              <input type="text" class="" placeholder="times" name="times[0][]">
            </div>
          </div>
          <div class="main-input">
            <label for="description">{{__('messages.description')}}</label>
            <input type="text" id="description" class="" name="description[0][]">
          </div>
          <div class="main-input">
            <label for="shape-name">{{__('messages.shape-name')}}</label>
            <div class="row-input">
              <div class="inputs">
                <input type="text" placeholder="{{__('messages.shape-name')}}" class="shape" name="shape[0][]">
                <input type="text" placeholder="{{__('messages.number-groups')}}" name="groups[0][]">
                <input type="text" placeholder="{{__('messages.number-repetitions')}}" name="repetitions[0][]">
                <button type="button" class="remove">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="30" height="30" aria-label="X in circle">
                    <circle cx="32" cy="32" r="28" fill="none" />
                    <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                    <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>
              <div class="buttons">
                <input type="file" id="video-0" name="video[0][]">
                <label for="video-0" class="label-upload">
                  <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
                  </svg>
                  <span>{{__('messages.add-video')}}</span>
                </label>
                <input type="file" id="img-0" name="img[0][]">
                <label for="img-0" class="label-upload">
                  <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
                  </svg>
                  <span>{{__('messages.add-img')}}</span>
                </label>
                <div class="check-shape">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="main-input input-add-row">
          <label for="shape-name">{{__('messages.shape-name')}}</label>
          <div class="row-input">
            <label for="" class="add-exercises label-upload">
              <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
                <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
              </svg>
              <span>{{__('messages.add-exercises')}}</span>
            </label>
            <label for="" class="add-shape-exercises label-upload">
              <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
                <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
              </svg>
              <span>{{__('messages.add-shape')}}</span>
            </label>
          </div>
        </div>
        <div class="button-row-card">
          <div class="buttons">
            <x-components::close-button follow="exercise-card" />
            <button type="submit" class="view-profile">{{__('messages.form-send')}}</button>
          </div>
        </div>
      </form>
    </div>
  </x-components::main-card>

  <div class="exercises-content">
    <div class="clients-panel">
      <div class="panel-header">
        <h1>{{__('messages.clients')}}</h1>
        <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
      </div>
      <div class="clients-grid">
        @if ($clients)
          @foreach ($clients as $item)
            <div class="client-plan-card card-client"
              data-documentation="{{$item->documentation}}"
            >
              <input type="hidden" value="{{$item->code}}" class="code_client">
              <div class="client-plan-header">
                @if ($item->img !== null)
                  <img src="{{asset("images/subscribers/$item->img")}}" alt="No Img Product" class="client-avatar">
                @else
                  <img src="{{asset("images/header/Team-Gym.png")}}" alt="No Img Product" class="client-avatar">
                @endif
                <div class="client-plan-info">
                  <h1 class="name-client">{{$item->fname}} {{$item->lname}}</h1>
                  <p>{{$item->category}}</p>
                </div>
              </div>
              <div class="plan-actions">
                <button class="plan-btn plan-btn-exercise exercise" data-follow="exercise-card">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 32" width="20" height="20" fill="none">
                    <rect x="0" y="8" width="6" height="16" rx="1" fill="currentColor"/>
                    <rect x="8" y="4" width="6" height="24" rx="1" fill="currentColor"/>
                    <rect x="16" y="2" width="6" height="28" rx="1" fill="currentColor"/>
                    <rect x="24" y="14" width="16" height="4" rx="2" fill="currentColor"/>
                    <rect x="40" y="2" width="6" height="28" rx="1" fill="currentColor"/>
                    <rect x="48" y="4" width="6" height="24" rx="1" fill="currentColor"/>
                    <rect x="56" y="8" width="6" height="16" rx="1" fill="currentColor"/>
                  </svg>
                  <span>{{__('messages.exercise-name')}}</span>
                </button>
                <button class="plan-btn plan-btn-diet foode" data-follow="food-card">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 32" width="20" height="20" fill="currentColor">
                    <rect x="7.2" y="5.0" width="1.6" height="7.6" rx="0.6"/>
                    <rect x="10.0" y="5.0" width="1.6" height="7.6" rx="0.6"/>
                    <rect x="12.8" y="5.0" width="1.6" height="7.6" rx="0.6"/>
                    <path d="M7.2 12.6 A3.6 3.6 0 0 0 14.4 12.6 L14.4 12.6 L7.2 12.6 Z"/>
                    <rect x="9.3" y="13.8" width="3.2" height="11.2" rx="1.6"/>
                  </svg>
                  <span>{{__('messages.foods')}}</span>
                </button>
                <button class="plan-btn plan-btn-view list" data-code="{{$item->code}}" data-follow="list-card">
                  <svg width="20" height="20" viewBox="0 0 64 64" fill="currentColor">
                    <circle cx="32" cy="22" r="3"/>
                    <circle cx="32" cy="32" r="3"/>
                    <circle cx="32" cy="42" r="3"/>
                  </svg>
                  <span>{{__('messages.details')}}</span>
                </button>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>

  <script>
    const destroy = '{{route("destroyExercises")}}';
    const tAddImg = @json(__('messages.add-img'));
    const tAddVideo = @json(__('messages.add-video'));
    let actionFormUpdate = '{{route("updateCoulmn")}}';
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
  <script src="{{asset("js/Company/pages/exercises.js")}}"></script>
@endsection
