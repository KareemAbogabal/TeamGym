@extends('Website.Dashboard.homePage')

@section('title', "Health Tracking")

@section('class', "health")

@section('content')
  <div class="health-hero">
    <div class="health-hero__text">
      <span class="health-eyebrow">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="currentColor" aria-hidden="true" role="img">
          <path d="M352 256a96 96 0 1 1-192 0 96 96 0 1 1 192 0z"/>
          <path d="M256 0c17.7 0 32 14.3 32 32V96c0 17.7-14.3 32-32 32s-32-14.3-32-32V32c0-17.7 14.3-32 32-32zM432 256c0 17.7-14.3 32-32 32H336c-17.7 0-32-14.3-32-32s14.3-32 32-32h64c17.7 0 32 14.3 32 32zM256 416c17.7 0 32 14.3 32 32v32c0 17.7-14.3 32-32 32s-32-14.3-32-32V448c0-17.7 14.3-32 32-32zM80 256c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H48c17.7 0 32 14.3 32 32z"/>
        </svg>
        InBody Analysis
      </span>
      <h1>{{__('messages.health-tracking')}}</h1>
      <p>Upload your InBody report photo and get an automatic AI analysis of your body composition in seconds.</p>
    </div>
    <div class="health-hero__actions">
      <span id="status" class="health-status">—</span>
      <div class="health-actions">
        <label for="body" class="btn-upload">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true" role="img">
            <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V272H64c-17.7 0-32 14.3-32 32s14.3 32 32 32H224V480c0 17.7 14.3 32 32 32s32-14.3 32-32V336H448c17.7 0 32-14.3 32-32s-14.3-32-32-32H288V32z"/>
          </svg>
          <span class="lable">Upload Report</span>
        </label>
        <input type="file" id="body" class="inbodyUpload" name="img" accept="image/*" hidden>
        <button type="button" class="show-img-inBody btn-archive">
          <svg fill="none" viewBox="0 0 24 24" class="svg-icon" aria-hidden="true" role="img">
            <g clip-rule="evenodd" fill-rule="evenodd" stroke="#fae105" stroke-linecap="round" stroke-width="2">
              <path d="m3 7h17c.5523 0 1 .44772 1 1v11c0 .5523-.4477 1-1 1h-16c-.55228 0-1-.4477-1-1z"></path>
              <path d="m3 4.5c0-.27614.22386-.5.5-.5h6.29289c.13261 0 .25981.05268.35351.14645l2.8536 2.85355h-10z"></path>
            </g>
          </svg>
          <span class="lable">Archive</span>
        </button>
      </div>
    </div>
  </div>

  <div class="health-metrics">
    <div class="metric-card">
      <span class="metric-label">{{__('messages.weight')}}</span>
      <p class="metric-value"><span class="weight">{{$basis['weight'] ?? 0}}</span><small> {{__('messages.kg')}}</small></p>
    </div>
    <div class="metric-card">
      <span class="metric-label">{{__('messages.bmi')}}</span>
      <p class="metric-value"><span class="BMI">{{$basis['BMI'] ?? 0}}</span><small> kg/m²</small></p>
    </div>
    <div class="metric-card">
      <span class="metric-label">{{__('messages.pbf_percent')}}</span>
      <p class="metric-value"><span class="PBF">{{$basis['PBF'] ?? 0}}</span><small> %</small></p>
    </div>
    <div class="metric-card">
      <span class="metric-label">{{__('messages.smm_kg')}}</span>
      <p class="metric-value"><span class="SMM">{{$basis['SMM'] ?? 0}}</span><small> {{__('messages.kg')}}</small></p>
    </div>
    <div class="metric-card">
      <span class="metric-label">{{__('messages.kcal')}}</span>
      <p class="metric-value"><span class="KCAL">{{$basis['KCAL'] ?? 0}}</span><small> kcal</small></p>
    </div>
    <div class="metric-card">
      <span class="metric-label">{{__('messages.total_body_water')}}</span>
      <p class="metric-value"><span class="water">{{$basis['water'] ?? 0}}</span><small> L</small></p>
    </div>
    <div class="metric-card">
      <span class="metric-label">{{__('messages.body_fat_mass')}}</span>
      <p class="metric-value"><span class="fat_mass">{{$basis['fat_mass'] ?? 0}}</span><small> {{__('messages.kg')}}</small></p>
    </div>
    <div class="metric-card">
      <span class="metric-label">{{__('messages.protein_kg')}}</span>
      <p class="metric-value"><span class="protein">{{$basis['protein'] ?? 0}}</span><small> {{__('messages.kg')}}</small></p>
    </div>
  </div>

  <div class="health-grid">
    <div class="health-card health-card--composition">
      <div class="health-card__head">
        <h2>Body Composition</h2>
      </div>
      <div class="health-composition">
        <div class="char">
          <canvas id="chart-1" data-percentage="{{$basis['SMM'] ?? 0}}"></canvas>
          <div class="content">
            <p>{{__('messages.muscles')}}</p>
            <p>{{$basis['SMM'] ?? 0}}%</p>
          </div>
        </div>
        <div class="char">
          <canvas id="chart-3" data-percentage="{{$basis['fat_mass'] ?? 0}}"></canvas>
          <div class="content">
            <p>{{__('messages.fats')}}</p>
            <p>{{$basis['fat_mass'] ?? 0}}%</p>
          </div>
        </div>
        <div class="char">
          <canvas id="chart-4" data-percentage="{{$basis['water'] ?? 0}}"></canvas>
          <div class="content">
            <p>{{__('messages.water')}}</p>
            <p>{{$basis['water'] ?? 0}}%</p>
          </div>
        </div>
      </div>
    </div>

    <div class="health-card health-card--muscle">
      <div class="health-card__head">
        <h2>{{__('messages.muscles')}} — {{__('messages.body-condition')}}</h2>
      </div>
      <div class="health-chart-holder">
        <canvas id="chart-2" data-muscles='@json($muscles)'></canvas>
      </div>
    </div>

    <div class="health-card health-card--fatwater">
      <div class="health-card__head">
        <h2>Fat & Water Trend</h2>
      </div>
      <div class="health-chart-holder">
        <canvas id="chart-5" data-fat='@json($fat)' data-water='@json($water)'></canvas>
      </div>
    </div>

    <div class="health-card health-card--segmental">
      <div class="health-card__head">
        <h2>Segmental Breakdown</h2>
      </div>
      <div class="health-segmental">
        <div class="health-segmental__group">
          <h3>{{__('messages.lean')}}</h3>
          <ul>
            <li>{{__('messages.right_arm_lean')}} <span class="right-arm-lean-kg">{{$minor['right_arm_lean'] ?? 0}}</span> {{__('messages.kg')}}</li>
            <li>{{__('messages.left_arm_lean')}} <span class="left-arm-lean-kg">{{$minor['left_arm_lean'] ?? 0}}</span> {{__('messages.kg')}}</li>
            <li>{{__('messages.right_leg_lean')}} <span class="right-leg-lean-kg">{{$minor['right_leg_lean'] ?? 0}}</span> {{__('messages.kg')}}</li>
            <li>{{__('messages.left_leg_lean')}} <span class="left-leg-lean-kg">{{$minor['left_leg_lean'] ?? 0}}</span> {{__('messages.kg')}}</li>
          </ul>
        </div>
        <div class="health-segmental__group">
          <h3>{{__('messages.fats')}}</h3>
          <ul>
            <li>{{__('messages.right_arm_fat')}} <span class="right-arm-fat-kg">{{$minor['right_arm_fat'] ?? 0}}</span> {{__('messages.kg')}}</li>
            <li>{{__('messages.left_arm_fat')}} <span class="left-arm-fat-kg">{{$minor['left_arm_fat'] ?? 0}}</span> {{__('messages.kg')}}</li>
            <li>{{__('messages.right_leg_fat')}} <span class="right-leg-fat-kg">{{$minor['right_leg_fat'] ?? 0}}</span> {{__('messages.kg')}}</li>
            <li>{{__('messages.left_leg_fat')}} <span class="left-leg-fat-kg">{{$minor['left_leg_fat'] ?? 0}}</span> {{__('messages.kg')}}</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="img-up">
    <div class="content">
      <div class="header">
        <button type="button" class="remove-img-inBody">×</button>
      </div>
      <div class="img">
        @if ($imgInBody)
          <img src="{{asset("images/inBody/$imgInBody->img")}}" class="imgInBody" alt="No Img Logo">
        @endif
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
  <script src="{{asset("js/Website/web/public.js")}}" type="module"></script>
  <script src="{{asset("js/Website/Dashboard/pages/health.js")}}"></script>
  <script src="{{asset("js/Website/Dashboard/pages/Inbody-coordinates.js")}}?v=8"></script>
@endsection
