@extends('Website.Dashboard.homePage')

@section('title', "Settings")

@section('class', "settings")

@section('content')
  <main>
    <form action="{{route("updateProfile")}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="profile-card">
        <div class="profile-card-inner">
          <div class="avatar-wrap">
            <div class="avatar-frame">
              <img id="profileImage" src='{{$client->img ? asset('images/subscribers/' . $client->img) : asset('images/header/Team-Gym.png')}}' alt="No Img Product" />
              <label for="imageInput" id="cameraBtn" class="camera-btn" aria-label="Upload photo">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden>
                  <path d="M5 7h2l1-2h8l1 2h2v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7z" stroke="var(--colorPearntSection)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                  <circle cx="12" cy="13" r="3" stroke="var(--colorPearntSection)" stroke-width="1.2"/>
                </svg>
              </label>
              <input id="imageInput" type="file" name="profile_image" accept="image/*" style="display:none" />
            </div>
            <button type="submit" name="action" value="removePhoto" id="removePhoto" class="remove-photo tg-btn tg-btn--danger">Remove photo</button>
          </div>
          <div class="form-wrap">
            <div class="row two-cols">
              <label>
                <div class="label">{{__('messages.form-fname')}}</div>
                <input type="text" name="fname" value="{{$client->fname}}" />
              </label>
              <label>
                <div class="label">{{__('messages.form-lname')}}</div>
                <input type="text" name="lname" value="{{$client->lname}}" />
              </label>
            </div>
            <div class="row two-cols">
              <label>
                <div class="label">{{__('messages.form-email')}}</div>
                <input type="text" name="email" value="{{$client->email}}" />
              </label>
              <label>
                <div class="label">{{__('messages.form-phone')}}</div>
                <input type="text" name="phone" value="{{$client->phone}}" />
              </label>
            </div>
            <div class="row">
              <label>
                <div class="label">{{__('messages.form-password')}}</div>
                <input type="text" name="password" />
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="notif-card">
        <h3 class="card-title">{{__('messages.notification-settings')}}</h3>
        <div class="section items">
          <div class="item">
            <div>
              <div class="item-title">{{__('messages.class-reminders')}}</div>
              <div class="item-sub">{{__('messages.class-reminders-d')}}</div>
            </div>
            <label class="switch">
              <input type="checkbox" name="class_reminders"
                @if ($client->settings->class_reminders)
                  @checked(true)
                @endif
              >
              <span class="slider"></span>
            </label>
          </div>
          <div class="item">
            <div>
              <div class="item-title">{{__('messages.payment-date')}}</div>
              <div class="item-sub">{{__('messages.payment-date-d')}}</div>
            </div>
            <label class="switch">
              <input type="checkbox" name="payment_date"
                @if ($client->settings->payment_date)
                  @checked(true)
                @endif
              >
              <span class="slider"></span>
            </label>
          </div>
          <div class="item">
            <div>
              <div class="item-title">{{__('messages.promotions')}}</div>
              <div class="item-sub">{{__('messages.promotions-d')}}</div>
            </div>
            <label class="switch">
              <input type="checkbox" name="promotions"
                @if ($client->settings->promotions)
                  @checked(true)
                @endif
              >
              <span class="slider"></span>
            </label>
          </div>
        </div>
        <div class="actions">
          <button type="submit" name="action" value="send" class="animated-button">
            <svg class="arr-2" viewBox="0 0 24 24">
              <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
            </svg>
            <span class="text">Save changes</span>
            <span class="circle"></span>
            <svg class="arr-1" viewBox="0 0 24 24">
              <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
            </svg>
          </button>
        </div>
      </div>
    </form>
  </main>
  <script src="{{asset("js/Website/Dashboard/pages/settings.js")}}"></script>
@endsection
