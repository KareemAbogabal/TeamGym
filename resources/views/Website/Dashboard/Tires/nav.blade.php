<nav>
  <div class="img-and-menu">
    <img src="{{asset("images/header/Team-Gym.png")}}" alt="No Img Logo">
    <div class="menu">
      <label class="menu-label">
        <input type="checkbox" />
        <span class="bar bar-1"></span>
        <span class="bar bar-2"></span>
        <span class="bar bar-3"></span>
      </label>
    </div>
  </div>
  <form action="" method="post" class="form-search">
    <input type="text" class="search"placeholder="{{__('messages.search-thing')}}">
    <button type="submit">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
        <path fill="var(--colorAverage)" d="M10 4a7 7 0 1 0 4.95 11.95l4.5 4.5a1 1 0 0 0 1.42-1.42l-4.5-4.5A7 7 0 0 0 10 4zm0 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10z"/>
      </svg>
    </button>
  </form>
  <div class="search-card">
    <div class="searched">
      <div class="msg">
        <p>No search found</p>
      </div>
    </div>
    <hr />
    <div class="result-search">
      <div class="msg">
        <p>No search found</p>
      </div>
    </div>
  </div>
  <div class="account-main">
    <button type="button" @if (!empty($notifications) && $notifications->isNotEmpty() && optional($client->settings)->class_reminders == true) class="notification-btn there-is" @else class="notification-btn" @endif>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="100%" height="100%" aria-hidden="true">
        <path d="M12 2a3 3 0 0 0-3 3v1.3C7.5 7.1 6 9.6 6 12v3l-1 1v1h14v-1l-1-1v-3c0-2.4-1.5-4.9-3-5.7V5a3 3 0 0 0-3-3z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M9.5 18.6 A2.5 2.5 0 0 0 14.5 18.6 L14.5 18.6 Z" fill="currentColor"/>
      </svg>
      @if (!empty($notifications) && $notifications->isNotEmpty() && optional($client->settings)->class_reminders == true)
        <span class="notification-badge">{{ $notifications->count() }}</span>
      @endif
    </button>
    <div class="notifications">
      <div class="notifications__header">
        <span class="notifications__title">{{ __('messages.notifications') }}</span>
        @if (!empty($notifications) && $notifications->isNotEmpty())
          <span class="notifications__count">{{ $notifications->count() }}</span>
        @endif
      </div>
      <div class="notifications__list">
        @if (optional($client->settings)->class_reminders == true)
          @if (!empty($notifications) && $notifications->isNotEmpty())
            @foreach ($notifications as $item)
              <div class="notification">
                <div class="notification__icon">{!!$item->icon!!}</div>
                <div class="notification__body">
                  <h1>{{$item->name}}</h1>
                  <p>{{$item->description}}</p>
                </div>
              </div>
            @endforeach
          @else
            <div class="notifications__empty">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <span>{{ __('messages.no_notifications') }}</span>
            </div>
          @endif
        @endif
      </div>
    </div>
    <div class="account-icon">
      <img src="{{$client->img ? asset('images/subscribers/' . $client->img) : asset('images/header/Team-Gym.png')}}" alt="No Img Logo">
      <div class="content">
        <h1>{{$client->fname}} {{$client->lname}}
          @if ($client->documentation == "true")
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" width="20" height="20">
              <defs>
                <path id="tooth" d="M 0,-110 C 5,-106 10,-98 14,-84 L 6,-62 C 3,-56 0,-54 0,-54 C 0,-54 -3,-56 -6,-62 L -14,-84 C -10,-98 -5,-106 0,-110 Z" />
                <linearGradient id="yellow-white-yellow-45" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stop-color="#f6d93b"/>
                  <stop offset="50%" stop-color="#ffffff"/>
                  <stop offset="100%" stop-color="#f6d93b"/>
                </linearGradient>
              </defs>
              <g transform="translate(128 128)">
                <g fill="url(#yellow-white-yellow-45)" stroke-linejoin="round" transform="scale(1.02)">
                  <use href="#tooth" transform="rotate(0)"/>
                  <use href="#tooth" transform="rotate(10)"/>
                  <use href="#tooth" transform="rotate(20)"/>
                  <use href="#tooth" transform="rotate(30)"/>
                  <use href="#tooth" transform="rotate(40)"/>
                  <use href="#tooth" transform="rotate(50)"/>
                  <use href="#tooth" transform="rotate(60)"/>
                  <use href="#tooth" transform="rotate(70)"/>
                  <use href="#tooth" transform="rotate(80)"/>
                  <use href="#tooth" transform="rotate(90)"/>
                  <use href="#tooth" transform="rotate(100)"/>
                  <use href="#tooth" transform="rotate(110)"/>
                  <use href="#tooth" transform="rotate(120)"/>
                  <use href="#tooth" transform="rotate(130)"/>
                  <use href="#tooth" transform="rotate(140)"/>
                  <use href="#tooth" transform="rotate(150)"/>
                  <use href="#tooth" transform="rotate(160)"/>
                  <use href="#tooth" transform="rotate(170)"/>
                  <use href="#tooth" transform="rotate(180)"/>
                  <use href="#tooth" transform="rotate(190)"/>
                  <use href="#tooth" transform="rotate(200)"/>
                  <use href="#tooth" transform="rotate(210)"/>
                  <use href="#tooth" transform="rotate(220)"/>
                  <use href="#tooth" transform="rotate(230)"/>
                  <use href="#tooth" transform="rotate(240)"/>
                  <use href="#tooth" transform="rotate(250)"/>
                  <use href="#tooth" transform="rotate(260)"/>
                  <use href="#tooth" transform="rotate(270)"/>
                  <use href="#tooth" transform="rotate(280)"/>
                  <use href="#tooth" transform="rotate(290)"/>
                  <use href="#tooth" transform="rotate(300)"/>
                  <use href="#tooth" transform="rotate(310)"/>
                  <use href="#tooth" transform="rotate(320)"/>
                  <use href="#tooth" transform="rotate(330)"/>
                  <use href="#tooth" transform="rotate(340)"/>
                  <use href="#tooth" transform="rotate(350)"/>
                  <circle r="92" fill="url(#yellow-white-yellow-45)" stroke-width="1.4"/>
                </g>
                <path d="M -34 0 L -4 40 L 56 -20" fill="none" transform="translate(-15, -6)" stroke="#000" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
              </g>
            </svg>
          @endif
        </h1>
        <p>{{$client->category}}</p>
      </div>
      <button type="button" class="option-btn">
        <svg width="40" height="40" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="32" cy="16" r="4" fill="rgb(116, 116, 116)" />
          <circle cx="32" cy="32" r="4" fill="rgb(116, 116, 116)" />
          <circle cx="32" cy="48" r="4" fill="rgb(116, 116, 116)" />
        </svg>
      </button>
      <div class="options">
        <button type="button"><i class="fa-solid fa-credit-card"></i></button>
        <button type="button" class="coach-request-option" data-follow="coach-request" aria-label="{{ __('messages.request') }}"><i class="fa-solid fa-dumbbell"></i></button>
      </div>
    </div>
  </div>
  <div class="menu-list">
    <input class="checkbox" type="checkbox" />
    <svg fill="none" viewBox="0 0 50 50" height="50" width="50">
      <path class="lineTop line" stroke-linecap="round" stroke-width="4" stroke="black" d="M6 11L44 11"></path>
      <path stroke-linecap="round" stroke-width="4" stroke="black" d="M6 24H43" class="lineMid line"></path>
      <path stroke-linecap="round" stroke-width="4" stroke="black" d="M6 37H43" class="lineBottom line"></path>
    </svg>
  </div>
</nav>
