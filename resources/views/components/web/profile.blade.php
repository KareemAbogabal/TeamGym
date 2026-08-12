<main class="profile">
  <div class="card">
    @php
      $muscleValue = is_array($muscles) ? ($muscles['value'] ?? 0) : $muscles;
      $fatValue = is_array($fats) ? ($fats['value'] ?? 0) : $fats;
      $percentage = $lineages["SMM"] - $lineages["fat_mass"];
    @endphp
    <div class="header">
      <div class="img">
        <img src="{{$img ? asset('images/subscribers/' . $img) : asset('images/header/Team-Gym.png')}}" alt="{{$img}}">
        <div class="content">
          <h1>
            {{$name}}
            @if ($documentation == "true")
              <i class="material-symbols-outlined">verified</i>
            @endif
          </h1>
          <p>{{$state}}</p>
        </div>
      </div>
      <div class="link-for-achievements">
        <a href="#health" class="active-button-profile"><p>{{__('messages.card-profile-link-for-achievements-health')}}</p> {{$percentage}}%</a>
        <a href="#muscles"><p>{{__('messages.card-profile-link-for-achievements-muscles')}}</p> {{$lineages["SMM"] ?? 0}}%</a>
        <a href="#fats"><p>{{__('messages.card-profile-link-for-achievements-fats')}}</p> {{$lineages["fat_mass"] ?? 0}}%</a>
        <a href="#water"><p>{{__('messages.card-profile-link-for-achievements-water')}}</p> {{$lineages["water"] ?? 0}}%</a>
      </div>
    </div>
    <div class="row">
      <div class="health" id="health">
        <canvas id="myChart-1" data-percentage="{{ $percentage }}"></canvas>
        <h1>{{$percentage}}%</h1>
      </div>
      <div class="rates">
        <div class="rate" id="muscles">
          <canvas id="myChart-2" data-percentage='@json($muscles)'></canvas>
        </div>
        <div class="rate" id="fats">
          <canvas id="myChart-3" data-percentage='@json($fats)'></canvas>
        </div>
        <div class="rate" id="water">
          <canvas id="myChart-4" data-percentage='@json($water)'></canvas>
        </div>
      </div>
    </div>
    <div class="view-close">
      <button class="close-profile">{{__('messages.card-profile-button-close')}}</button>
      <a href="/dashboard" class="view-profile">{{__('messages.card-profile-button-view')}}</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</main>
