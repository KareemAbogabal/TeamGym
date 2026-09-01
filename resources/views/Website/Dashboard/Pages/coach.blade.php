@extends('Website.Dashboard.homePage')

@section('title', 'Coach')

@section('class', 'coach')

@section('content')
<main class="client-coach">
  <div class="coach-title">
    <h1>{{ __('messages.coach') }}</h1>
    <p>{{ __('messages.choose-a-coach') }}</p>
  </div>

  <section class="coach-status">
    @if ($active)
      <div class="status-card active">
        <div class="status-avatar">
          <img src="{{ ($active->coach && $active->coach->img) ? asset('images/employees/' . $active->coach->img) : asset('images/header/Team-Gym.png') }}" alt="{{ $active->coach?->fname }}" />
        </div>
        <div class="status-body">
          <strong>{{ __('messages.active-coach') }}</strong>
          <span>{{ $active->coach ? $active->coach->fname . ' ' . $active->coach->lname : '—' }}</span>
          <small>{{ __('messages.coach-since') }} {{ $active->started_at?->format('d M Y') }}</small>
          @if ($active->coach?->coachProfile?->specialization)
            <small class="coach-doc">{{ $active->coach->coachProfile->specialization }}</small>
          @endif
        </div>
      </div>
    @elseif ($pending)
      <div class="status-card pending">
        <div class="status-avatar">
          <img src="{{ ($pending->coach && $pending->coach->img) ? asset('images/employees/' . $pending->coach->img) : asset('images/header/Team-Gym.png') }}" alt="{{ $pending->coach?->fname }}" />
        </div>
        <div class="status-body">
          <strong>{{ __('messages.pending-coach') }}</strong>
          <span>{{ $pending->coach ? $pending->coach->fname . ' ' . $pending->coach->lname : '—' }}</span>
          <small>{{ __('messages.pending-since') }} {{ $pending->requested_at?->format('d M Y') }}</small>
          @if ($pending->coach?->coachProfile?->specialization)
            <small class="coach-doc">{{ $pending->coach->coachProfile->specialization }}</small>
          @endif
        </div>
        <form method="post" action="{{ route('coach.cancel') }}">
          @csrf
          <input type="hidden" name="assignment_id" value="{{ $pending->id }}" />
          <button type="submit" class="btn tg-btn tg-btn--secondary">{{ __('messages.cancel') }}</button>
        </form>
      </div>
    @else
      <div class="status-card idle">
        <div class="status-icon"><i class="fa-solid fa-user-plus"></i></div>
        <div class="status-body">
          <strong>{{ __('messages.no-coach') }}</strong>
          <span>{{ __('messages.choose-a-coach') }}</span>
        </div>
      </div>
    @endif
  </section>

  <div class="coach-actions">
    <button type="button" class="btn coach-request-open tg-btn tg-btn--primary" data-follow="coach-request" @if($pending) disabled @endif>
      <i class="fa-solid @if($active) fa-arrows-rotate @else fa-dumbbell @endif"></i>
      @if ($active)
        {{ __('messages.change-coach') }}
      @elseif ($pending)
        {{ __('messages.requested') }}
      @else
        {{ __('messages.request') }} {{ __('messages.coach') }}
      @endif
    </button>
  </div>

  <x-components::main-card state="coach-request" dataFollow="coach-request" extraClass="coach-request-card">
    <div class="body-card">
      <div class="coach-request-header">
        <div class="header-icon"><i class="fa-solid fa-dumbbell"></i></div>
        <h1>{{ __('messages.request') }} {{ __('messages.coach') }}</h1>
        <p>{{ __('messages.choose-a-coach') }}</p>
      </div>
      @if ($activeCoach ?? $active)
        <div class="coach-current">
          <strong>{{ __('messages.active-coach') }}</strong>
          <span>{{ ($activeCoach ?? $active)->coach ? ($activeCoach ?? $active)->coach->fname . ' ' . ($activeCoach ?? $active)->coach->lname : '—' }}</span>
        </div>
      @elseif ($pendingCoach ?? $pending)
        <div class="coach-current">
          <strong>{{ __('messages.pending-coach') }}</strong>
          <span>{{ ($pendingCoach ?? $pending)->coach ? ($pendingCoach ?? $pending)->coach->fname . ' ' . ($pendingCoach ?? $pending)->coach->lname : '—' }}</span>
        </div>
      @endif
      <form method="post" action="{{ route('coach.request') }}">
        @csrf
        <div class="main-input">
          <label for="coach-request-select">{{ __('messages.choose-a-coach') }}</label>
          <select id="coach-request-select" name="code_coach" required>
            <option value="">{{ __('messages.choose-a-coach') }}</option>
            @forelse ($coaches as $c)
              <option value="{{ $c['employee']->code }}">{{ $c['employee']->fname }} {{ $c['employee']->lname }}@if($c['profile']?->specialization) — {{ $c['profile']->specialization }}@endif</option>
            @empty
            @endforelse
          </select>
        </div>
        <div class="main-input">
          <label for="coach-request-reason">{{ __('messages.reason-optional') }}</label>
          <textarea id="coach-request-reason" name="reason" rows="3" placeholder="{{ __('messages.reason-optional') }}"></textarea>
        </div>
        <div class="button-row-card">
          <div class="buttons">
            <x-components::close-button follow="coach-request" />
            <button type="submit" class="view-profile tg-btn tg-btn--primary" @if($pending) disabled @endif>{{ __('messages.request') }}</button>
          </div>
        </div>
      </form>
    </div>
  </x-components::main-card>

  <section class="coach-list">
    <h2>{{ __('messages.coaches') }}</h2>
    @forelse ($coaches as $c)
      <div class="coach-card">
        <div class="coach-avatar">
          <img src="{{ $c['employee']->img ? asset('images/employees/' . $c['employee']->img) : asset('images/header/Team-Gym.png') }}" alt="{{ $c['employee']->fname }}" />
        </div>
        <div class="coach-meta">
          <div class="coach-name">{{ $c['employee']->fname }} {{ $c['employee']->lname }}</div>
          <div class="coach-spec">{{ $c['profile']?->specialization ?? __('messages.general-coach') }}</div>
          <div class="coach-capacity">
            {{ $c['profile'] ? __('messages.clients-count', ['count' => $c['profile']->activeClientCount(), 'max' => $c['profile']->max_active_clients]) : '' }}
          </div>
        </div>
        @if ($pending)
          <button type="button" class="btn tg-btn tg-btn--secondary disabled" disabled>{{ __('messages.requested') }}</button>
        @else
          <button type="button" class="btn coach-request-open tg-btn tg-btn--primary" data-follow="coach-request">{{ __('messages.request') }}</button>
        @endif
      </div>
    @empty
      <p>{{ __('messages.no-coaches-available') }}</p>
    @endforelse
  </section>

  @if ($history->isNotEmpty())
    <section class="coach-history">
      <h3>{{ __('messages.history') }}</h3>
      <x-components::table :header="[__('messages.coach'), __('messages.status'), __('messages.date')]">
        @foreach ($history as $h)
          <div class="row">
            <p class="search">{{ $h->coach ? $h->coach->fname . ' ' . $h->coach->lname : '—' }}</p>
            <p data-state="request">{{ $h->status }}</p>
            <p>{{ $h->requested_at?->format('d M Y') }}</p>
          </div>
        @endforeach
      </x-components::table>
    </section>
  @endif
</main>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action="{{ route('coach.request') }}"]');
    if (!form) return;
    const hasActive = @json((bool) $active);
    if (!hasActive) return;
    form.addEventListener('submit', (e) => {
      if (!confirm(@json(__('messages.change-coach-confirm-client')))) {
        e.preventDefault();
      }
    });
  });
</script>
@endsection
