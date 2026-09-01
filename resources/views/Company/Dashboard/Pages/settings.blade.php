@extends('Company.Dashboard.homePageCompany')

@section('title', "Settings")

@section('class', "settings")

@section('content')
  <main>
    <form action="{{route("updateEmployeeProfile")}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="profile-card">
        <div class="profile-card-inner">
          <div class="avatar-wrap">
            <div class="avatar-frame">
              <img id="profileImage" src="{{optional($employee)->img ? asset('images/employee/' . optional($employee)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img Logo" loading="lazy">
              <label for="imageInput" id="cameraBtn" class="camera-btn" aria-label="Upload photo">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden>
                  <path d="M5 7h2l1-2h8l1 2h2v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7z" stroke="var(--colorPearntSection)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                  <circle cx="12" cy="13" r="3" stroke="var(--colorPearntSection)" stroke-width="1.2"/>
                </svg>
              </label>
              <input id="imageInput" name="image" type="file" accept="image/*" style="display:none" />
            </div>
            <button type="submit" name="action" value="removePhoto" class="remove-photo tg-btn tg-btn--danger">{{__('messages.remove-photo')}}</button>
          </div>
          <div class="form-wrap">
            <div class="row two-cols">
              <label>
                <div class="label">{{__('messages.form-fname')}}</div>
                <input type="text" name="fname" value="{{$employee->fname}}" />
              </label>
              <label>
                <div class="label">{{__('messages.form-lname')}}</div>
                <input type="text" name="lname" value="{{$employee->lname}}" />
              </label>
            </div>
            <div class="row two-cols">
              <label>
                <div class="label">{{__('messages.form-email')}}</div>
                <input type="text" name="email" value="{{$employee->email}}" />
              </label>
              <label>
                <div class="label">{{__('messages.form-phone')}}</div>
                <input type="text" name="phone" value="{{$employee->phone}}" />
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
          @if (strtolower($employee->job_role ?? '') == "admin")
            <div class="item">
              <div>
                <div class="item-title">{{__('messages.view-log')}}</div>
                <div class="item-sub">{{__('messages.view-log-d')}}</div>
              </div>
              <label class="switch">
                <input type="checkbox" name="view_logs_logins"
                  @if ($employee->settingAdmin->view_logs_logins)
                    @checked(true)
                  @endif
                >
                <span class="slider"></span>
              </label>
            </div>
            <div class="item">
              <div>
                <div class="item-title">{{__('messages.supplements-requests')}}</div>
                <div class="item-sub">{{__('messages.supplements-requests-d')}}</div>
              </div>
              <label class="switch">
                <input type="checkbox" name="supplements_requests"
                  @if ($employee->settingAdmin->supplements_requests)
                    @checked(true)
                  @endif
                >
                <span class="slider"></span>
              </label>
            </div>
            <div class="item">
              <div>
                <div class="item-title">{{__('messages.subscription-requests')}}</div>
                <div class="item-sub">{{__('messages.subscription-requests-d')}}</div>
              </div>
              <label class="switch">
                <input type="checkbox" name="subscription_requests"
                  @if ($employee->settingAdmin->subscription_requests)
                    @checked(true)
                  @endif
                >
                <span class="slider"></span>
              </label>
            </div>
            <div class="item">
              <div>
                <div class="item-title">{{__('messages.add-employees')}}</div>
                <div class="item-sub">{{__('messages.add-employees-d')}}</div>
              </div>
              <label class="switch">
                <input type="checkbox" name="add_employees"
                  @if ($employee->settingAdmin->add_employees)
                    @checked(true)
                  @endif
                >
                <span class="slider"></span>
              </label>
            </div>
            <div class="item">
              <div>
                <div class="item-title">{{__('messages.subscription-form')}}</div>
                <div class="item-sub">{{__('messages.subscription-form-d')}}</div>
              </div>
              <label class="switch">
                <input type="checkbox" name="subscription_application_form"
                  @if ($employee->settingAdmin->subscription_application_form)
                    @checked(true)
                  @endif
                >
                <span class="slider"></span>
              </label>
            </div>
          @endif
          <div class="item">
            <div>
              <div class="item-title">{{__('messages.class-reminders')}}</div>
              <div class="item-sub">{{__('messages.class-reminders-d')}}</div>
            </div>
            <label class="switch">
              <input type="checkbox" name="class_reminders"
                @if ($employee->setting->class_reminders)
                  @checked(true)
                @endif
              >
              <span class="slider"></span>
            </label>
          </div>
          <div class="item">
            <div>
              <div class="item-title">{{__('messages.login-alerts')}}</div>
              <div class="item-sub">{{__('messages.login-alerts-d')}}</div>
            </div>
            <label class="switch">
              <input type="checkbox" name="login_alerts"
                @if ($employee->setting->login_alerts)
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
            <span class="text">{{__('messages.save-changes')}}</span>
            <span class="circle"></span>
            <svg class="arr-1" viewBox="0 0 24 24">
              <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
            </svg>
          </button>
        </div>
      </div>
    </form>
  </main>
  <script src="{{asset("js/Company/pages/settings.js")}}"></script>
@endsection
