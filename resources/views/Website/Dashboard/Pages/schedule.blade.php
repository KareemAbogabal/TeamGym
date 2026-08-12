@extends('Website.Dashboard.homePage')

@section('title', "Class Schedule")

@section('class', "class-schedule")

@section('content')
  <div class="progress-wrapper">
    <progress id="timer" value="0" max="60"></progress>
    <span id="time-label">0s</span>
  </div>
  <main>
    <div class="exercises">
      @if ($activities)
        @foreach ($activities as $item)
          <div @if ($item->statement == "true" && $item->day == $day) class="exercise active" @elseif ($item->statement == "true" && $item->day != $day && ($item->times != $item->visits)) class="exercise" @else class="exercise unactive" @endif>
            <div class="content">
              <h1>{{$item->name}}</h1>
              <p data-description="{{$item->description}}">{{__('messages.couch')}}: {{$item->employee->fname}} {{$item->employee->lname}}</p>
            </div>
            <div class="footer-exercise">
              <p>{{__('messages.time')}}: {{$start}} - {{$end}}</p>
              <button class="go-exercise" id="{{$item->code_attachments}}" data-code="{{$item->code}}" data-img="{{asset($item->elements[0]->attachments[0]->img)}}" data-video="{{asset("video/exercises/push.mp4")}}"><i class="fa-solid fa-check"></i></button>
            </div>
          </div>
        @endforeach
      @endif
    </div>
    <x-components::table :header="[__('messages.exercise-name'), __('messages.number-of-groups'), __('messages.number-of-sets'), __('messages.details')]"/>
    <div class="show-exercises">
      <div class="header">
        @forelse ($exercise as $item)
          <h1 class="name-exercises">{{ $item->name }}</h1>
          <p class="description">{{ $item->description }}</p>
        @empty
          <h1 class="name-exercises">{{__('messages.start-your-workout-today')}}</h1>
          <p class="description">{{__('messages.description-exercises')}}</p>
        @endforelse
      </div>
      <div class="body">
        <div class="shape">
          @if ($exercise)
            @forelse ($exercise as $item)
              <img src="{{asset($item->elements[0]->attachments[0]->img)}}" alt="No img exercises">
            @empty
              <img src="{{asset("images/exercises/defult.jpeg")}}" alt="No img exercises">
            @endforelse
          @endif
        </div>
      </div>
      <p>{{__('messages.time')}}: {{$start}} - {{$end}}</p>
      <form action="" method="post">
        <input type="hidden">
        <button class="btn-exercises-go"
          @forelse ($exercise as $item)
            data-statement="{{$item->statement}}"
            data-code="{{$item->code}}"
            data-name="{{$item->name}}"
            data-description="{{$item->description}}"
          @empty
          @endforelse
          type="button">{{__('messages.go')}}</button>
      </form>
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
    let videoExercises = [
      '{{asset("video/exercises/push.mp4")}}',
    ];
    let hour = 1;
  </script>
  <script src="{{asset("js/Website/Dashboard/pages/schedule.js")}}"></script>
@endsection
