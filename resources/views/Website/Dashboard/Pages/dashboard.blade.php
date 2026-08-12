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
  </div>
  <div class="progress">
    <canvas id="chart-6" data-percentage='@json($analysis)'></canvas>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{asset("js/Website/Dashboard/pages/dashboard.js")}}"></script>
@endsection
