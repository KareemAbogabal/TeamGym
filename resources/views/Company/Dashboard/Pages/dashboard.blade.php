@extends('Company.Dashboard.homePageCompany')

@section('title', "Dashboard")

@section('class', "dashboard")

@section('content')
  <div class="main-header-lineage">
    <div class="main-lineage">
      <div @if ($systems["state"] == 1) class="lineage" @else class="lineage descending" @endif >
        <div class="header">
          <h1>{{ __('messages.subscriptions') }}</h1>
          <a href="#">
            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="var(--colorSVGAnalytcis)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 8h20l12 12v32a4 4 0 0 1-4 4H16a4 4 0 0 1-4-4V12a4 4 0 0 1 4-4z"/>
              <path d="M36 8v12h12"/>
              <line x1="20" y1="28" x2="32" y2="28"/>
              <line x1="20" y1="36" x2="32" y2="36"/>
              <g transform="translate(30,44) rotate(-45) scale(1.2)" stroke="var(--colorSVGAnalytcis)" fill="none">
                <rect x="0" y="0" width="25" height="4" rx="1"/>
                <rect x="22" y="0" width="3" height="4" rx="1"/>
                <polygon points="0,0 0,4 -4,2"/>
                <polygon points="-4,2 -6,2 0,2"/>
              </g>
            </svg>
          </a>
        </div>
        <main>
          <div class="content">
            <h1>{{$systems["total"]}} {{ __('messages.EGP') }}</h1>
            <p>{!! __('messages.you_won_month', ['lineage' => '<span class="average">'.$systems["lineage"].'%</span>']) !!}</p>
          </div>
          <div class="stock-index">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" viewBox="0 0 67 44" fill="none">
              <defs>
                <marker id="arrowhead-1" markerWidth="6" markerHeight="6" refX="4.8" refY="3" orient="auto">
                  <path d="M0,0 L6,3 L0,6 Z" fill="var(--colorSVGAnalytcis)"/>
                </marker>
              </defs>
              <path d="M6 34 L24 20 L32 26 L48 10" stroke="var(--colorSVGAnalytcis)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" marker-end="url(#arrowhead-1)"/>
            </svg>
            <p>{{$systems["lineage"]}} %</p>
          </div>
        </main>
      </div>
      <div @if ($supplements["state"] == 1) class="lineage" @else class="lineage descending" @endif >
        <div class="header">
          <h1>{{ __('messages.nutritional_supplements') }}</h1>
          <a href="#">
            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="var(--colorSVGAnalytcis)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 8h20l12 12v32a4 4 0 0 1-4 4H16a4 4 0 0 1-4-4V12a4 4 0 0 1 4-4z"/>
              <path d="M36 8v12h12"/>
              <line x1="20" y1="28" x2="32" y2="28"/>
              <line x1="20" y1="36" x2="32" y2="36"/>
              <g transform="translate(30,44) rotate(-45) scale(1.2)" stroke="var(--colorSVGAnalytcis)" fill="none">
                <rect x="0" y="0" width="25" height="4" rx="1"/>
                <rect x="22" y="0" width="3" height="4" rx="1"/>
                <polygon points="0,0 0,4 -4,2"/>
                <polygon points="-4,2 -6,2 0,2"/>
              </g>
            </svg>
          </a>
        </div>
        <main>
          <div class="content">
            <h1>{{$supplements["total"]}} {{ __('messages.EGP') }}</h1>
            <p>{!! __('messages.you_won_month', ['lineage' => '<span class="average">'.$supplements["lineage"].'%</span>']) !!}</p>
          </div>
          <div class="stock-index">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" viewBox="0 0 67 44" fill="none">
              <defs>
                <marker id="arrowhead-2" markerWidth="6" markerHeight="6" refX="4.8" refY="3" orient="auto">
                  <path d="M0,0 L6,3 L0,6 Z" fill="var(--colorSVGAnalytcis)"/>
                </marker>
              </defs>
              <path d="M6 34 L24 20 L32 26 L48 10" stroke="var(--colorSVGAnalytcis)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" marker-end="url(#arrowhead-2)"/>
            </svg>
            <p>{{$supplements["lineage"]}} %</p>
          </div>
        </main>
      </div>
      <div @if ($imports["state"] == 1) class="lineage" @else class="lineage descending" @endif >
        <div class="header">
          <h1>{{ __('messages.inputs') }}</h1>
          <a href="#">
            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="var(--colorSVGAnalytcis)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 8h20l12 12v32a4 4 0 0 1-4 4H16a4 4 0 0 1-4-4V12a4 4 0 0 1 4-4z"/>
              <path d="M36 8v12h12"/>
              <line x1="20" y1="28" x2="32" y2="28"/>
              <line x1="20" y1="36" x2="32" y2="36"/>
              <g transform="translate(30,44) rotate(-45) scale(1.2)" stroke="var(--colorSVGAnalytcis)" fill="none">
                <rect x="0" y="0" width="25" height="4" rx="1"/>
                <rect x="22" y="0" width="3" height="4" rx="1"/>
                <polygon points="0,0 0,4 -4,2"/>
                <polygon points="-4,2 -6,2 0,2"/>
              </g>
            </svg>
          </a>
        </div>
        <main>
          <div class="content">
            <h1>{{$imports["total"]}} {{ __('messages.EGP') }}</h1>
            <p>{!! __('messages.you_won_month', ['lineage' => '<span class="average">'.$imports["lineage"].'%</span>']) !!}</p>
          </div>
          <div class="stock-index">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" viewBox="0 0 67 44" fill="none"  style="color: var(--colorSVGAnalytcis);">
              <defs>
                <marker id="arrowhead-3" markerWidth="6" markerHeight="6" refX="4.8" refY="3" orient="auto">
                  <path d="M0,0 L6,3 L0,6 Z"  fill="currentColor"/>
                </marker>
              </defs>
              <path d="M6 34 L24 20 L32 26 L48 10" stroke="var(--colorSVGAnalytcis)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" marker-end="url(#arrowhead-3)"/>
            </svg>
            <p>{{$imports["lineage"]}} %</p>
          </div>
        </main>
      </div>
    </div>
    <div class="main-table">
      <div class="chart">
        <div class="header">
          <h4>{{ __('messages.analytics') }}</h4>
          <h1>{{$revenuesAmount["total"]}} {{ __('messages.EGP') }}</h1>
          @if ($revenuesAmount["state"] == 1)
            <p>{!! __('messages.you_won_year_excellent', ['lineage' => '<span class="excellent">'.$revenuesAmount["lineage"].'%</span>']) !!}</p>
          @else
            <p>{!! __('messages.you_won_year_descending', ['lineage' => '<span class="descending">'.$revenuesAmount["lineage"].'%</span>']) !!}</p>
          @endif
        </div>
        <div class="charts">
          <div class="main-char">
            <div class="char">
              <canvas id="chart-1" data-revenues='@json($revenuesArr)' data-expenses='@json($expensesArr)'></canvas>
            </div>
          </div>
          <div class="main-char">
            <div class="char">
              <canvas id="chart-2" data-percentage="[{{$revenues["lineage"]}}, {{$expenses["lineage"]}}, {{$supplement["lineage"]}}, {{$system["lineage"]}}]"></canvas>
            </div>
          </div>
        </div>
      </div>
      @if ($settingCompany->view_logs_logins == true || $user->job_role == "admin")
        <div class="logins">
          <div class="header">
            <div class="content">
              <h4>{{ __('messages.records') }}</h4>
              <h1>{{$recordsCount}} {{ __('messages.records') }}</h1>
            </div>
            <button>{{ __('messages.see_all') }}</button>
          </div>
          <main>
            <x-components::table :header="[__('messages.name'), __('messages.state')]">
              @if ($records)
                @foreach ($records as $item)
                  <div class="row">
                    <p class="search">{{$item->name_client}}</p>
                    <p data-state="{{$item->state}}">{{$item->state}}</p>
                  </div>
                @endforeach
              @endif
            </x-components::table>
          </main>
        </div>
      @endif
    </div>
  </div>
  @if ($settingCompany->view_logs_logins == true || $user->job_role == "admin")
    <div class="history">
      <div class="content">
        <h1>{{ __('messages.history') }}</h1>
        <input type="text" class="search-payments" placeholder="{{ __('messages.search') }}">
      </div>
      <main>
        <x-components::table :header="[__('messages.name'), __('messages.id'), __('messages.state'), __('messages.attachment'), __('messages.amount'), __('messages.date')]">
          @if ($history)
              @foreach ($history as $item)
                <div class="row">
                  <p class="search">
                    <img src="{{ optional($item->client)->img
                        ? asset('images/subscribers/' . optional($item->client)->img)
                        : (optional($item->employee)->img
                            ? asset('images/employee/' . optional($item->employee)->img)
                            : asset('images/header/Team-Gym.png'))
                    }}" alt="{{ __('messages.no_img') }}" loading="lazy">{{$item->name}}</p>
                  <p>{{$item->code}}</p>
                  <p data-state="{{$item->state}}">{{ __('messages.' . $item->state) }}</p>
                  @if ($item->attachment)
                    <p>{{$item->attachment}}</p>
                  @else
                    <p>--</p>
                  @endif
                  @if ($item->amount)
                    <p>{{$item->amount}}</p>
                  @else
                    <p>--</p>
                  @endif
                  <p>{{$item->created_at}}</p>
                </div>
              @endforeach
            @endif
        </x-components::table>
      </main>
    </div>
  @endif
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{asset("js/Company/pages/dashboard.js")}}"></script>
@endsection
