@extends('Company.Dashboard.homePageCompany')

@section('title', 'Coach Management')

@section('class', 'coach-management')

@section('content')
<main>
  <div class="content-table coach-head">
    <h1>{{ __('messages.coach-management') }}</h1>
    @if (\Illuminate\Support\Facades\Gate::forUser($employee)->allows('coach'))
      <button type="button" class="btn add-request coach-summon-trigger">
        <i class="fa-solid fa-user-plus"></i>
        {{ __('messages.coach-requests-client') }}
      </button>
    @endif
  </div>

  <x-components::main-card state="coach-summon" dataFollow="coach-summon">
    <div class="body-card">
      <div class="img">
        <img src="{{ $employee->img ? asset('images/employee/' . $employee->img) : asset('images/header/Team-Gym.png') }}" class="img-profile" alt="No Img" loading="lazy">
        <div class="content">
          <h1>{{ $employee->fname }} {{ $employee->lname }}</h1>
          <p>{{ $employee->job_role }}</p>
        </div>
        @if ($employee->documentation == "true")
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
                <use href="#tooth" transform="rotate(15)"/>
                <use href="#tooth" transform="rotate(30)"/>
                <use href="#tooth" transform="rotate(45)"/>
                <use href="#tooth" transform="rotate(60)"/>
                <use href="#tooth" transform="rotate(75)"/>
                <use href="#tooth" transform="rotate(90)"/>
                <use href="#tooth" transform="rotate(105)"/>
                <use href="#tooth" transform="rotate(120)"/>
                <use href="#tooth" transform="rotate(135)"/>
                <use href="#tooth" transform="rotate(150)"/>
                <use href="#tooth" transform="rotate(165)"/>
                <use href="#tooth" transform="rotate(180)"/>
                <use href="#tooth" transform="rotate(195)"/>
                <use href="#tooth" transform="rotate(210)"/>
                <use href="#tooth" transform="rotate(225)"/>
                <use href="#tooth" transform="rotate(240)"/>
                <use href="#tooth" transform="rotate(255)"/>
                <use href="#tooth" transform="rotate(270)"/>
                <use href="#tooth" transform="rotate(285)"/>
                <use href="#tooth" transform="rotate(300)"/>
                <use href="#tooth" transform="rotate(315)"/>
                <use href="#tooth" transform="rotate(330)"/>
                <use href="#tooth" transform="rotate(345)"/>
                <circle r="92" fill="url(#yellow-white-yellow-45)" stroke-width="1.4"/>
              </g>
              <path d="M -34 0 L -4 40 L 56 -20" fill="none" transform="translate(-15, -6)" stroke="#000" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
          </svg>
        @endif
      </div>
      <form method="post" action="{{ route('coachManagement.requestClient') }}">
        @csrf
        <div class="main-input">
          <label for="summon-client">{{ __('messages.choose-client') }}</label>
          <select id="summon-client" name="code_client" required>
            <option value="">{{ __('messages.choose-client') }}</option>
            @foreach ($clients as $c)
              <option value="{{ $c->code }}">{{ $c->fname }} {{ $c->lname }}</option>
            @endforeach
          </select>
        </div>
        <div class="main-input">
          <label for="summon-coach">{{ __('messages.select-coaches') }}</label>
          <select id="summon-coach" name="code_coach" required>
            <option value="">{{ __('messages.select-coaches-placeholder') }}</option>
            @foreach ($coaches as $c)
              <option value="{{ $c['employee']->code }}">{{ $c['employee']->fname }} {{ $c['employee']->lname }}@if($c['profile']?->specialization) — {{ $c['profile']->specialization }}@endif</option>
            @endforeach
          </select>
        </div>
        <div class="button-row-card">
          <div class="buttons">
            <button type="button" class="close-profile coach-summon-close">{{ __('messages.card-profile-button-close') }}</button>
            <button type="submit" class="view-profile">{{ __('messages.request') }}</button>
          </div>
        </div>
      </form>
    </div>
  </x-components::main-card>

  <div class="main-table coach-tables">
    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{ __('messages.pending-requests') }} — {{ __('messages.coach') }}</h1>
      </div>
      <x-components::table :header="[__('messages.name-client'), __('messages.coach'), __('messages.reason-optional'), __('messages.status'), __('messages.details')]">
        @forelse ($coachRequests as $r)
          <div class="row">
            <p class="search">{{ $r->client ? $r->client->fname . ' ' . $r->client->lname : '—' }}</p>
            <p>{{ $r->coach ? $r->coach->fname . ' ' . $r->coach->lname : '—' }}</p>
            <p>{{ $r->reason ?: '—' }}</p>
            <p data-state="request">{{ $r->status }}</p>
            <div class="content-row">
              <form method="post" action="{{ route('coachManagement.manage') }}">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $r->id }}" />
                <input type="hidden" name="note" value="" />
                <button type="submit" name="action" value="approve" class="btn success">{{ __('messages.approve') }}</button>
                <button type="submit" name="action" value="reject" class="btn danger">{{ __('messages.reject') }}</button>
              </form>
            </div>
          </div>
        @empty
          <div class="row"><p class="empty-row">{{ __('messages.no-pending-requests') }}</p></div>
        @endforelse
      </x-components::table>
    </main>

    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{ __('messages.requests-clients') }}</h1>
      </div>
      <x-components::table :header="[__('messages.coach'), __('messages.name-client'), __('messages.reason-optional'), __('messages.status'), __('messages.details')]">
        @forelse ($clientRequests as $r)
          <div class="row">
            <p class="search">{{ $r->coach ? $r->coach->fname . ' ' . $r->coach->lname : '—' }}</p>
            <p>{{ $r->client ? $r->client->fname . ' ' . $r->client->lname : '—' }}</p>
            <p>{{ $r->reason ?: '—' }}</p>
            <p data-state="request">{{ $r->status }}</p>
            <div class="content-row">
              <form method="post" action="{{ route('coachManagement.manage') }}">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $r->id }}" />
                <input type="hidden" name="note" value="" />
                <button type="submit" name="action" value="approve" class="btn success">{{ __('messages.approve') }}</button>
                <button type="submit" name="action" value="reject" class="btn danger">{{ __('messages.reject') }}</button>
              </form>
            </div>
          </div>
        @empty
          <div class="row"><p class="empty-row">{{ __('messages.no-pending-requests') }}</p></div>
        @endforelse
      </x-components::table>
    </main>

    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{ __('messages.active-assignments') }}</h1>
      </div>
      <x-components::table :header="[__('messages.name-client'), __('messages.coach'), __('messages.date'), __('messages.status'), __('messages.details')]">
        @forelse ($active as $a)
          <div class="row">
            <p class="search">{{ $a->client ? $a->client->fname . ' ' . $a->client->lname : '—' }}</p>
            <p>{{ $a->coach ? $a->coach->fname . ' ' . $a->coach->lname : '—' }}</p>
            <p>{{ $a->started_at?->format('d M Y') }}</p>
            <p data-state="acceptance">{{ $a->status }}</p>
            <div class="content-row">
              <form method="post" action="{{ route('coachManagement.manage') }}">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $a->id }}" />
                <input type="hidden" name="note" value="" />
                <button type="submit" name="action" value="end" class="btn danger">{{ __('messages.end') }}</button>
              </form>
            </div>
          </div>
        @empty
          <div class="row"><p class="empty-row">{{ __('messages.no-active-assignments') }}</p></div>
        @endforelse
      </x-components::table>
    </main>

    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{ __('messages.coaches') }}</h1>
      </div>
      <x-components::table :header="[__('messages.name'), __('messages.specialization'), __('messages.active-clients'), __('messages.capacity')]">
        @forelse ($coaches as $c)
          <div class="row">
            <p class="search">{{ $c['employee']->fname }} {{ $c['employee']->lname }}</p>
            <p>{{ $c['profile']?->specialization ?? '—' }}</p>
            <p>{{ $c['activeClients'] }}</p>
            <p>{{ $c['profile'] ? ($c['profile']->max_active_clients ?? '—') : '—' }}</p>
          </div>
        @empty
          <div class="row"><p class="empty-row">{{ __('messages.no-coaches-available') }}</p></div>
        @endforelse
      </x-components::table>
    </main>

    <main class="main-tabel-row-search">
      <div class="content">
        <h1>{{ __('messages.history') }}</h1>
      </div>
      <x-components::table :header="[__('messages.name-client'), __('messages.coach'), __('messages.status'), __('messages.date')]">
        @forelse ($history as $h)
          <div class="row">
            <p class="search">{{ $h->client ? $h->client->fname . ' ' . $h->client->lname : '—' }}</p>
            <p>{{ $h->coach ? $h->coach->fname . ' ' . $h->coach->lname : '—' }}</p>
            <p data-state="exit">{{ $h->status }}</p>
            <p>{{ $h->requested_at?->format('d M Y') }}</p>
          </div>
        @empty
          <div class="row"><p class="empty-row">{{ __('messages.no-history') }}</p></div>
        @endforelse
      </x-components::table>
    </main>
  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.main-card[data-state="coach-summon"]');
    if (!cards.length) return;

    const open = () => {
      cards.forEach(c => c.classList.add('show-main-card'));
    };
    const close = () => {
      cards.forEach(c => c.classList.remove('show-main-card'));
    };

    document.querySelectorAll('.coach-summon-trigger').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (cards[0].classList.contains('show-main-card')) {
          close();
        } else {
          open();
        }
      });
    });
    document.querySelectorAll('.coach-summon-close').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        close();
      });
    });

    const clientCoaches = @json($clientCoaches ?? []);

    document.querySelectorAll('.client-request-trigger').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const code = btn.dataset.code;
        const select = document.getElementById('summon-client');
        if (select) {
          select.value = code;
        }
        open();
      });
    });

    const form = document.querySelector('form[action="{{ route('coachManagement.requestClient') }}"]');
    if (form) {
      form.addEventListener('submit', (e) => {
        const select = document.getElementById('summon-client');
        const code = select ? select.value : '';
        const coach = clientCoaches[code];
        if (coach) {
          const msg = @json(__('messages.change-coach-confirm')) + ' (' + coach + ')';
          if (!confirm(msg)) {
            e.preventDefault();
          }
        }
      });
    }
  });
</script>
@endsection
