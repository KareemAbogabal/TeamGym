<nav>
  <img src="{{asset("images/header/Team-Gym.png")}}" alt="No Img Logo">
  <div class="search">
    <input type="text" class="inp-search" placeholder="{{__('messages.search-thing')}}">
    <button type="submit" class="btn-search">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
        <path fill="var(--colorAverage)" d="M10 4a7 7 0 1 0 4.95 11.95l4.5 4.5a1 1 0 0 0 1.42-1.42l-4.5-4.5A7 7 0 0 0 10 4zm0 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10z"/>
      </svg>
    </button>
  </div>
  <main>
    <ul>
      <li><a href="#section-1" class="btn-sec active">{{__('messages.nav-home')}}</a></li>
      <li><a href="#section-1" class="btn-sec">{{__('messages.nav-store')}}</a></li>
    </ul>
    <div class="buttons">
      <button class="basket">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 6c-0.5 0-1 0.4-1 0.9l1.5 9.1c0 0.5 0.4 0.9 0.9 0.9h13c0.5 0 0.9-0.4 0.9-0.9L21 6.9c0-0.5-0.4-0.9-0.9-0.9H6z"></path>
          <circle cx="3" cy="3" r="1.5"></circle>
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="18" cy="21" r="1"></circle>
        </svg>
      </button>
      @if (Cookie::has('login_client'))
        <button type="button" class="btn-profile"><img src="{{$client->img ? asset('images/subscribers/' . $client->img) : asset('images/header/Team-Gym.png')}}" alt="No Img Logo"></button>
      @else
        <a href="{{route("loginPage")}}" class="login">{{__('messages.nav-login')}}</a>
      @endif
    </div>
  </main>
  <label class="bar" for="check">
    <input type="checkbox" id="check">
    <span class="top"></span>
    <span class="middle"></span>
    <span class="bottom"></span>
  </label>
</nav>
