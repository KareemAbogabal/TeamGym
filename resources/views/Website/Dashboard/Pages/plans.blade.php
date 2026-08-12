@extends('Website.Dashboard.homePage')

@section('title', "Plans and Subscriptions")

@section('class', "plans")

@section('content')
  <div class="charts">
    @if ($plan)
      <div class="side">
        <h1>{{__('messages.your-plan')}} <span data-category="{{$plan->category}}">{{ \Illuminate\Support\Facades\Lang::has("messages.sc-4-card-h1-{$plan->category}") ? __("messages.sc-4-card-h1-{$plan->category}") : $plan->category }}</span></h1>
        <p>available</p>
      </div>
      @foreach ($plan->payment as $payment)
        <div class="char">
          <canvas id="chart-1" data-amount="{{$payment->amount}}" data-paid="{{$payment->paid}}"></canvas>
          <p>{{$payment->amount}} {{__('messages.EGP')}}</p>
        </div>
        <div class="side">
          <div class="color">
            <div>
              <span></span>
              <p>{{__('messages.paid')}}</p>
            </div>
            <p>{{$payment->paid}} {{__('messages.EGP')}}</p>
          </div>
          <div class="color">
            <div>
              <span></span>
              <p>{{__('messages.residual')}}</p>
            </div>
            <p>{{$payment->amount - $payment->paid}} {{__('messages.EGP')}}</p>
          </div>
        </div>
      @endforeach
    @endif
  </div>
  <main class="main-tabel-row-search">
    <div class="content">
      <h1>{{__('messages.payments')}}</h1>
      <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
    </div>
    <x-components::table :header="[__('messages.registered-entity'), __('messages.order-name'), __('messages.type'), __('messages.amount'), __('messages.date')]">
      @if ($registriePayments && $registriePayments->payment)
        @foreach ($registriePayments->payment as $payment)
          @foreach ($payment->registries as $item)
            <div class="row">
              <p class="search">
                <img src="{{optional($item->employee)->img ? asset('images/employee/' . optional($item->employee)->img) : asset('images/header/Team-Gym.png')}}" alt="">
                {{optional($item->employee)->fname}} {{optional($item->employee)->lname}}
              </p>
              <p>{{$item->order_name}}</p>
              <p>{{$item->type}}</p>
              <p>{{$item->amount}} {{__('messages.EGP')}}</p>
              <p>{{$item->created_at}}</p>
            </div>
          @endforeach
        @endforeach
      @endif
    </x-components::table>
  </main>
  <main class="main-tabel-row-search">
    <div class="content">
      <h1>{{__('messages.recordings')}}</h1>
      <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
    </div>
    <x-components::table :header="[__('messages.name-client'), __('messages.amount'), __('messages.condition'), __('messages.registered-entity'), __('messages.date')]">
      @if ($records)
        @foreach ($records as $item)
          <div class="row">
            <p class="search"><img src="{{optional($item->client)->img ? asset('images/subscribers/' . optional($item->client)->img) : asset('images/header/Team-Gym.png')}}" alt="">{{$item->name_client}}</p>
            <p data-state="{{$item->state}}">{{$item->state}}</p>
            <p data-state="{{$item->amount}}">{{$item->amount}}</p>
            <p><img src="{{optional($item->employee)->img ? asset('images/employee/' . optional($item->employee)->img) : asset('images/header/Team-Gym.png')}}" alt="">{{$item->employee->fname}} {{$item->employee->lname}}</p>
            <p>{{$item->created_at}}</p>
          </div>
        @endforeach
      @endif
    </x-components::table>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{asset("js/Website/Dashboard/pages/plans.js")}}"></script>
@endsection
