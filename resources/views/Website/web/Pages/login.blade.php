<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="google" content="notranslate">
  <link rel="icon" href="{{asset("images/header/Team-Gym.png")}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="{{asset("css/Website/web/public.css")}}">
  <link rel="stylesheet" href="{{asset("css/Website/web/pages/login.css")}}">
  <title>Team Gym | Login</title>
</head>
<body>
  <div class="warnings-container">
    @foreach ($errors->all() as $error)
      <x-components::warning :text="$error" />
    @endforeach
  </div>
  <main>
    <form action="{{route("signUp")}}" method="post">
      @csrf
      <h1>{{__('messages.sigin-up-h1')}}</h1>
      <div class="row">
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
        <input type="text" name="phone" class="input" />
        <label class="user-label">{{__('messages.form-phone')}}</label>
      </div>
      <div class="nebula-input">
        <input type="text" name="email" class="input" />
        <label class="user-label">{{__('messages.form-email')}}</label>
      </div>
      <div class="nebula-input">
        <input type="password" name="password" class="input" />
        <label class="user-label">{{__('messages.form-password')}}</label>
      </div>
      <label class="container">
        <input type="checkbox" id="remember" />
        <svg viewBox="0 0 64 64" height="1em" width="1em">
          <path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16" pathLength="575.0541381835938" class="path"></path>
        </svg>
        <label for="remember">{{__('messages.form-remember-me')}}</label>
      </label>
      <button type="submit">{{__('messages.form-send')}}</button>
    </form>
    <div class="card">
      <img src="{{asset("images/content/img-login.jpeg")}}" alt="No Img Login">
      <div class="content">
        <h1>{{__('messages.card-h1-friend')}}</h1>
        <p>{{__('messages.card-p-login')}}</p>
      </div>
      <button type="button" class="switch">
        <svg width="40px" height="40px" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M3.85355 2.14645C3.65829 1.95118 3.34171 1.95118 3.14645 2.14645C2.95118 2.34171 2.95118 2.65829 3.14645 2.85355L7.14645 6.85355C7.34171 7.04882 7.65829 7.04882 7.85355 6.85355L11.8536 2.85355C12.0488 2.65829 12.0488 2.34171 11.8536 2.14645C11.6583 1.95118 11.3417 1.95118 11.1464 2.14645L7.5 5.79289L3.85355 2.14645ZM3.85355 8.14645C3.65829 7.95118 3.34171 7.95118 3.14645 8.14645C2.95118 8.34171 2.95118 8.65829 3.14645 8.85355L7.14645 12.8536C7.34171 13.0488 7.65829 13.0488 7.85355 12.8536L11.8536 8.85355C12.0488 8.65829 12.0488 8.34171 11.8536 8.14645C11.6583 7.95118 11.3417 7.95118 11.1464 8.14645L7.5 11.7929L3.85355 8.14645Z"
            fill="#ffffff"
          />
        </svg>
        <p>{{__('messages.sigin-up-h1')}}</p>
      </button>
    </div>
    <form action="{{route("client.forget")}}" method="post" class="hidden">
      @csrf
      <h1>{{__('messages.login-h1')}}</h1>
      <div class="nebula-input">
        <input type="text" name="email" class="input" />
        <label class="user-label">{{__('messages.form-email')}}</label>
      </div>
      <button type="submit">{{__('messages.form-send')}}</button>
    </form>
    <form action="{{route("client.login")}}" method="post">
      @csrf
      <h1>{{__('messages.login-h1')}}</h1>
      <div class="nebula-input">
        <input type="text" name="email" class="input" />
        <label class="user-label">{{__('messages.form-email')}}</label>
      </div>
      <div class="nebula-input">
        <input type="text" name="password" class="input" />
        <label class="user-label">{{__('messages.form-password')}}</label>
      </div>
      <button type="button" class="forget">{{__('messages.form-forget-my-password')}}</button>
      <button type="submit" value="send">{{__('messages.form-send')}}</button>
    </form>
    <form action="{{route("client.verifyCode")}}" method="post" class="hidden">
      @csrf
      <h1>{{__('messages.form-verification-code')}}</h1>
      <div class="nebula-input">
        <input type="text" name="code" class="input code-input" maxlength="6" inputmode="numeric" pattern="[0-9]*" />
        <label class="user-label">{{__('messages.form-code')}}</label>
      </div>
      <button type="submit">{{__('messages.form-send')}}</button>
    </form>
    <form action="{{route("client.resetPassword")}}" method="post" class="hidden">
      @csrf
      <h1>{{__('messages.form-new-password')}}</h1>
      <div class="nebula-input">
        <input type="email" name="email" class="input" />
        <label class="user-label">{{__('messages.form-email')}}</label>
      </div>
      <div class="nebula-input">
        <input type="password" name="password" class="input" />
        <label class="user-label">{{__('messages.form-new-password')}}</label>
      </div>
      <div class="nebula-input">
        <input type="password" name="password_confirmation" class="input" />
        <label class="user-label">{{__('messages.form-confirm-password')}}</label>
      </div>
      <button type="submit">{{__('messages.form-send')}}</button>
    </form>
  </main>
  <script>
    let imgLogin = "{{asset('images/content/img-login.jpeg')}}";
    let imgSiginUp = "{{asset('images/content/img-sign-up.jpeg')}}";
    let h1Login = "{{__('messages.card-h1-friend')}}";
    let h1SiginUp = "{{__('messages.card-h1-buddy')}}";
    let pLogin = "{{__('messages.card-p-login')}}";
    let pSiginUp = "{{__('messages.card-p-sigin-up')}}";
    let btnLogin = "{{__('messages.card-button-login')}}";
    let btnSiginUp = "{{__('messages.card-button-sigin-up')}}";
  </script>
  <script src="{{asset("js/login.js")}}"></script>
  <script src="{{asset("js/warning.js")}}"></script>
</body>
</html>
