@extends('Company.Dashboard.homePageCompany')

@section('title', "History")

@section('class', "history")

@section('content')
  <div class="content">
    <h1>{{__('messages.history')}}</h1>
    <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
  </div>
  <div class="main">
    <x-components::table :header="[__('messages.name'), __('messages.id'), __('messages.state'), __('messages.amount'), __('messages.attachment'), __('messages.date'), __('messages.details')]">
      @foreach($histories as $history)
        <div class="row">
          <div class="content">
            <p class="search">
            <img
              src="{{
                $history->client?->img
                  ? asset('images/subscribers/' . $history->client->img)
                  : ($history->employee?->img
                      ? asset('images/employee/' . $history->employee->img)
                      : asset('images/header/Team-Gym.png')
                    )
              }}"
              alt="No Img"
              loading="lazy">
              {{$history->client?->fname ?? $history->employee?->fname ?? ''}}
              {{$history->client?->lname ?? $history->employee?->lname ?? ''}}
            </p>
            <p>{{$history->code}}</p>
            <p data-state="{{$history->state}}">{{__('messages.' . $history->state)}}</p>
            <p>{{$history->amount ?? '--'}}</p>
            <p>{{$history->attachment ?? '--'}}</p>
            <p>{{$history->created_at->format('Y-m-d H:i:s')}}</p>
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
            <ul>
              <li>
                {{__('messages.registered-entity')}}:
                {{$history->registered_entity ?? ($history->client?->fname . ' ' . $history->client?->lname) ?? ($history->employee?->fname . ' ' . $history->employee?->lname) ?? '—'}}
              </li>
              @if($history->client)
                <li>{{__('messages.phone')}}: {{$history->client->phone ?? '—'}}</li>
                <li>{{__('messages.form-job-role')}}: Client</li>
              @endif
              @if($history->employee)
                <li>{{__('messages.phone')}}: {{$history->employee->phone ?? '—'}}</li>
                <li>{{__('messages.form-job-role')}}: {{$history->employee->job_role ?? 'Employee'}}</li>
              @endif
            </ul>
          </div>
        </div>
      @endforeach
    </x-components::table>
  </div>
  <script src="{{asset("js/Company/pages/history.js")}}"></script>
@endsection
