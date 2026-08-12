<main class="main-form" data-follow="{{ $dataFollowButton }}">
  <img src="{{asset("images/content/records.jpeg")}}" alt="No Img Logo" loading="lazy">
  <div class="forms">
    <form action="{{route("record")}}" method="post" class="record-form">
      @csrf
      <h1>{{__('messages.attendance-record')}}</h1>
      <input name="fname" type="hidden" />
      <input name="lname" type="hidden" />
      <div class="main-input">
        <div class="nebula-input">
          <input type="text" name="full-name" id="fullname" autocomplete="off" class="input" />
          <label class="user-label">{{__('messages.form-full-name')}}</label>
        </div>
      </div>
      <div class="state-check-client">
      </div>
      <button type="submit">{{__('messages.record')}}</button>
    </form>
    <form action="{{route("signUpRecord")}}" method="post" class="hidden">
      @csrf
      <h1>{{__('messages.customer-registration')}}</h1>
      <div class="main-input">
        <div class="row-input">
          <div class="nebula-input">
            <input type="text" name="fname" class="input" />
            <label class="user-label">{{__('messages.form-fname')}}</label>
          </div>
          <div class="nebula-input">
            <input type="text" name="lname" class="input" />
            <label class="user-label">{{__('messages.form-lname')}}</label>
          </div>
        </div>
        <div class="nebula-input">
          <input type="text" name="phone" autocomplete="off" class="input" />
          <label class="user-label">{{__('messages.form-phone')}}</label>
        </div>
        <div class="nebula-input">
          <input type="text" name="email" autocomplete="off" class="input" />
          <label class="user-label">{{__('messages.form-email')}}</label>
        </div>
        <div class="nebula-input">
          <input type="text" name="password" autocomplete="off" class="input" />
          <label class="user-label">{{__('messages.form-password')}}</label>
        </div>
        <label class="container">
          <input type="checkbox" id="remember-1" />
          <svg viewBox="0 0 64 64" height="1em" width="1em">
            <path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16" pathLength="575.0541381835938" class="path"></path>
          </svg>
          <label for="remember-1">{{__('messages.form-remember-me')}}</label>
        </label>
        <button type="submit">{{__('messages.form-send')}}</button>
      </div>
    </form>
    <form action="{{route("addRequests")}}" method="post" class="hidden attachment-form">
      @csrf
      <h1>{{__('messages.register-request')}}</h1>
      <div class="main-input">
        <div class="row-input">
          <div class="nebula-input">
            <input type="text" name="fname" autocomplete="off" class="input" />
            <label class="user-label">{{__('messages.form-fname')}}</label>
          </div>
          <div class="nebula-input">
            <input type="text" name="lname" autocomplete="off" class="input" />
            <label class="user-label">{{__('messages.form-lname')}}</label>
          </div>
        </div>
        <input type="hidden" name="order_name" value="system" autocomplete="off" class="input attachment-name" />
        <input type="hidden" name="attachment" autocomplete="off" class="input attachment-input" />
        <div class="main-input">
          <div class="mydict">
            @if ($settingCompany->subscription_requests == true)
              <label class="radio-choose">
                <input type="radio" name="radio" checked="">
                <span class="text" data-name="system">{{__('messages.system')}}</span>
              </label>
            @endif
            @if ($settingCompany->supplements_requests == true)
              <label class="radio-choose">
                <input type="radio" name="radio">
                <span class="text" data-name="supplement">{{__('messages.supplement')}}</span>
              </label>
            @endif
          </div>
        </div>
        <div class="nebula-input">
          <input type="text" name="amount" autocomplete="off" class="input" />
          <label class="user-label">{{__('messages.form-amount')}}</label>
        </div>
        <div class="radio-input">
          @if ($supplements)
            @foreach ($supplements as $index => $item)
              <label class="label supplement-lable">
                <input type="radio" id="value-{{$index}}" name="value-radio" value="{{$item->code}}" />
                <p class="text" data-name="supplement">{{$item->name}}</p>
              </label>
            @endforeach
          @endif
        </div>
        <div class="main-input main-input-subscription">
          <div class="mydict">
            @if ($systems)
              @foreach ($systems as $index => $item)
                <label class="radio-system-attachment">
                  <input type="radio" value="{{$item->code}}" name="radio-system">
                  <span class="text" data-name="system">{{$item->name}}</span>
                </label>
              @endforeach
            @endif
          </div>
        </div>
        <label class="container">
          <input type="checkbox" id="remember-2" name="remember" />
          <svg viewBox="0 0 64 64" height="1em" width="1em">
            <path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16" pathLength="575.0541381835938" class="path"></path>
          </svg>
          <label for="remember-2">{{__('messages.form-remember-me')}}</label>
        </label>
        <button type="submit">{{__('messages.form-send')}}</button>
      </div>
    </form>
    <form action="{{route("registrationRequestsPayment")}}" method="post" class="hidden">
      @csrf
      <h1>{{__('messages.installment-registration')}}</h1>
      <div class="main-input">
        <div class="row-input">
          <div class="nebula-input">
            <input type="text" name="fname" autocomplete="off" class="input fname-installment" />
            <label class="user-label">{{__('messages.form-fname')}}</label>
          </div>
          <div class="nebula-input">
            <input type="text" name="lname" autocomplete="off" class="input lname-installment" />
            <label class="user-label">{{__('messages.form-lname')}}</label>
          </div>
        </div>
        <input type="hidden" name="attachment" value="system" autocomplete="off" class="input installment-name" />
        <div class="main-input">
          <div class="mydict">
            @if ($settingCompany->subscription_requests == true)
              <label class="radio-choose radio-choose-installment">
                <input type="radio" name="radio" checked="">
                <span class="text" data-name="system">{{__('messages.system')}}</span>
              </label>
            @endif
            @if ($settingCompany->supplements_requests == true)
              <label class="radio-choose radio-choose-installment">
                <input type="radio" name="radio">
                <span class="text" data-name="supplement">{{__('messages.supplement')}}</span>
              </label>
            @endif
          </div>
        </div>
        <div class="nebula-input">
          <input type="text" name="amount" autocomplete="off" class="input" />
          <label class="user-label">{{__('messages.form-amount')}}</label>
        </div>
        <div class="radio-input">
        </div>
        <div class="main-input main-input-subscription">
          <div class="mydict">
            @if ($systems)
              @foreach ($systems as $index => $item)
                <label class="radio-system-attachment">
                  <input type="radio" value="{{ $item->code }}" name="radio-system-installment">
                  <span class="text" data-name="system">{{ $item->name }}</span>
                </label>
              @endforeach
            @endif
          </div>
        </div>
        <label class="container">
          <input type="checkbox" id="remember-3" />
          <svg viewBox="0 0 64 64" height="1em" width="1em">
            <path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16" pathLength="575.0541381835938" class="path"></path>
          </svg>
          <label for="remember-3">{{__('messages.form-remember-me')}}</label>
        </label>
        <button type="submit">{{__('messages.form-send')}}</button>
      </div>
    </form>
  </div>
</main>
