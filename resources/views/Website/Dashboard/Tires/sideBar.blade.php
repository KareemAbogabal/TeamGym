<div class="side-bar">
  <ul>
    <li>
      <a href="{{route("dashboard")}}">
        <svg width="40" height="40" viewBox="0 -2 64 64">
          <rect x="8" y="8" width="18" height="18" rx="4" fill="var(--colorSVG2)"/>
          <rect x="30" y="8" width="18" height="18" rx="4" fill="var(--colorSVG1)"/>
          <rect x="8" y="30" width="18" height="18" rx="4" fill="var(--colorSVG1)"/>
          <rect x="30" y="30" width="18" height="18" rx="4" fill="var(--colorSVG2)"/>
        </svg><p class="text-link">{{__('messages.dashboard')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("schedule")}}">
        <svg width="40" height="40" viewBox="0 0 64 64" fill="none">
          <rect x="11" y="8" width="38" height="48" rx="4" ry="4" fill="var(--colorSVG1)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="17" y="16" width="20" height="4" rx="2" ry="2" fill="var(--colorSVG2)"/>
          <rect x="17" y="26" width="20" height="4" rx="2" ry="2" fill="var(--colorSVG2)"/>
          <rect x="17" y="36" width="20" height="4" rx="2" ry="2" fill="var(--colorSVG2)"/>
          <rect x="17" y="46" width="20" height="4" rx="2" ry="2" fill="var(--colorSVG2)"/>
          <circle cx="43" cy="18" r="2" fill="var(--colorSVG2)"/>
          <circle cx="43" cy="28" r="2" fill="var(--colorSVG2)"/>
          <circle cx="43" cy="38" r="2" fill="var(--colorSVG2)"/>
          <circle cx="43" cy="48" r="2" fill="var(--colorSVG2)"/>
        </svg><p class="text-link">{{__('messages.class-schedule')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("plans")}}">
        <svg width="40" height="40" viewBox="0 -3 64 64" fill="none">
          <rect x="8"  y="12" width="28" height="4" rx="2" fill="var(--colorSVG1)" />
          <rect x="8"  y="26" width="28" height="4" rx="2" fill="var(--colorSVG1)" />
          <rect x="8"  y="40" width="28" height="4" rx="2" fill="var(--colorSVG1)" />
          <circle cx="48" cy="14" r="4" fill="var(--colorSVG2)" />
          <circle cx="48" cy="28" r="4" fill="var(--colorSVG2)" />
          <circle cx="48" cy="42" r="4" fill="var(--colorSVG2)" />
        </svg><p class="text-link">{{__('messages.plans-subs')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("health")}}">
        <svg width="40" height="40" viewBox="1 -7 64 64" fill="none">
          <path d="M32,12 C14,0 0,20 32,44 C64,20 50,0 32,12 Z" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <polyline points="16,26 24,26 28,20 32,32 36,18 40,28 48,28" fill="none" stroke="var(--colorSVG1)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg><p class="text-link">{{__('messages.health-tracking')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("burnMeter")}}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="-3 -5 90 80" width="40" height="40">
          <defs>
            <linearGradient id="gradOuter" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#ffb347"/>
              <stop offset="1" stop-color="#ff7043"/>
            </linearGradient>
            <linearGradient id="gradMid" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#ffd54a"/>
              <stop offset="1" stop-color="#ff6e40"/>
            </linearGradient>
            <linearGradient id="gradInner" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#fff176"/>
              <stop offset="1" stop-color="#ffca28"/>
            </linearGradient>
          </defs>
          <g transform="translate(40 40) scale(1.15) translate(-40 -40)">
            <g id="bigFlame" transform="rotate(-12 40 40)">
              <path d="M40 6 C24 22, 20 30, 20 38 C20 48, 32 56, 40 58 C48 56, 60 48, 60 38 C60 30, 56 22, 40 6 Z" fill="url(#gradOuter)"/>
              <path d="M40 14 C30 26, 28 32, 28 38 C28 46, 34 52, 40 54 C46 52, 52 46, 52 38 C52 32, 50 26, 40 14 Z" fill="url(#gradMid)"/>
              <path d="M40 22 C36 30, 36 34, 36 38 C36 44, 38 48, 40 50 C42 48, 44 44, 44 38 C44 34, 42 30, 40 22 Z" fill="url(#gradInner)"/>
            </g>
            <g transform="translate(10,6) scale(0.78) rotate(10 40 40)">
              <path d="M40 6 C24 22, 20 30, 20 38 C20 48, 32 56, 40 58 C48 56, 60 48, 60 38 C60 30, 56 22, 40 6 Z" fill="url(#gradOuter)"/>
              <path d="M40 14 C30 26, 28 32, 28 38 C28 46, 34 52, 40 54 C46 52, 52 46, 52 38 C52 32, 50 26, 40 14 Z" fill="url(#gradMid)"/>
              <path d="M40 22 C36 30, 36 34, 36 38 C36 44, 38 48, 40 50 C42 48, 44 44, 44 38 C44 34, 42 30, 40 22 Z" fill="url(#gradInner)"/>
            </g>
          </g>
        </svg><p class="text-link">Burn Meter</p>
      </a>
    </li>
    <li>
      <a href="{{route("supplementStore")}}">
        <svg width="40" height="40" viewBox="0 0 64 64" fill="none">
          <rect x="18" y="8" width="28" height="5" rx="4" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="8" y="18" width="48" height="40" rx="8" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <text x="32" y="43" text-anchor="middle" fill="var(--colorSVG1)" font-family="Arial, sans-serif" font-size="14" font-weight="bold">WHAY</text>
        </svg><p class="text-link">{{__('messages.supplement-store')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("coach")}}">
        <svg width="40" height="40" viewBox="0 0 64 64" fill="none">
          <path d="M20 8 L44 8 L54 18 L44 28 L20 28 L10 18 Z" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="26" y="8" width="12" height="46" rx="3" fill="var(--colorSVG1)"/>
          <rect x="24" y="50" width="16" height="6" rx="2" fill="var(--colorSVG1)"/>
        </svg><p class="text-link">{{__('messages.coach')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("settings")}}">
        <svg width="40" height="40" viewBox="0 8 64 64" fill="none">
          <g fill="var(--colorSVG1)">
            <path d="M32 20c1.1 0 2 .9 2 2v2a15 15 0 0 1 4.2 1.6l1.4-1.4a2 2 0 0 1 2.8 0l2.5 2.5a2 2 0 0 1 0 2.8l-1.4 1.4a15 15 0 0 1 1.6 4.2h2a2 2 0 0 1 2 2v3.5a2 2 0 0 1-2 2h-2a15 15 0 0 1-1.6 4.2l1.4 1.4a2 2 0 0 1 0 2.8l-2.5 2.5a2 2 0 0 1-2.8 0l-1.4-1.4a15 15 0 0 1-4.2 1.6v2a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-2a15 15 0 0 1-4.2-1.6l-1.4 1.4a2 2 0 0 1-2.8 0l-2.5-2.5a2 2 0 0 1 0-2.8l1.4-1.4a15 15 0 0 1-1.6-4.2h-2a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2h2a15 15 0 0 1 1.6-4.2l-1.4-1.4a2 2 0 0 1 0-2.8l2.5-2.5a2 2 0 0 1 2.8 0l1.4 1.4a15 15 0 0 1 4.2-1.6v-2c0-1.1.9-2 2-2h3.5z"/>
          </g>
          <circle cx="30.5" cy="38.5" r="6" fill="var(--colorSVG2)"/>
        </svg><p class="text-link">{{__('messages.settings')}}</p>
      </a>
    </li>
  </ul>
  <form action="{{route("logOutClient")}}" method="post">
    @csrf
    <button type="submit" class="logout">
      <svg width="36" height="40" viewBox="0 5 48 30" fill="none" preserveAspectRatio="xMidYMid meet">
        <path d="M8 4 H26 M8 4 V34 H26" stroke="var(--colorSVG1)" stroke-width="2" fill="none" stroke-linejoin="round"/>
        <path d="M22 20 H42 M34 14 L42 20 L34 26" stroke="var(--colorSVG2)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      </svg>{{__('messages.logout')}}
    </button>
  </form>
</div>
