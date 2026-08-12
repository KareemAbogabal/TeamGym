<div class="side-bar">
  <ul>
    <li>
      <a href="{{route("dashboardCompany")}}">
        <svg width="40" height="40" viewBox="0 -2 64 64">
          <rect x="8" y="8" width="18" height="18" rx="4" fill="var(--colorSVG2)"/>
          <rect x="30" y="8" width="18" height="18" rx="4" fill="var(--colorSVG1)"/>
          <rect x="8" y="30" width="18" height="18" rx="4" fill="var(--colorSVG1)"/>
          <rect x="30" y="30" width="18" height="18" rx="4" fill="var(--colorSVG2)"/>
        </svg><p class="text-link">{{__('messages.dashboard')}}</p>
      </a>
    </li>
    @can('admin')
      <li>
        <a href="{{route("users")}}">
          <svg width="40" height="40" viewBox="4 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="32" cy="20" r="10" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
            <path d="M10 48 C10 40 22 36 32 36 C42 36 54 40 54 48 V52 H10 V48 Z" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          </svg><p class="text-link">{{__('messages.users')}}</p>
        </a>
      </li>
      <li>
        <a href="{{route("history")}}" class="historys-link">
          <svg width="40" height="40" viewBox="4 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none">
            <defs>
              <mask id="cutQuarter">
                <rect width="100%" height="100%" fill="white"/>
                <path d="M32 32 L32 6 A26 26 0 0 1 58 32 L32 32 Z" fill="black"/>
              </mask>
              <clipPath id="circleClip">
                <circle cx="32" cy="32" r="26" />
              </clipPath>
            </defs>
            <circle cx="32" cy="32" r="26" stroke="var(--colorSVG1)" stroke-width="2" fill="none"/>
            <g mask="url(#cutQuarter)" clip-path="url(#circleClip)">
              <circle cx="32" cy="32" r="18" fill="var(--colorSVG2)" />
            </g>
          </svg><p class="text-link">{{__('messages.history')}}</p>
        </a>
      </li>
      <li>
        <a href="{{route("analytics")}}">
          <svg width="40" height="40" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true">
            <polyline points="10,40 22,28 34,36 48,20" fill="none" stroke="var(--colorSVG1)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="10" cy="40" r="3" fill="var(--colorSVG2)"/>
            <circle cx="22" cy="28" r="3" fill="var(--colorSVG2)"/>
            <circle cx="34" cy="36" r="3" fill="var(--colorSVG2)"/>
            <circle cx="48" cy="20" r="3" fill="var(--colorSVG2)"/>
          </svg><p class="text-link">{{__('messages.analytics')}}</p>
        </a>
      </li>
    @endcan
    <li>
      <a href="{{route("records")}}" class="records-link">
        <svg width="40" height="40" viewBox="4 5 64 64" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true">
          <rect x="12" y="14" width="18" height="10" rx="3" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="8" y="20" width="48" height="34" rx="4" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="16" y="28" width="32" height="3" rx="1.5" fill="var(--colorSVG1)"/>
          <rect x="16" y="34" width="26" height="3" rx="1.5" fill="var(--colorSVG1)"/>
          <rect x="16" y="40" width="20" height="3" rx="1.5" fill="var(--colorSVG1)"/>
          <g transform="translate(32,54) rotate(-45) scale(1.5)">
            <rect x="0" y="0" width="25" height="4" rx="1" fill="#ffffff"/>
            <rect x="12" y="0" width="3" height="4" rx="1" fill="#E5E7EB"/>
            <polygon points="0,0 0,4 -3,2" fill="#FBBF24"/>
          </g>
        </svg><p class="text-link">{{__('messages.records')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("requests")}}" class="requests-link">
        <svg width="16" height="20" viewBox="0 0 48 32" xmlns="http://www.w3.org/2000/svg" fill="none" preserveAspectRatio="xMidYMid meet">
          <rect x="2" y="2" width="40" height="28" rx="4" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="4" y="6" width="35" height="4" fill="var(--colorSVG1)"/>
          <rect x="6" y="14" width="6" height="6" rx="1" fill="var(--colorSVG1)"/>
          <rect x="16" y="16" width="4" height="2" fill="var(--colorSVG1)" />
          <rect x="22" y="16" width="4" height="2" fill="var(--colorSVG1)" />
          <rect x="28" y="16" width="4" height="2" fill="var(--colorSVG1)" />
          <rect x="34" y="16" width="4" height="2" fill="var(--colorSVG1)" />
        </svg><p class="text-link">{{__('messages.requests')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("contactUsCompany")}}" class="contact-us-link">
        <svg width="40" height="40" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true">
          <rect x="8" y="10" width="48" height="34" rx="8" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <path d="M22 44 L22 54 L32 44" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2" stroke-linejoin="round"/>
          <circle cx="24" cy="27" r="2.5" fill="var(--colorSVG1)"/>
          <circle cx="32" cy="27" r="2.5" fill="var(--colorSVG1)"/>
          <circle cx="40" cy="27" r="2.5" fill="var(--colorSVG1)"/>
        </svg><p class="text-link">{{__('messages.contact-us')}}</p>
      </a>
    </li>
    @can('coach')
      <li>
        <a href="{{route("exercise")}}">
          <svg width="40" height="40" viewBox="0 0 64 34" xmlns="http://www.w3.org/2000/svg" fill="none">
            <rect x="0"  y="6"  width="8" height="24" rx="3" ry="3" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
            <rect x="8" y="0"  width="8" height="34" rx="3" ry="3" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
            <rect x="16" y="14" width="32" height="8" rx="3" ry="3" fill="var(--colorSVG1)"/>
            <rect x="48" y="0"  width="8" height="34" rx="3" ry="3" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
            <rect x="55" y="6"  width="8" height="24" rx="3" ry="3" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
          </svg><p class="text-link">{{__('messages.exercises')}}</p>
        </a>
      </li>
    @endcan
    <li>
      <a href="{{route("publications")}}">
        <svg width="40" height="40" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none">
          <rect x="6" y="8" width="52" height="48" rx="4" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="10" y="14" width="22" height="26" rx="3" fill="var(--colorSVG1)"/>
          <rect x="18" y="20" width="10" height="14" rx="2" fill="var(--colorSVG2)"/>
          <rect x="19" y="18" width="8" height="3" rx="1" fill="var(--colorSVG2)"/>
          <rect x="20" y="26" width="6" height="2" rx="1" fill="var(--colorSVG1)"/>
          <rect x="20" y="30" width="6" height="2" rx="1" fill="var(--colorSVG1)"/>
          <rect x="34" y="16" width="18" height="4" rx="2" fill="var(--colorSVG1)"/>
          <rect x="34" y="24" width="14" height="3" rx="1.5" fill="var(--colorSVG1)"/>
          <rect x="34" y="29" width="10" height="3" rx="1.5" fill="var(--colorSVG1)"/>
          <g transform="translate(44,12)">
            <rect x="0" y="0" width="10" height="6" rx="2" fill="var(--colorSVG1)"/>
            <circle cx="8" cy="3" r="1.2" fill="var(--colorSVG2)"/>
          </g>
          <g transform="translate(42,36)">
            <rect x="0" y="0" width="12" height="6" rx="2" fill="none" stroke="var(--colorSVG1)" stroke-width="1"/>
            <circle cx="3" cy="3" r="1.2" fill="var(--colorSVG1)"/>
            <rect x="6" y="2" width="4" height="1.5" rx="0.8" fill="var(--colorSVG1)"/>
          </g>
          <rect x="10" y="44" width="40" height="3" rx="1.5" fill="var(--colorSVG1)"/>
          <rect x="10" y="49" width="32" height="3" rx="1.5" fill="var(--colorSVG1)"/>
        </svg><p class="text-link">{{__('messages.publications')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("imports")}}" class="imports-link">
        <svg width="40" height="40" viewBox="5 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none">
          <rect x="14" y="8" width="46" height="48" rx="3" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <line x1="20" y1="20" x2="54" y2="20" stroke="var(--colorSVG1)" stroke-width="2"/>
          <line x1="20" y1="28" x2="54" y2="28" stroke="var(--colorSVG1)" stroke-width="2"/>
          <line x1="20" y1="36" x2="46" y2="36" stroke="var(--colorSVG1)" stroke-width="2"/>
          <line x1="37" y1="2" x2="37" y2="14" stroke="var(--colorSVG1)" stroke-width="3" stroke-linecap="round"/>
          <polyline points="31,10 37,16 43,10" fill="none" stroke="var(--colorSVG1)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg><p class="text-link">{{__('messages.imports')}}</p>
      </a>
    </li>
    <li>
      <a href="{{route("settingsCompany")}}">
        <svg width="40" height="40" viewBox="0 8 64 64" fill="none">
          <g fill="var(--colorSVG1)">
            <path d="M32 20c1.1 0 2 .9 2 2v2a15 15 0 0 1 4.2 1.6l1.4-1.4a2 2 0 0 1 2.8 0l2.5 2.5a2 2 0 0 1 0 2.8l-1.4 1.4a15 15 0 0 1 1.6 4.2h2a2 2 0 0 1 2 2v3.5a2 2 0 0 1-2 2h-2a15 15 0 0 1-1.6 4.2l1.4 1.4a2 2 0 0 1 0 2.8l-2.5 2.5a2 2 0 0 1-2.8 0l-1.4-1.4a15 15 0 0 1-4.2 1.6v2a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-2a15 15 0 0 1-4.2-1.6l-1.4 1.4a2 2 0 0 1-2.8 0l-2.5-2.5a2 2 0 0 1 0-2.8l1.4-1.4a15 15 0 0 1-1.6-4.2h-2a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2h2a15 15 0 0 1 1.6-4.2l-1.4-1.4a2 2 0 0 1 0-2.8l2.5-2.5a2 2 0 0 1 2.8 0l1.4 1.4a15 15 0 0 1 4.2-1.6v-2c0-1.1.9-2 2-2h3.5z"/>
          </g>
          <circle cx="30.5" cy="38.5" r="6" fill="var(--colorSVG2)"/>
        </svg><p class="text-link">{{__('messages.settings')}}</p>
      </a>
    </li>
  </ul>
  <form action="{{route("logOutEmployee")}}" method="post">
    @csrf
    <button type="submit" class="logout">
      <svg width="36" height="40" viewBox="0 5 48 30" fill="none" preserveAspectRatio="xMidYMid meet">
        <path d="M8 4 H26 M8 4 V34 H26" stroke="var(--colorSVG1)" stroke-width="2" fill="none" stroke-linejoin="round"/>
        <path d="M22 20 H42 M34 14 L42 20 L34 26" stroke="var(--colorSVG2)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
      </svg>{{__('messages.logout')}}
    </button>
  </form>
</div>
