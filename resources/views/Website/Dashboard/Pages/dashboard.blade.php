@extends('Website.Dashboard.homePage')

@section('title', "Dashboard")

@section('class', "dashboard")

@section('content')
  <div class="header">
    <div class="coulmn">
      <div class="charts-circle">
        <div class="main-char">
          <div class="char">
            <canvas id="chart-1" data-percentage="{{$smm}}"></canvas>
            <p>{{$smm}}%</p>
          </div>
          <div class="content">
            <h1>{{__('messages.muscles')}}</h1>
            <p>{{__('messages.muscle-percentage')}}</p>
          </div>
        </div>
        <div class="main-char">
          <div class="char">
            <canvas id="chart-2" data-percentage="{{$fat}}"></canvas>
            <p>{{$fat}}%</p>
          </div>
          <div class="content">
            <h1>{{__('messages.fats')}}</h1>
            <p>{{__('messages.fats-percentage')}}</p>
          </div>
        </div>
        <div class="main-char">
          <div class="char">
            <canvas id="chart-3" data-percentage="{{$water}}"></canvas>
            <p>{{$water}}%</p>
          </div>
          <div class="content">
            <h1>{{__('messages.water')}}</h1>
            <p>{{__('messages.water-percentage')}}</p>
          </div>
        </div>
      </div>
      <div class="charts-body">
        <div class="main-char">
          <div class="char">
            <canvas id="chart-5" class="bar-body" data-percentage-water='@json($waterM)' data-percentage-protein='@json($proteinM)' data-percentage-fats='@json($fatM)'></canvas>
          </div>
        </div>
        <div class="main-char">
          <div class="char">
            <canvas id="chart-4" data-percentage="[{{$weight}}, {{$smm}}, {{$fat}}, {{$water}}, {{$pbf}}]"></canvas>
          </div>
          <div class="content">
            <h1>{{__('messages.body-condition')}}</h1>
            <p>{{__('messages.body-condition-percentage')}}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="exercises">
      <h1>{{__('messages.exercises')}}</h1>
      @forelse ($exercise as $item)
        <div class="exercise">
          <img src="{{asset($item->elements[0]->attachments[0]->img)}}" alt="No Img Login">
          <div class="content">
            <h1>{{$item->name}}</h1>
            <p>{{$item->description}}</p>
          </div>
          <button type="button"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @empty
    @endforelse
  </div>
  <x-components::main-card state="coach-request" dataFollow="coach-request" extraClass="coach-request-card">
    <div class="body-card">
      <div class="coach-request-header">
        <i class="fa-solid fa-dumbbell"></i>
        <h1>{{ __('messages.request') }} {{ __('messages.coach') }}</h1>
        <p>{{ __('messages.choose-a-coach') }}</p>
      </div>
      @if ($activeCoach)
        <div class="coach-current">
          <strong>{{ __('messages.active-coach') }}</strong>
          <span>{{ $activeCoach->coach ? $activeCoach->coach->fname . ' ' . $activeCoach->coach->lname : '—' }}</span>
        </div>
      @elseif ($pendingCoach)
        <div class="coach-current">
          <strong>{{ __('messages.pending-coach') }}</strong>
          <span>{{ $pendingCoach->coach ? $pendingCoach->coach->fname . ' ' . $pendingCoach->coach->lname : '—' }}</span>
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
            <button type="submit" class="view-profile tg-btn tg-btn--primary" @if($pendingCoach) disabled @endif>{{ __('messages.request') }}</button>
          </div>
        </div>
      </form>
    </div>
  </x-components::main-card>
</div>
  <div class="progress">
    <canvas id="chart-6" data-percentage='@json($analysis)'></canvas>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{asset("js/Website/Dashboard/pages/dashboard.js")}}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.querySelector('form[action="{{ route('coach.request') }}"]');
      if (!form) return;
      const hasActiveCoach = @json((bool) ($activeCoach ?? false));
      if (!hasActiveCoach) return;
      form.addEventListener('submit', (e) => {
        if (!confirm(@json(__('messages.change-coach-confirm-client')))) {
          e.preventDefault();
        }
      });
    });
  </script>
@endsection
