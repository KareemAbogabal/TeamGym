@extends('Company.Dashboard.homePageCompany')

@section('title', "Analytics")

@section('class', "analytics")

@section('content')
  <div class="main-charts">
    <div class="charts">
      <div class="main-char">
        <div class="header">
          <svg width="16" height="20" viewBox="0 0 48 32" xmlns="http://www.w3.org/2000/svg" fill="none" preserveAspectRatio="xMidYMid meet" style="--colorSVG: rgba(92, 166, 250, 0.692);">
            <rect x="2" y="2" width="40" height="28" rx="4" fill="#ffff" stroke="#ffff" stroke-width="2"/>
            <rect x="4" y="6" width="35" height="4" fill="#242424"/>
            <rect x="6" y="14" width="6" height="6" rx="1" fill="#242424"/>
            <rect x="16" y="16" width="4" height="2" fill="#242424" />
            <rect x="22" y="16" width="4" height="2" fill="#242424" />
            <rect x="28" y="16" width="4" height="2" fill="#242424" />
            <rect x="34" y="16" width="4" height="2" fill="#242424" />
          </svg>
          <h1>{{__('messages.subscriptions')}}</h1>
        </div>
        <div class="char">
          <p>{{$dataGetLineage["system"] ?? 0}}%</p>
          <canvas class="chart-line" data-points='@json($dataGetArray["system"])'></canvas>
        </div>
      </div>
      <div class="main-char">
        <div class="header">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 40" width="64" height="40" aria-label="money" style="--colorSVG: rgba(237, 92, 250, 0.692);">
            <rect x="6" y="8" width="52" height="24" rx="4" ry="4" fill="none" stroke="#ffff" stroke-width="2"/>
            <text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" font-family="Arial, Helvetica, sans-serif" font-weight="700" font-size="14" fill="#ffff">$</text>
          </svg>
          <h1>{{__('messages.transactions')}}</h1>
        </div>
        <div class="char">
          <p>{{$dataGetLineage["revenues"] ?? 0}}%</p>
          <canvas class="chart-line" data-points='@json($dataGetArray["revenues"])'></canvas>
        </div>
      </div>
      <div class="main-char">
        <div class="header">
          <svg width="56" height="60" viewBox="0 0 64 32" xmlns="http://www.w3.org/2000/svg" fill="none" style="--colorSVG: rgba(239, 250, 92, 0.692);">
            <rect x="0"  y="8"  width="6" height="16" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="8"  y="4"  width="6" height="24" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="16" y="2"  width="6" height="28" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="24" y="14" width="16" height="4" rx="2" fill="#ffff"/>
            <rect x="40" y="2"  width="6" height="28" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="48" y="4"  width="6" height="24" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
            <rect x="56" y="8"  width="6" height="16" rx="1" fill="#ffff" stroke="#ffff" stroke-width="1"/>
          </svg>
          <h1>{{__('messages.nutritional_supplements')}}</h1>
        </div>
        <div class="char">
          <p>{{$dataGetLineage["supplement"] ?? 0}}%</p>
          <canvas class="chart-line" data-points='@json($dataGetArray["supplement"])'></canvas>
        </div>
      </div>
      <div class="main-char">
        <div class="header">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -14 100 100" width="100" height="100" fill="none" role="img" aria-label="vertical rectangle with centered chevrons" style="--colorSVG: rgba(116, 116, 116, 0.692);">
            <rect x="4" y="4" width="92" height="62" rx="5" stroke="#ffff" stroke-width="2" fill="none"/>
            <polyline points="44,24 50,30 56,24" stroke="#ffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <polyline points="44,34 50,40 56,34" stroke="#ffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
          </svg>
          <h1>{{__('messages.inputs')}}</h1>
        </div>
        <div class="char">
          <p>{{$dataGetLineage["input"] ?? 0}}%</p>
          <canvas class="chart-line" data-points='@json($dataGetArray["input"])'></canvas>
        </div>
      </div>
      <div class="main-char">
        <div class="header">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 80" width="110" height="160" aria-label="document dollar triangle outline" style="--colorSVG: rgba(250, 92, 92, 0.692);">
            <path d="M4 8 H44 L56 20 V72 H4 Z" fill="none" stroke="#ffff" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>
            <path d="M44 8 L56 20" fill="none" stroke="#ffff" stroke-width="3" stroke-linecap="round"/>
            <polygon points="44,8 56,20 44,19" fill="none" stroke="#ffff" stroke-width="2" stroke-linejoin="round"/>
            <text x="30" y="44" text-anchor="middle" dominant-baseline="middle" font-family="Arial, Helvetica, sans-serif" font-weight="700" font-size="20" fill="#ffff">$</text>
          </svg>
          <h1>{{__('messages.expenses')}}</h1>
        </div>
        <div class="char">
          <p>{{$dataGetLineage["expenses"] ?? 0}}%</p>
          <canvas class="chart-line" data-points='@json($dataGetArray["expenses"])'></canvas>
        </div>
      </div>
      <div class="main-char">
        <div class="header">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 48" width="64" height="48" fill="none" stroke="#ffff" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" style="--colorSVG: rgba(92, 250, 100, 0.692);">
            <rect x="4" y="6" width="56" height="36" rx="8" fill="none"/>
            <rect x="10" y="12" width="45" height="6" rx="3" fill="#ffff" stroke="none"/>
            <polygon points="12,24 53,24 49,32" fill="#ffff" stroke="#ffff"/>
          </svg>
          <h1>{{__('messages.revenues')}}</h1>
        </div>
        <div class="char">
          <p>{{$dataGetLineage["revenues"] ?? 0}}%</p>
          <canvas class="chart-line" data-points='@json($dataGetArray["revenues"])'></canvas>
        </div>
      </div>
    </div>
    <div class="main-char char-circle">
      <div class="header">
        <h1>{{__('messages.revenues')}}</h1>
      </div>
      <div class="char">
        <canvas class="chart-circle" data-revenues='@json($dataGetLineage["revenues"])' data-expenses='@json($dataGetLineage["expenses"])'></canvas>
        <p>{{$dataGetLineage["revenues"] ?? 0}}%</p>
      </div>
      <div class="footer">
        <div class="color" style="--colorFooterChar: rgba(255, 245, 100, 1);">
          <span></span>
          <p>{{__('messages.revenues')}}</p>
        </div>
        <div class="color" style="--colorFooterChar: rgba(136, 136, 136, 1);">
          <span></span>
          <p>{{__('messages.expenses')}}</p>
        </div>
      </div>
    </div>
  </div>
  <div class="body-charts">
    <div class="main-charts">
      <div class="char">
        <canvas class="chart-bar" data-expenses='@json($dataGetArray["expenses"])' data-revenues='@json($dataGetArray["revenues"])'></canvas>
      </div>
      <div class="char">
        <canvas class="chart-sankey" data-links='@json($finalLinks)'></canvas>
      </div>
    </div>
  </div>
  <div class="main-tables">
    <div class="row-tables">
      <main>
        <h1>{{__('messages.subscriptions')}}</h1>
        <x-components::table :header="[__('messages.name'), __('messages.id'), __('messages.state')]">
          @if ($systems)
            @foreach ($systems as $item)
              <div class="row">
                <p class="search"><img src="{{optional($item)->img ? asset('images/subscribers/' . optional($item)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img" loading="lazy">{{$item->fname}} {{$item->lname}}</p>
                <p>{{$item->code}}</p>
                <p data-state="{{$item->category}}">{{$item->category}}</p>
              </div>
            @endforeach
          @endif
        </x-components::table>
      </main>
      <main>
        <h1>{{__('messages.nutritional_supplements')}}</h1>
        <x-components::table :header="[__('messages.name'), __('messages.id'), __('messages.name-product'), __('messages.date')]">
          @if ($supplements)
            @foreach ($supplements as $item)
              <div class="row">
                <p class="search"><img src="{{optional($item->employee)->img ? asset('images/employee/' . optional($item->employee)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img" loading="lazy">{{$item->employee->fname}} {{$item->employee->lname}}</p>
                <p>{{$item->code}}</p>
                <p>{{$item->order_name}}</p>
                <p>{{$item->created_at}}</p>
              </div>
            @endforeach
          @endif
        </x-components::table>
      </main>
    </div>
    <div class="main-header-lineage">
      <div @if ($expenses["state"] == 1) class="lineage" @else class="lineage descending" @endif >
        <div class="header">
          <h1>{{__('messages.expenses')}}</h1>
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
            <h1>{{$expenses["total"]}} EGP</h1>
            <p>{!! __('messages.you_won_month', ['lineage' => '<span class="excellent">'.$expenses["lineage"].'%</span>']) !!}</p>
          </div>
          <div class="stock-index">
            @if ($expenses["state"] == 1)
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" viewBox="0 0 67 44" fill="none">
                <defs>
                  <marker id="arrowhead-2" markerWidth="6" markerHeight="6" refX="4.8" refY="3" orient="auto">
                    <path d="M0,0 L6,3 L0,6 Z" fill="var(--colorSVGAnalytcis)"/>
                  </marker>
                </defs>
                <path d="M6 34 L24 20 L32 26 L48 10" stroke="var(--colorSVGAnalytcis)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" marker-end="url(#arrowhead-2)"/>
              </svg>
            @else
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="22" viewBox="0 0 67 44" fill="none">
                <defs>
                  <marker id="arrowhead-1" markerWidth="6" markerHeight="6" refX="4.8" refY="3" orient="auto">
                    <path d="M0,0 L6,3 L0,6 Z" fill="var(--colorSVGAnalytcis)"/>
                  </marker>
                </defs>
                <path d="M6 34 L24 20 L32 26 L48 10" stroke="var(--colorSVGAnalytcis)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" marker-end="url(#arrowhead-1)"/>
              </svg>
            @endif
            <p>{{$expenses["lineage"]}} %</p>
          </div>
        </main>
      </div>
      <div @if ($revenues["state"] == 1) class="lineage" @else class="lineage descending" @endif >
        <div class="header">
          <h1>{{__('messages.revenues')}}</h1>
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
            <h1>{{$revenues["total"]}} EGP</h1>
            <p>{!! __('messages.you_won_month', ['lineage' => '<span class="excellent">'.$revenues["lineage"].'%</span>']) !!}</p>
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
            <p>{{$revenues["lineage"]}} %</p>
          </div>
        </main>
      </div>
    </div>
    <main>
      <h1>{{__('messages.wallet-transactions')}}</h1>
      <x-components::table :header="[__('messages.name'), __('messages.id'), __('messages.state'), __('messages.amount'), __('messages.date')]">
        @if ($incomeStatement)
          @foreach ($incomeStatement as $item)
            <div class="row">
              <p>{{$item->name}}</p>
              <p>{{$item->code}}</p>
              <p data-state="{{$item->type}}">{{$item->type}}</p>
              <p>{{$item->amount}}</p>
              <p>{{$item->created_at}}</p>
            </div>
          @endforeach
        @endif
      </x-components::table>
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-sankey/dist/chartjs-chart-sankey.min.js"></script>
  <script src="{{asset("js/Company/pages/analytics.js")}}"></script>
@endsection
