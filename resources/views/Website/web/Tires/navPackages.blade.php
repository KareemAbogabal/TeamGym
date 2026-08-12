<nav>
  <img src="{{asset("images/header/Team-Gym.png")}}" alt="No Img Logo">
  <main>
    <ul>
      <li><a href="#section-1" class="btn-sec active">{{__('messages.nav-home')}}</a></li>
      <li><a href="#section-2" class="btn-sec">{{__('messages.nav-systems')}}</a></li>
    </ul>
    @if (Cookie::has('login_client'))
      <button type="button" class="btn-profile"><img src="{{$client->img ? asset('images/subscribers/' . $client->img) : asset('images/header/Team-Gym.png')}}" alt="No Img Logo"></button>
    @else
      <a href="{{route("loginPage")}}" class="login">{{__('messages.nav-login')}}</a>
    @endif
  </main>
  <label class="bar" for="check">
    <input type="checkbox" id="check">
    <span class="top"></span>
    <span class="middle"></span>
    <span class="bottom"></span>
  </label>
</nav>
