@extends('Website.Dashboard.homePage')

@section('title', "Supplement Store")

@section('class', "supplements-store")

@section('content')
  <div class="charts">
    <div class="side">
      <h1>{{__('messages.payments')}}</h1>
      <p>available</p>
    </div>
    <div class="char">
      <canvas class="chart" data-amount="{{$amount}}" data-paid="{{$paid}}"></canvas>
      <p>{{$amount}} {{__('messages.EGP')}}</p>
    </div>
    <div class="side">
      <div class="color">
        <div>
          <span></span>
          <p>{{__('messages.paid')}}</p>
        </div>
        <p>{{$paid}} {{__('messages.EGP')}}</p>
      </div>
      <div class="color">
        <div>
          <span></span>
          <p>{{__('messages.residual')}}</p>
        </div>
        <p>{{$remaining}} {{__('messages.EGP')}}</p>
      </div>
    </div>
  </div>
  <div class="main-products">
    <div class="products">
      @if ($supplements->payment)
        @foreach ($supplements->payment as $itemSupplement)
          <div class="product" id="product-1">
            <div class="main-row">
              <main>
                <div class="ribbon"><span>{{$itemSupplement->amount}} {{__('messages.EGP')}}</span></div>
                <img src="{{asset("images/products/" . $itemSupplement->supplement->img)}}" alt="No Img Product">
                <div class="content">
                  <h1>{{$itemSupplement->supplement->name}}</h1>
                  <button class="show-details-product">
                    <span>{{__('messages.show-the-details')}}</span>
                  </button>
                </div>
              </main>
              <div class="main-char-row">
                <div class="char">
                  <canvas class="chart" data-amount="{{$itemSupplement->amount}}" data-paid="{{$itemSupplement->paid}}"></canvas>
                  <p>{{$itemSupplement->amount}} {{__('messages.EGP')}}</p>
                </div>
                <div class="side">
                  <div class="color">
                    <div>
                      <span></span>
                      <p>{{__('messages.paid')}}</p>
                    </div>
                    <p>{{$itemSupplement->paid}} {{__('messages.EGP')}}</p>
                  </div>
                  <div class="color">
                    <div>
                      <span></span>
                      <p>{{__('messages.residual')}}</p>
                    </div>
                    <p>{{$itemSupplement->amount - $itemSupplement->paid}} {{__('messages.EGP')}}</p>
                  </div>
                </div>
              </div>
            </div>
            <x-components::table :header="[__('messages.name-product'), __('messages.month-pay'), __('messages.amount'), __('messages.date')]">
              @if ($registriePaymentSupplements)
                @foreach ($registriePaymentSupplements->payment as $payment)
                  @foreach ($payment->registries as $item)
                    @if ($item->code_payments == $itemSupplement->code)
                      <div class="row">
                        <p>{{$itemSupplement->supplement->name}}</p>
                        <p>{{$item->paymonth}}</p>
                        <p class="payment-numbers">{{$item->amount}} {{__('messages.EGP')}}</p>
                        <p>{{$item->created_at}}</p>
                      </div>
                    @endif
                  @endforeach
                @endforeach
              @endif
            </x-components::table>
          </div>
        @endforeach
      @endif
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{asset("js/Website/Dashboard/pages/supplementStore.js")}}"></script>
@endsection
