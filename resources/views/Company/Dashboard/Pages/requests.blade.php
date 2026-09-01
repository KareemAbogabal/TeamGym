@extends('Company.Dashboard.homePageCompany')

@section('title', "Requests")

@section('class', "requests")

@section('content')
  <x-components::main-card state="subscription-requests" dataFollow="requests-card">
    <div class="body-card">
        <div class="img">
          @php
            $employee = Auth::guard('employee')->user();
          @endphp
          <img src="{{optional($employee)->img ? asset('images/employee/' . optional($employee)->img) : asset('images/header/Team-Gym.png')}}" class="img-profile" alt="No Img" loading="lazy">
          <div class="content">
            <h1>{{optional($employee)->fname}} {{optional($employee)->lname}}</h1>
            <p>employee</p>
          </div>
          @if (optional($employee)->documentation == "true")
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
        <form action="{{route("customerRequests")}}" method="post">
          @csrf
          <div class="main-input">
            <label for="full-name">{{__('messages.form-full-name')}}</label>
            <div class="row-input">
              <input type="text" id="full-name" class="fname" name="fname">
              <input type="text" name="lname" class="lname">
            </div>
          </div>
          <input type="hidden" class="id_request" name="id_request">
          <input type="hidden" class="code_order" name="code_order">
          <div class="main-input">
            <label for="phone">{{__('messages.form-phone')}}</label>
            <input type="text" id="phone" class="phone" name="phone">
          </div>
          <div class="main-input">
            <label for="order_name">{{__('messages.order-name')}}</label>
            <input type="text" id="order_name" class="order_name" name="order_name" readonly>
          </div>
          <div class="main-input">
            <label for="paid">{{__('messages.paid')}}</label>
            <input type="text" id="paid" class="paid" name="paid">
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <x-components::close-button follow="requests-card" />
              <button type="submit" name="action" value="reject" class="reject">{{__('messages.reject')}}</button>
              <button type="submit" name="action" data-label="{{ __('messages.form-send') }}" value="acceptance" class="acceptance view-profile">{{__('messages.acceptance')}}</button>
            </div>
          </div>
        </form>
      </div>
    </x-components::main-card>
  <x-components::main-card state="edit" dataFollow="edit-card">
      <div class="body-card">
        <div class="img img-card">
          <img src="{{ asset('images/header/Team-Gym.png') }}" alt="No Img" loading="lazy">
          <div class="content">
            <h1 class="full-name-card"></h1>
            <p>client</p>
          </div>
        </div>
        <form action="{{route("updateClient")}}" method="post">
          @csrf
          <div class="main-input">
            <label for="full-name-card">Full Name</label>
            <div class="row-input">
              <input type="text" id="full-name-card" class="fname" name="fname">
              <input type="text" class="lname" name="lname">
            </div>
          </div>
          <input type="hidden" class="code_request_payment" name="code_request_payment">
          <input type="hidden" class="code_client" name="code_client">
          <input type="hidden" class="code_supplements" name="code_supplements">
          <input type="hidden" class="code_systems" name="code_systems">
          <div class="main-input">
            <label for="supplement">{{__('messages.order-name')}}</label>
            <input type="text" id="supplement" class="order_name" name="order_name" readonly>
          </div>
          <div class="main-input">
            <label for="amount">{{__('messages.amount')}}</label>
            <input type="text" id="amount" class="amount" name="amount">
          </div>
          <div class="main-input">
            <label for="end-date">{{__('messages.pay-day')}}</label>
            <input type="text" id="end-date" class="payday" name="payday">
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <x-components::close-button follow="edit-card" />
              <button type="submit" name="action" value="reject" class="reject">{{__('messages.reject')}}</button>
              <button type="submit" name="action" value="acceptance" class="acceptance view-profile">{{__('messages.acceptance')}}</button>
            </div>
          </div>
        </form>
      </div>
    </x-components::main-card>
  <div class="main-table">
    <div class="charts-circle">
      <div class="main-char">
        <div class="char">
          <canvas id="chart-1" data-percentage="{{$supplements}}"></canvas>
          <p>{{$supplements}}%</p>
        </div>
        <div class="content">
          <h1>{{__('messages.supplement')}}</h1>
          <p>{{__('messages.supplement-d')}}</p>
        </div>
      </div>
    </div>
    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{__('messages.supplement')}}</h1>
        <input type="text" class="search-input" placeholder="Search">
      </div>
      <x-components::table :header="[__('messages.name'), __('messages.product-name'), __('messages.state'), __('messages.amount'), __('messages.details')]">
        @if ($requestsPayment)
          @foreach ($requestsPayment as $item)
            @if ($item->order_name == "supplement")
              <div class="row"
                data-img="{{optional($item->client)->img ? asset('images/subscribers/' . optional($item->client)->img) : asset('images/header/Team-Gym.png')}}"
                data-name="{{$item->client->fname}} {{$item->client->lname}}"
                data-fname="{{$item->client->fname}}"
                data-lname="{{$item->client->lname}}"
                data-documentation="{{$item->client->documentation}}"
                data-code-request-payment="{{$item->code}}"
                data-code-client="{{$item->client->code}}"
                data-code-supplements="{{$item->code_supplements}}"
                data-code-system="{{$item->code_systems}}"
                data-order-name="{{$item->order_name}}"
                data-amount="{{$item->amount}}"
                data-payday="{{$item->payday}}"
                data-state-buttons-control="{{$item->state}}"
              >
                <p class="search"><img src="{{optional($item->employee)->img ? asset('images/employee/' . optional($item->employee)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img" loading="lazy">
                  @if($item->employee)
                    {{trim((optional($item->employee)->fname ?? '') . ' ' . (optional($item->employee)->lname ?? '')) ?: $item->code_employee}}
                  @else
                    {{$item->code_employee}}
                  @endif
                </p>
                <p>{{$item->order_name}}</p>
                <p data-state="{{$item->state}}">{{$item->state}}</p>
                <p>{{$item->amount}}</p>
                <div class="content-row">
                  <button type="button" class="edit" data-follow="edit-card">
                    <svg width="30" height="30" viewBox="0 0 64 64" style="transform: rotate(145deg)" fill="none" aria-hidden="true">
                      <g stroke="var(--colorSVG1)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none">
                        <rect x="6"  y="26" width="6"  height="12" rx="1"/>
                        <rect x="12" y="26" width="36" height="12" rx="1"/>
                        <polygon points="48,26 60,32 48,38"/>
                      </g>
                    </svg>
                  </button>
                </div>
              </div>
            @endif
          @endforeach
        @endif
      </x-components::table>
    </main>
    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{__('messages.requests-clients')}}</h1>
        <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
      </div>
      <x-components::table :header="[__('messages.name'), __('messages.state'), __('messages.name-client'), __('messages.date'), __('messages.details')]">
        @if ($requestsPayment)
          @foreach ($requestsPayment as $item)
            @if ($item->order_name !== "supplement")
              <div class="row"
                data-img="{{optional($item->client)->img ? asset('images/subscribers/' . optional($item->client)->img) : asset('images/header/Team-Gym.png')}}"
                data-name="{{$item->client->fname}} {{$item->client->lname}}"
                data-fname="{{$item->client->fname}}"
                data-lname="{{$item->client->lname}}"
                data-documentation="{{$item->client->documentation}}"
                data-code-request-payment="{{$item->code}}"
                data-code-client="{{$item->client->code}}"
                data-code-supplements="{{$item->code_supplements}}"
                data-code-system="{{$item->code_systems}}"
                data-order-name="{{$item->order_name}}"
                data-amount="{{$item->amount}}"
                data-payday="{{$item->payday}}"
                data-state-buttons-control="{{$item->state}}"
              >
                <p class="search"><img src="{{optional($item->employee)->img ? asset('images/employee/' . optional($item->employee)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img" loading="lazy">
                  @if($item->employee)
                    {{trim((optional($item->employee)->fname ?? '') . ' ' . (optional($item->employee)->lname ?? '')) ?: $item->code_employee}}
                  @else
                    {{$item->code_employee}}
                  @endif
                </p>
                <p data-state="{{$item->state}}">{{$item->state}}</p>
                <p><img src="{{optional($item->client)->img ? asset('images/subscribers/' . optional($item->client)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img" loading="lazy">{{optional($item->client)->fname}} {{optional($item->client)->lname}}</p>
                <p>{{ now()->format('Y-m-d H:i:s') }}</p>
                <div class="content-row">
                  <button type="button" class="edit" data-follow="edit-card">
                    <svg width="30" height="30" viewBox="0 0 64 64" style="transform: rotate(145deg)" fill="none" aria-hidden="true">
                      <g stroke="var(--colorSVG1)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none">
                        <rect x="6"  y="26" width="6"  height="12" rx="1"/>
                        <rect x="12" y="26" width="36" height="12" rx="1"/>
                        <polygon points="48,26 60,32 48,38"/>
                      </g>
                    </svg>
                  </button>
                </div>
              </div>
            @endif
          @endforeach
        @endif
      </x-components::table>
    </main>
    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{__('messages.subscription-requests')}}</h1>
        <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
      </div>
      <x-components::table :header="[__('messages.name-client'), __('messages.phone'), __('messages.state'), __('messages.date'), __('messages.details')]">
        @if ($customerRequests)
          @foreach ($customerRequests as $item)
            <div class="row"
              data-fname="{{$item->fname}}"
              data-lname="{{$item->lname}}"
              data-id-request="{{$item->id}}"
              data-code-order="{{$item->code_order}}"
              @if ($item->system !== null)
                data-order-name="{{$item->system}}"
              @else
                data-order-name="{{$item->supplement}}"
              @endif
              data-phone="{{$item->phone}}"
              data-state-buttons-control="{{$item->state}}"
              data-paid="{{$item->paid}}"
            >
              <p class="search">{{$item->fname}} {{$item->lname}}</p>
              <p>{{$item->phone}}</p>
              <p data-state="{{$item->state}}">{{$item->state}}</p>
              <p>{{$item->created_at}}</p>
                <div class="content-row">
                  <button type="button" class="btn-requests" data-follow="requests-card">
                    <svg width="30" height="30" viewBox="0 0 64 64" style="transform: rotate(145deg)" fill="none" aria-hidden="true">
                      <g stroke="var(--colorSVG1)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none">
                        <rect x="6"  y="26" width="6"  height="12" rx="1"/>
                        <rect x="12" y="26" width="36" height="12" rx="1"/>
                        <polygon points="48,26 60,32 48,38"/>
                    </g>
                  </svg>
                </button>
              </div>
            </div>
          @endforeach
        @endif
      </x-components::table>
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
  <script src="{{asset("js/Company/pages/requests.js")}}"></script>
@endsection
