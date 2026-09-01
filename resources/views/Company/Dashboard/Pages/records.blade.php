@extends('Company.Dashboard.homePageCompany')

@section('title', "Records")

@section('class', "records")

@section('content')
  <x-components::main-card state="details-customer" dataFollow="details-card">
    <div class="body-card">
      <div class="img img-card">
        <img src="{{ asset('images/header/Team-Gym.png') }}" alt="No Img" loading="lazy">
        <div class="content">
          <h1 class="full-name-card"></h1>
          <p>client</p>
        </div>
      </div>
      <div class="main-t">
        <main class="system">
          <h1>system</h1>
          <div class="head">
            <div class="lineage-system">
              <div class="side">
                <h1>{{__('messages.your-plan')}} <span class="plan"></span></h1>
                <p>available</p>
              </div>
              <div class="side">
                <p class="payment-paid" data-lable="{{__('messages.paid')}}"></p>
                <p class="payment-residual" data-lable="{{__('messages.residual')}}"></p>
              </div>
            </div>
          </div>
          <x-components::table :header="[__('messages.order-name'), __('messages.type'), __('messages.amount'), __('messages.date')]" />
        </main>
        <main class="supplement">
          <h1>Supplement</h1>
          <x-components::table :header="[__('messages.order-name'), __('messages.type'), __('messages.amount'), __('messages.date')]" />
        </main>
      </div>
      <div class="button-row-card">
        <div class="buttons">
          <x-components::close-button follow="details-card" />
        </div>
      </div>
    </div>
  </x-components::main-card>
  <x-company::recordsForm
    dataFollowButton="records-form"
    :settingCompany="$settingCompany"
    :supplements="$supplements"
    :systems="$systems"
  />
  <div class="main">
    <div class="content-table">
      <h1>customer subscriptions</h1>
      <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
    </div>
    <x-components::table :header="[__('messages.name-client'), __('messages.phone'), __('messages.category'), __('messages.date'), __('messages.details')]">
      @if ($clients)
        @foreach($clients as $client)
          <div class="row row-customer"
            data-img="{{ optional($client)->img ? asset('images/subscribers/' . optional($client)->img) : asset('images/header/Team-Gym.png') }}"
            data-name="{{ optional($client)->fname }} {{ optional($client)->lname }}"
            data-documentation="{{$client->documentation}}"
          >
            <div class="content">
              <p class="search">
                <img src="{{ optional($client)->img ? asset('images/subscribers/' . optional($client)->img) : asset('images/header/Team-Gym.png') }}" alt="">
                {{ optional($client)->fname }} {{ optional($client)->lname }}
              </p>
              <p>{{$client->phone}}</p>
              <p>{{$client->category}}</p>
              <p>{{$client->created_at}}</p>
              <p>
                <button type="button" class="btn-details-customer" data-code="{{$client->code}}" data-follow="details-card">
                  <svg width="30" height="30" viewBox="0 0 64 64" aria-hidden="true">
                    <circle cx="32" cy="22" r="3" fill="var(--colorSVG1)"/>
                    <circle cx="32" cy="32" r="3" fill="var(--colorSVG1)"/>
                    <circle cx="32" cy="42" r="3" fill="var(--colorSVG1)"/>
                  </svg>
                </button>
              </p>
            </div>
          </div>
        @endforeach
      @endif
    </x-components::table>
  </div>
  <div class="main">
    <div class="content-table">
      <h1>{{__('messages.records')}}</h1>
      <input type="text" data-state-search="record" class="search-input" placeholder="{{__('messages.search')}}">
    </div>
    <x-components::main-card state="entrances" dataFollow="entrances-card">
      <div class="body-card">
        <div class="img">
          <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-profile" alt="No Img" loading="lazy">
          <div class="content">
            <h1 class="full-name"></h1>
            <p>client</p>
          </div>
        </div>
        <form action="{{route("recordExit")}}" method="post" class="exit-form">
          @csrf
          <input type="hidden" id="code" class="code" name="code">
          <div class="main-input">
            <label for="full-name-card">{{__('messages.form-full-name')}}</label>
            <div class="row-input">
              <input type="text" class="fname" name="fname">
              <input type="text" class="lname" name="lname">
            </div>
          </div>
          <input type="hidden" name="order_name" autocomplete="off" class="input attachment-name-exit" />
          <input type="hidden" name="attachment" class="attachment-input-exit">
          <input type="hidden" name="attachment_supplement_code" class="supplement-input-exit-code">
          <input type="hidden" name="attachment_supplement" class="supplement-input-exit">
          <input type="hidden" name="attachment_system_code" class="system-input-exit-code">
          <input type="hidden" name="attachment_system" class="system-input-exit">
          <div class="main-input">
            <label for="attachment">{{__('messages.attachment')}}</label>
            <div class="row-input">
              @if ($settingCompany->subscription_requests == true)
                <button type="button" class="button-choose" data-name="system">{{__('messages.system')}}</button>
              @endif
              @if ($settingCompany->supplements_requests == true)
                <button type="button" class="button-choose" data-name="supplement">{{__('messages.supplement')}}</button>
              @endif
              <button type="button" class="button-choose" data-name="snacks">{{__('messages.snacks')}}</button>
            </div>
          </div>
          <div class="main-input main-amount">
            <label for="amount">{{__('messages.amount')}}</label>
            <input type="text" id="amount" class="amount" name="amount">
          </div>
          <div class="radio-input">
          </div>
          <div class="radio-input radio-input-snacks">
            @if ($snacks)
              @foreach ($snacks as $index => $item)
                <label class="label radio-system-snacks">
                  <input type="radio" value="{{ $item->code }}" name="radio-system-installment">
                  <span class="text">{{ $item->name }}</span>
                </label>
              @endforeach
            @endif
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <x-components::close-button follow="entrances-card" />
              <button type="submit" class="view-profile tg-btn tg-btn--primary">{{__('messages.form-send')}}</button>
            </div>
          </div>
        </form>
      </div>
    </x-components::main-card>
    <x-components::table :header="[__('messages.name'), __('messages.id'), __('messages.state'), __('messages.amount'), __('messages.attachment'), __('messages.date'), __('messages.details')]">
      @if ($record)
        @foreach ($record as $index => $item)
          <div class="row row-record">
            <div class="content">
              <p class="search">
                <img src="{{ optional($item->client)->img ? asset('images/subscribers/' . optional($item->client)->img) : asset('images/header/Team-Gym.png') }}" alt="">
                {{ optional($item->client)->fname }} {{ optional($item->client)->lname }}
              </p>
              <p class="code-client">{{ optional($item->client)->code }}</p>
              <p data-state="{{ $item->state }}">{{ $item->state }}</p>
              <p data-state="{{ $item->amount }}">{{ $item->amount }}</p>
              @if ($item->state == "entrance")
                <p>--</p>
              @else
                @if ($item->attachment == null)
                  <p>--</p>
                @else
                  <p>{{$item->attachment}}</p>
                @endif
              @endif
              <p>{{ $item->created_at }}</p>
              <p>
                <button type="button" class="btn-details">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 48 48">
                    <circle cx="24" cy="24" r="22" stroke="var(--colorSVG2)" stroke-width="2.5" fill="none"/>
                    <path d="M16 20 L24 30 L32 20" fill="none" stroke="var(--colorSVG2)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </p>
            </div>
            <div class="details">
              <ul>
                <li>{{__('messages.registered-entity')}}: {{ optional($item->employee)->name ?? $item->name_employee }}</li>
                <li>{{__('messages.phone')}}: {{ optional($item->employee)->phone ?? $item->phone_employee }}</li>
                <li>{{__('messages.form-job-role')}}: {{ optional($item->employee)->job_role ?? $item->job_role_employee }}</li>
                @if ($item->state == "entrance")
                  <li><button type="button" class="btn-entrance" data-follow="entrances-card" data-full-name="{{optional($item->client)->fname}} {{optional($item->client)->lname}}" data-fname="{{optional($item->client)->fname}}" data-lname="{{optional($item->client)->lname}}" data-code="{{optional($item->client)->code}}" data-documentation="{{optional($item->client)->documentation}}" data-system="{{optional($item->client)->category}}" data-system-defult="{{$systemDefult->name}}">{{__('messages.entrance')}}</button></li>
                @endif
              </ul>
            </div>
          </div>
        @endforeach
      @endif
    </x-components::table>
  </div>
  <script src="{{asset("js/Company/pages/records.js")}}"></script>
@endsection
