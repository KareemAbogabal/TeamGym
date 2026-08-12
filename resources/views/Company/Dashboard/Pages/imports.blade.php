@extends('Company.Dashboard.homePageCompany')

@section('title', "Imports")

@section('class', "imports")

@section('content')
  <div class="head-analytics">
    <div class="main-quantities">
      {{-- @if ($imports)
        @foreach ($imports as $item)
          <div class="quantity">
            <div class="content">
              <h1>{{$item->quantity}}</h1>
              <p>{{$item->name}}</p>
            </div>
            @if ((int)$item->quantity > 2)
              <span data-state="available">{{__('messages.available')}}</span>
            @elseif ((int)$item->quantity == 2)
              <span data-state="approach">{{__('messages.approach')}}</span>
            @else
              <span data-state="unavailable">{{__('messages.unavailable')}}</span>
            @endif
          </div>
        @endforeach
      @endif --}}
      @if ($supplements)
        @foreach ($supplements as $item)
          <div @if (optional($item->imports)->quantity <= 0) class="product-analytics unavailable" @elseif (optional($item->imports)->quantity == 2) class="product-analytics approach" @else class="product-analytics available" @endif>
            <div class="head">
              <div class="content">
                <img src="{{asset("images/products/" . $item->img)}}" alt="">
                <h1>{{$item->name}}</h1>
                <p>Sales {{$lineagesByPayment[$item->name] ?? 0}}</p>
              </div>
              <div class="state">
                <div class="det">
                  <span @if (optional($item->imports)->quantity <= 0) data-state="unavailable" @elseif (optional($item->imports)->quantity == 2) data-state="approach" @else data-state="available" @endif>{{optional($item->imports)->quantity}}</span>
                  <form action="{{route("destroySupplementsAndImports")}}" method="post">
                    <input type="hidden" value="{{$item->code}}" name="code">
                    <button type="submit">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="18" height="18" aria-label="X in circle">
                        <circle cx="32" cy="32" r="28" fill="none" />
                        <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                        <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                      </svg>
                    </button>
                  </form>
                </div>
                <p @if (optional($item->imports)->quantity <= 0) data-state="unavailable" @elseif (optional($item->imports)->quantity == 2) data-state="approach" @else data-state="available" @endif>
                  @if (optional($item->imports)->quantity <= 0)
                    {{__('messages.unavailable')}}
                  @elseif (optional($item->imports)->quantity == 2)
                    {{__('messages.approach')}}
                  @else
                    {{__('messages.available')}}
                  @endif
                </p>
              </div>
            </div>
            <div class="chart">
              <canvas class="chart-line" data-points='@json($lineagesByName[$item->name])'></canvas>
              <div class="months"></div>
            </div>
          </div>
        @endforeach
      @endif
    </div>
    <div class="main-charts">
      <div class="main-char char-circle">
        <div class="header">
          <h1>{{__('messages.imports')}}</h1>
        </div>
        <div class="char">
          <canvas class="chart-circle" data-revenues='@json($lineageRevenues)' data-input='@json($lineageInput)'></canvas>
          <p>{{$lineageInput}}%</p>
        </div>
        <div class="footer">
          <div class="color" style="--colorFooterChar: rgba(255, 245, 100, 1);">
            <span></span>
            <p>{{__('messages.imports')}}</p>
          </div>
          <div class="color" style="--colorFooterChar: rgba(136, 136, 136, 1);">
            <span></span>
            <p>{{__('messages.expenses')}}</p>
          </div>
        </div>
      </div>
      <main>
        <x-components::table :header="[__('messages.name'), __('messages.id'), __('messages.name-product'), __('messages.date')]">
          @if ($supplementsPayment)
            @foreach ($supplementsPayment as $item)
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
  </div>
  <div class="form">
    <canvas id="chart-1" data-revenues='@json($lineageImport)'></canvas>
    <form action="{{route("addProduct")}}" method="post" enctype="multipart/form-data">
      @csrf
      <h1>{{__('messages.input-recording')}}</h1>
      <div class="nebula-input">
        <input type="text" name="name_product" class="input" />
        <label class="user-label">{{__('messages.product-name')}}</label>
      </div>
      <div class="nebula-input">
        <input type="text" name="description" class="input" />
        <label class="user-label">{{__('messages.description-product')}}</label>
      </div>
      <div class="nebula-input">
        <input type="text" name="price" class="input" />
        <label class="user-label">{{__('messages.price')}}</label>
      </div>
      <div class="nebula-input">
        <input type="text" name="quantity" class="input" />
        <label class="user-label">{{__('messages.quantity')}}</label>
      </div>
      <input type="hidden" class="type-product" value="supplement" name="type">
      <div class="main-input">
        <input type="file" id="img" name="img">
        <label for="img" class="label-upload">
          <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
          </svg>
          <span>{{__('messages.add-img')}}</span>
        </label>
        <div class="mydict">
          <label class="radio-choose">
            <input type="radio" name="radio">
            <span class="text" data-name="snacks">{{__('messages.snacks')}}</span>
          </label>
          <label class="radio-choose">
            <input type="radio" name="radio" checked="">
            <span class="text" data-name="supplement">{{__('messages.supplement')}}</span>
          </label>
        </div>
      </div>
      <button type="submit">{{__('messages.record')}}</button>
    </form>
  </div>
  <div class="main-table">
    <div class="content-table">
      <h1>{{__('messages.imports')}}</h1>
      <input type="text" class="search-input" placeholder="Search">
    </div>
    <x-components::table :header="[__('messages.name'), __('messages.id'), __('messages.product-name'), __('messages.state'), __('messages.quantity'), __('messages.amount'), __('messages.date')]">
      @if ($imports)
        @foreach ($imports as $item)
          <div class="row">
            <p class="search"><img src="{{optional($item->employee)->img ? asset('images/employee/' . optional($item->employee)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img" loading="lazy">{{$item->employee->fname}} {{$item->employee->lname}}</p>
            <p>{{$item->code}}</p>
            <p>{{$item->name}}</p>
            <p data-state="{{$item->state}}">{{$item->state}}</p>
            <p>{{$item->quantity}}</p>
            <p>{{$item->amount}}</p>
            <p>{{$item->created_at}}</p>
          </div>
        @endforeach
      @endif
    </x-components::table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script> --}}
  <script src="{{asset("js/Company/pages/imports.js")}}"></script>
@endsection
