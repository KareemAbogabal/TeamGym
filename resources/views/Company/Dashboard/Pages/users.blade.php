@extends('Company.Dashboard.homePageCompany')

@section('title', "Users")

@section('class', "users")

@section('content')
  @php
    $docBadge = '<svg viewBox="0 0 256 256" width="20" height="20" class="verification-badge"><defs><path id="tooth-user" d="M 0,-110 C 5,-106 10,-98 14,-84 L 6,-62 C 3,-56 0,-54 0,-54 C 0,-54 -3,-56 -6,-62 L -14,-84 C -10,-98 -5,-106 0,-110 Z" /><linearGradient id="yellow-white-yellow-user" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#f6d93b"/><stop offset="50%" stop-color="#ffffff"/><stop offset="100%" stop-color="#f6d93b"/></linearGradient></defs><g transform="translate(128 128)"><g fill="url(#yellow-white-yellow-user)" stroke-linejoin="round" transform="scale(1.02)">@for($i=0;$i<36;$i++)<use href="#tooth-user" transform="rotate({{ $i * 10 }})"/>@endfor<circle r="92" fill="url(#yellow-white-yellow-user)" stroke-width="1.4"/></g><path d="M -34 0 L -4 40 L 56 -20" fill="none" transform="translate(-15, -6)" stroke="#000" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/></g></svg>';
  @endphp

  <div class="content-table">
    <h1>{{__('messages.employees')}}</h1>
  </div>

  <x-components::main-card state="edit">
    <div class="body-card">
      <div class="img img-card">
        <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-card" alt="No Img" loading="lazy">
        <div class="content">
          <h1 class="full-name-card"></h1>
          <p>{{ __('messages.employee') }}</p>
        </div>
      </div>
      <form action="{{ route("updateEmployee") }}" method="post">
        @csrf
        <div class="main-input">
          <label for="employee-fname-edit">{{ __('messages.form-full-name') }}</label>
          <div class="row-input">
            <input type="text" id="employee-fname-edit" class="fname-card" name="fname-employee">
            <input type="text" class="lname-card" name="lname-employee">
          </div>
        </div>
        <input type="hidden" class="code" name="code-employee">
        <div class="main-input">
          <label for="employee-job-edit">{{ __('messages.job-role') }}</label>
          <input type="text" id="employee-job-edit" class="role-card" name="job_role-employee">
        </div>
        <div class="main-input">
          <label for="employee-communication-edit">{{ __('messages.form-communication') }}</label>
          <div class="row-input">
            <input type="text" id="employee-communication-edit" class="email-card" name="email-employee" placeholder="{{ __('messages.form-email') }}">
            <input type="text" class="phone-card" name="phone-employee" placeholder="{{ __('messages.form-phone') }}">
          </div>
        </div>
        <div class="main-input">
          <label for="employee-password-edit">{{ __('messages.form-password') }}</label>
          <input type="text" id="employee-password-edit" class="password-card" name="password-employee">
        </div>
        <div class="main-switch">
          <div>
            <div class="item-title">{{ __('messages.form-documentation') }}</div>
            <div class="item-sub">{{ __('messages.form-documentation-d') }}</div>
          </div>
          <label class="switch">
            <input type="checkbox" class="documentation-input" name="documentation-employee">
            <span class="slider"></span>
          </label>
        </div>
        <div class="button-row-card">
          <div class="buttons">
            <x-components::close-button />
            <button type="submit" class="view-profile tg-btn tg-btn--primary">{{ __('messages.form-send') }}</button>
          </div>
        </div>
      </form>
    </div>
  </x-components::main-card>

  <x-components::main-card state="list">
    <div class="body-card">
      <div class="head">
        <div class="img">
          <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-card" alt="No Img" loading="lazy">
          <div class="content">
            <h1 class="full-name-card"></h1>
            <p class="role-card-text">{{ __('messages.employee') }}</p>
          </div>
          <span class="verification-wrap">{!! $docBadge !!}</span>
        </div>
        <div class="profile-info">
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">{{ __('messages.code') }}</span>
              <span class="info-value code-value"></span>
            </div>
            <div class="info-item">
              <span class="info-label">{{ __('messages.job-role') }}</span>
              <span class="info-value role-value"></span>
            </div>
            <div class="info-item">
              <span class="info-label">{{ __('messages.form-email') }}</span>
              <span class="info-value email-value"></span>
            </div>
            <div class="info-item">
              <span class="info-label">{{ __('messages.form-phone') }}</span>
              <span class="info-value phone-value"></span>
            </div>
          </div>
        </div>
      </div>
      <form action="{{ route("updateEmployee") }}" method="post">
        @csrf
        <div class="main-input">
          <label for="employee-fname-list">{{ __('messages.form-full-name') }}</label>
          <div class="row-input">
            <input type="text" id="employee-fname-list" class="fname-card" name="fname-employee">
            <input type="text" class="lname-card" name="lname-employee">
          </div>
        </div>
        <input type="hidden" class="code" name="code-employee">
        <div class="main-input">
          <label for="employee-communication-list">{{ __('messages.form-communication') }}</label>
          <div class="row-input">
            <input type="text" id="employee-communication-list" class="email-card" name="email-employee" placeholder="{{ __('messages.form-email') }}">
            <input type="text" class="phone-card" name="phone-employee" placeholder="{{ __('messages.form-phone') }}">
          </div>
        </div>
        <div class="main-switch">
          <div>
            <div class="item-title">{{ __('messages.form-documentation') }}</div>
            <div class="item-sub">{{ __('messages.form-documentation-d') }}</div>
          </div>
          <label class="switch">
            <input type="checkbox" class="documentation-input" name="documentation-employee">
            <span class="slider"></span>
          </label>
        </div>
        <div class="button-row-card">
          <div class="buttons">
            <x-components::close-button />
            <button type="submit" class="view-profile tg-btn tg-btn--primary">{{ __('messages.form-send') }}</button>
          </div>
        </div>
      </form>
    </div>
  </x-components::main-card>

  <main>
    <x-components::table :header="[__('messages.name'), __('messages.job-role'), __('messages.form-phone'), __('messages.date'), __('messages.details')]">
      @if ($employees)
        @foreach ($employees as $item)
          <div class="row"
            data-img="{{ $item->img ? asset('images/employee/' . $item->img) : asset('images/header/Team-Gym.png') }}"
            data-code="{{ $item->code }}"
            data-documentation="{{ $item->documentation }}"
            data-role="{{ $item->job_role }}"
            data-communication="{{ $item->email }}"
          >
            <p class="search"><img src="{{ $item->img ? asset('images/employee/' . $item->img) : asset('images/header/Team-Gym.png') }}" alt="No Img" loading="lazy">{{ $item->fname }} {{ $item->lname }}</p>
            <p class="role">{{ $item->job_role }}</p>
            <p class="phone">{{ $item->phone }}</p>
            <p>{{ $item->created_at }}</p>
            <div class="content-row">
              <button type="button" class="edit">
                <svg width="30" height="30" viewBox="0 0 64 64" style="transform: rotate(145deg)" fill="none" aria-hidden="true">
                  <g stroke="var(--colorSVG1)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none">
                    <rect x="6"  y="26" width="6"  height="12" rx="1"/>
                    <rect x="12" y="26" width="36" height="12" rx="1"/>
                    <polygon points="48,26 60,32 48,38"/>
                  </g>
                </svg>
              </button>
              <form action="{{ route("destroy") }}" method="post">
                @csrf
                <input type="hidden" value="{{ $item->id }}" name="id">
                <input type="hidden" value="employee" name="state">
                <button type="submit" class="destroy-employee">
                  <svg width="30" height="30" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <g stroke="var(--colorSVG1)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
                      <rect x="14" y="10" width="36" height="6" rx="2"/>
                      <rect x="26" y="8" width="12" height="4" rx="1" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="1"/>
                      <path d="M16 20 L48 20 L44 54 L20 54 Z" />
                      <path d="M24 26 L26 48" />
                      <path d="M32 26 L32 48" />
                      <path d="M40 26 L38 48" />
                      <path d="M20 54h24" stroke-width="3" stroke-linecap="round"/>
                    </g>
                  </svg>
                </button>
              </form>
              <button type="button" class="show">
                <svg width="30" height="30" viewBox="0 0 64 64" aria-hidden="true">
                  <circle cx="32" cy="22" r="3" fill="var(--colorSVG1)"/>
                  <circle cx="32" cy="32" r="3" fill="var(--colorSVG1)"/>
                  <circle cx="32" cy="42" r="3" fill="var(--colorSVG1)"/>
                </svg>
              </button>
            </div>
          </div>
        @endforeach
      @endif
    </x-components::table>
  </main>

  <script>
    let employeeLabel = @json(__('messages.employee'));
  </script>
  <script src="{{ asset('js/Company/pages/users.js') }}"></script>
@endsection
