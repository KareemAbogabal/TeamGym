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
        <svg width="40" height="40" viewBox="0 0 508 508" xml:space="preserve">
            <g>
              <path style="fill:#FFD05B;" d="M308,260.4C308.4,260.8,308.4,260.8,308,260.4L308,260.4z"/>
              <path style="fill:#FFD05B;" d="M200,260.4C200,260.8,199.6,260.8,200,260.4L200,260.4z"/>
              <path style="fill:#FFD05B;" d="M361.6,161.2c-2.4-23.2-31.6-34.4-31.6-34.4c-11.6-28.4-36-13.2-36-13.2c-4.4-6-14.4-4.4-14.4-4.4
                c-12.4-2-11.6-7.6-11.6-7.6h-14l0,0h-14.4c0,0,0.8,5.6-11.6,7.6c0,0-10-1.6-14.4,4.4c0,0-24.4-15.2-36,13.2
                c0,0-28.8,11.6-31.6,34.4c0,0-5.2,10,3.6,12.4c0,0,12.8,26.8,51.6,32c0,0,5.2,7.6,16.4,9.6c0,0-0.8,1.2-1.6,3.2
                c-0.4,1.2-0.8,2.4-1.2,3.6c-0.8,3.2-1.6,6.8-0.8,10.4c0,0-12.8,25.6-14.4,27.6c0.4-0.4,0.8-1.2,1.2-1.2
                c-0.8,1.6-22.4,41.2-9.2,64.4c0,0-4.8,18-2,25.2c0,0-12.4,18-9.6,61.6c0,0,0,37.2-11.2,48c0,0-1.6,4.4-5.6,4.4c0,0-9.2,8,7.6,6
                l13.2,0.4c0,0,3.6-8,3.6-10.4c0,0,10.4-1.6,12-6c0,0,0.8-2-3.6-11.6c0,0,0.4-28.4,6.4-37.6c0,0,14.8-12.8,10-31.6v-22.4
                c0,0,6.8-7.2,10-18c0,0,7.6-10.8,8.4-13.6c0,0,23.6-7.6,20.4-53.6h2.8l0,0h2c-3.2,46.4,20.4,54,20.4,54c0.8,3.2,8.4,13.6,8.4,13.6
                c3.2,10.8,10,18,10,18V372c-4.8,18.8,10,31.6,10,31.6c6,9.2,6.4,37.6,6.4,37.6c-4.4,9.6-3.6,11.6-3.6,11.6c1.6,4.4,12,6,12,6
                c0,2.4,3.6,10.4,3.6,10.4l13.2-0.4c16.8,2,7.6-6,7.6-6c-4,0-5.6-4.4-5.6-4.4c-11.2-10.8-11.2-48-11.2-48c2.8-44-9.6-61.6-9.6-61.6
                c2.8-7.6-2-25.2-2-25.2c13.2-22.8-8.4-62.8-9.2-64.4c0,0,0.8,0.8,1.2,1.2c-1.6-2-14.4-27.6-14.4-27.6c0.8-3.6,0.4-7.6-0.8-10.4
                c-0.4-1.2-0.8-2.8-1.2-3.6c-0.8-2-1.6-3.2-1.6-3.2c11.2-2,16.4-9.6,16.4-9.6c39.2-5.2,51.6-32,51.6-32
                C366.8,171.2,361.6,161.2,361.6,161.2z M212.8,195.6c-6.8-3.2-25.2-18-25.2-18c0-0.8-0.4-2-0.8-3.6l0,0c-1.2-3.2-2.8-7.6-2.8-7.6
                c0.8,0,2-0.8,3.6-1.6c2.4-1.6,5.2-3.6,5.2-3.6c1.6,9.2,17.2,19.2,17.2,19.2c3.2,3.6,7.6,14.4,7.6,14.4L212.8,195.6z M321.6,173.6
                L321.6,173.6c-0.4,1.6-0.8,3.2-0.8,3.6c0,0-18.8,14.8-25.2,18l-4.4-0.8c0,0,4.4-10.8,7.6-14.4c0,0,15.2-10.4,17.2-19.2
                c0,0,2.8,2,5.2,3.6c1.6,0.8,2.8,1.6,3.6,1.6C324,166,322.4,170.4,321.6,173.6z"/>
            </g>
            <g>
              <path style="fill:#F9B54C;" d="M254,151.6v9.6l6.8-2.4c30,5.6,37.2-10,37.2-10C277.6,165.2,258,154.4,254,151.6z"/>
              <path style="fill:#F9B54C;" d="M254,178c0,0,20,7.2,24.8,2.8c0,0-7.2,7.6-24.8,1.6L254,178L254,178z"/>
              <path style="fill:#F9B54C;" d="M254,197.6c0,0,14.8,2,19.6-2.4c0,0-3.2,8.4-19.6,6.8V197.6z"/>
              <path style="fill:#F9B54C;" d="M300.8,154.4c0,0-0.8,14.4-4.4,20.4c0,0-2,10-3.6,12.8c0,0,4-15.6,0-21.2
                C292.4,166.4,299.6,172,300.8,154.4z"/>
              <path style="fill:#F9B54C;" d="M333.2,152.4c-2,3.6-9.2,16.4-11.6,21.2c1.2-3.2,2.8-7.6,2.8-7.6c-0.8,0-2-0.8-3.6-1.6
                C320.8,164.8,330.4,156.8,333.2,152.4z"/>
              <path style="fill:#F9B54C;" d="M333.6,151.6c0,0.4-0.4,0.4-0.4,0.8C333.6,152,333.6,151.6,333.6,151.6z"/>
              <path style="fill:#F9B54C;" d="M254,129.2c0,0,11.6-11.2,24.8-12c0,0-19.2-1.2-24.4,8L254,129.2z"/>
              <path style="fill:#F9B54C;" d="M286.8,280.4c-0.4,0,26,21.2,25.2,43.6c0,0,2-42.4-3.6-47.6c0,0,2.4,13.2,1.2,24
                C309.6,300,295.6,282,286.8,280.4z"/>
              <path style="fill:#F9B54C;" d="M286,300c0,0-5.2,36.4,13.6,38.4c0,0,10.4-6,12-11.6c0,0-2,11.6-14.4,14.4
                C297.2,341.2,278,335.6,286,300z"/>
              <path style="fill:#F9B54C;" d="M338.4,140.8c0,0,24.4,12.8,11.2,25.6C350,166.4,357.2,154,338.4,140.8z"/>
              <path style="fill:#F9B54C;" d="M347.6,176c0,0-18.4,17.6-35.2,20C312,196,328.4,198.4,347.6,176z"/>
              <path style="fill:#F9B54C;" d="M303.6,113.6c0,0,11.2,7.6,10,19.6C313.6,133.2,310.4,118.4,303.6,113.6z"/>
              <path style="fill:#F9B54C;" d="M254,151.6v9.6l-6.8-2.4c-30,5.6-37.2-10-37.2-10C230.4,165.2,250,154.4,254,151.6z"/>
              <path style="fill:#F9B54C;" d="M254,178c0,0-20,7.2-24.8,2.8c0,0,7.2,7.6,24.8,1.6V178z"/>
              <path style="fill:#F9B54C;" d="M254,197.6c0,0-14.8,2-19.6-2.4c0,0,3.2,8.4,19.6,6.8V197.6z"/>
              <path style="fill:#F9B54C;" d="M207.2,154.4c0,0,0.8,14.4,4.4,20.4c0,0,2,10,3.6,12.8c0,0-4-15.6,0-21.2
                C215.6,166.4,208.4,172,207.2,154.4z"/>
              <path style="fill:#F9B54C;" d="M174.8,152.4c2,3.6,9.2,16.4,11.6,21.2c-1.2-3.2-2.8-7.6-2.8-7.6c0.8,0,2-0.8,3.6-1.6
                C187.2,164.8,177.6,156.8,174.8,152.4z"/>
              <path style="fill:#F9B54C;" d="M174.4,151.6c0,0.4,0.4,0.4,0.4,0.8C174.4,152,174.4,151.6,174.4,151.6z"/>
              <path style="fill:#F9B54C;" d="M254,129.2c0,0-11.6-11.2-24.8-12c0,0,19.2-1.2,24.4,8L254,129.2z"/>
              <path style="fill:#F9B54C;" d="M221.2,280.4c0.4,0-26,21.2-25.2,43.6c0,0-2-42.4,3.6-47.6c0,0-2.4,13.2-1.2,24
                C198.4,300,212.4,282,221.2,280.4z"/>
              <path style="fill:#F9B54C;" d="M222,300c0,0,5.2,36.4-13.6,38.4c0,0-10.4-6-12-11.6c0,0,2,11.6,14.4,14.4
                C210.8,341.2,230,335.6,222,300z"/>
              <path style="fill:#F9B54C;" d="M169.6,140.8c0,0-24.4,12.8-11.2,25.6C158,166.4,150.8,154,169.6,140.8z"/>
              <path style="fill:#F9B54C;" d="M160.4,176c0,0,18.4,17.6,35.2,20C196,196,179.6,198.4,160.4,176z"/>
              <path style="fill:#F9B54C;" d="M204.4,113.6c0,0-11.2,7.6-10,19.6C194.4,133.2,197.6,118.4,204.4,113.6z"/>
              <path style="fill:#F9B54C;" d="M306.4,367.2c0,0,4,18.4-3.2,29.2C302.8,396.4,313.2,389.2,306.4,367.2z"/>
              <path style="fill:#F9B54C;" d="M198.8,367.2c0,0-4,18.4,3.2,29.2C202.4,396.4,192,389.2,198.8,367.2z"/>
            </g>
            <path style="fill:#FF7058;" d="M292.4,230.4c-11.2,4-66,4-77.2,0c-0.8-0.4-2,0-2.4,1.2l0,0c-0.4,0.8,0,1.6,0.8,2.4
              c11.6,8.4,36.8,32,38.8,34.4h2.8c2-2.4,27.2-26,38.8-34.4c0.8-0.4,1.2-1.6,0.8-2.4l0,0C294.4,230.4,293.6,230,292.4,230.4z"/>
            <g>
              <path style="fill:#FFD05B;" d="M276.8,62c0,16.4-10.4,36.4-22.8,36.4c-12.8,0-22.8-20-22.8-36.4s10.4-22.4,22.8-22.4
                C266.8,39.2,276.8,45.6,276.8,62z"/>
              <path style="fill:#FFD05B;" d="M266.4,84.4H254h-12.4c0,0,0.8,19.2-6,22.4H254h18.4C265.6,103.6,266.4,84.4,266.4,84.4z"/>
              <path style="fill:#FFD05B;" d="M278,74.8c-2,2.8-5.2,4-7.2,2.4c-1.6-1.6-1.2-4.8,1.2-7.6c2-2.8,5.2-3.6,6.8-2.4
                C280.4,68.8,280,72,278,74.8z"/>
              <path style="fill:#FFD05B;" d="M230,74.8c2,2.8,5.2,4,7.2,2.4c1.6-1.6,1.2-4.8-1.2-7.6c-2-2.8-5.2-3.6-6.8-2.4
                C227.6,68.8,228,72,230,74.8z"/>
            </g>
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
