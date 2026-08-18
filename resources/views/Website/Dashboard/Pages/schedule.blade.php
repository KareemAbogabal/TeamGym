@extends('Website.Dashboard.homePage')

@section('title', "Class Schedule")

@section('class', "class-schedule")

@section('content')
  <div class="progress-wrapper">
    <progress id="timer" value="0" max="60"></progress>
    <span id="time-label">0s</span>
  </div>

  <main>
    <div class="schedule-exercises">
      @php
        $todayExercises = [];
        $otherExercises = [];
        $elementsJson = [];
        if ($activities) {
          foreach ($activities as $item) {
            if ($item->statement == "true" && $item->day == $day) {
              $todayExercises[] = $item;
            } else {
              $otherExercises[] = $item;
            }
          }
        }
        $allCards = array_merge($todayExercises, $otherExercises);
        foreach ($allCards as $item) {
          $elementsData = [];
          foreach ($item->elements as $el) {
            $atts = [];
            foreach ($el->attachments as $a) {
              $atts[] = [
                'img' => $a->img ? asset($a->img) : '',
                'video' => $a->video ? asset($a->video) : '',
              ];
            }
            $elementsData[] = [
              'name' => $el->name,
              'ratio' => $el->ratio,
              'sets' => $el->sets,
              'attachments' => $atts,
            ];
          }
          $elementsJson[$item->code] = json_encode($elementsData);
        }
      @endphp

      <div class="schedule-section">
        <h2 class="schedule-section-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
          {{__('messages.today-s-exercises') ?? "Today's Exercises"}}
        </h2>
        <div class="schedule-cards">
          @forelse ($todayExercises as $item)
            <div class="exercise-card {{ $item->statement == 'true' ? 'active' : '' }}"
              data-code="{{$item->code}}"
              data-name="{{$item->name}}"
              data-description="{{$item->description}}"
              data-statement="{{$item->statement}}"
              data-code-attachments="{{$item->code_attachments}}"
              data-elements="{{$elementsJson[$item->code]}}"
            >
              <div class="exercise-card-header">
                <div class="exercise-card-info">
                  <h3 class="exercise-card-name">{{$item->name}}</h3>
                  <p class="exercise-card-coach">{{__('messages.couch')}}: {{$item->employee->fname}} {{$item->employee->lname}}</p>
                </div>
                <div class="exercise-card-badge today">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                  </svg>
                </div>
              </div>
              <div class="exercise-card-footer">
                <p>{{__('messages.time')}}: {{$start}} - {{$end}}</p>
                <span class="exercise-card-expand">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                  </svg>
                </span>
              </div>
            </div>
          @empty
            <div class="schedule-empty">
              <p>{{__('messages.start-your-workout-today') ?? "No exercises scheduled for today"}}</p>
            </div>
          @endforelse
        </div>
      </div>

      <div class="schedule-section">
        <h2 class="schedule-section-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          {{__('messages.other-exercises') ?? "Other Exercises"}}
        </h2>
        <div class="schedule-cards">
          @forelse ($otherExercises as $item)
            <div class="exercise-card {{ $item->statement == 'true' ? '' : 'unactive' }}"
              data-code="{{$item->code}}"
              data-name="{{$item->name}}"
              data-description="{{$item->description}}"
              data-statement="{{$item->statement}}"
              data-code-attachments="{{$item->code_attachments}}"
              data-elements="{{$elementsJson[$item->code]}}"
            >
              <div class="exercise-card-header">
                <div class="exercise-card-info">
                  <h3 class="exercise-card-name">{{$item->name}}</h3>
                  <p class="exercise-card-coach">{{__('messages.couch')}}: {{$item->employee->fname}} {{$item->employee->lname}}</p>
                </div>
                <div class="exercise-card-badge other">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                  </svg>
                </div>
              </div>
              <div class="exercise-card-footer">
                <p>{{__('messages.time')}}: {{$start}} - {{$end}}</p>
                <span class="exercise-card-expand">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                  </svg>
                </span>
              </div>
            </div>
          @empty
            <div class="schedule-empty">
              <p>{{__('messages.no-other-exercises') ?? "No other exercises"}}</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="exercise-detail-panel" id="exerciseDetailPanel">
      <div class="detail-header">
        <h1 class="detail-name"></h1>
        <p class="detail-description"></p>
        <p class="detail-time">{{__('messages.time')}}: {{$start}} - {{$end}}</p>
      </div>

      <x-components::table :header="[__('messages.exercise-name'), __('messages.number-of-groups'), __('messages.number-of-sets'), __('messages.details')]">
        <div class="body shapes-body">
        </div>
      </x-components::table>

      <div class="detail-media">
        <div class="media-container">
          <img src="" alt="Exercise" class="detail-img">
        </div>
      </div>

      <div class="detail-actions">
        <button class="btn-exercises-go" type="button">{{__('messages.go')}}</button>
      </div>
    </div>
  </main>

  <div class="content-table">
    <h1>{{__('messages.foods')}}</h1>
    <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
  </div>
  <div class="main">
    <x-components::table :header="[__('messages.name'), __('messages.often'), __('messages.quantity'), __('messages.date')]">
      @if ($foods)
        @foreach ($foods as $item)
          @foreach ($item->elements as $element)
            <div class="row">
              <p class="search">{{$element->name}}</p>
              <p>{{$element->name}}</p>
              <p>{{$element->ratio}}</p>
              <p>{{$element->created_at}}</p>
            </div>
          @endforeach
        @endforeach
      @endif
    </x-components::table>
  </div>
  <script>
    let hour = 1;
    let tGo = @json(__('messages.go'));
    let defaultExerciseImg = '{{asset("images/exercises/defult.jpeg")}}';
  </script>
  <script src="{{asset("js/Website/Dashboard/pages/schedule.js")}}"></script>
@endsection
