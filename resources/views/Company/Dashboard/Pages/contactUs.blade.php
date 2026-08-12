@extends('Company.Dashboard.homePageCompany')

@section('title', "Contact Us")

@section('class', "contact-us")

@section('content')
  <div class="main-table">
    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{__('messages.contact-us')}}</h1>
        <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
      </div>
      <x-components::table :header="[__('messages.name'), __('messages.phone'), __('messages.subject'), __('messages.date'), __('messages.details')]">
        @if ($orders && $orders->isNotEmpty())
          @foreach ($orders as $item)
            <div class="row">
              <p class="search">{{$item->name}}</p>
              <p>{{$item->phone}}</p>
              <p>{{$item->subject}}</p>
              <p>{{$item->created_at}}</p>
              <div class="content-row">
                <form action="{{route("destroyContact")}}" method="post">
                  @csrf
                  <input type="hidden" name="code" value="{{$item->code}}">
                  <button type="submit" class="edit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="22" height="22" aria-label="X in circle">
                      <circle cx="32" cy="32" r="28" fill="none" />
                      <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                      <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                    </svg>
                  </button>
                </form>
              </div>
            </div>
          @endforeach
        @else
          <div class="choose">
            <p>{{__('messages.no-messages')}}</p>
          </div>
        @endif
      </x-components::table>
    </main>
  </div>
  <script src="{{asset("js/Company/pages/contactUs.js")}}"></script>
@endsection
