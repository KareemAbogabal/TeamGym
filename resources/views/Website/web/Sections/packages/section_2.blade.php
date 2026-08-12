<section class="section-2">
    <x-web::header title="{!!__('messages.sc-4-h1')!!}" paragraph="{{__('messages.sc-4-paragraph')}}"/>
    <div class="systems">
      @if ($systems)
        @foreach ($systems as $item)
          <div class="card {{$item->name}}">
            <div class="ribbon">
              <span>
                <svg width="24" height="24" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_429_11034)">
                    <path d="M6 14L13 2V10H18L11 22V14H6Z" stroke="#161616" stroke-width="2.5" stroke-linejoin="round"/>
                  </g>
                  <defs>
                    <clipPath id="clip0_429_11034">
                      <rect width="24" height="24" fill="white"/>
                    </clipPath>
                  </defs>
                </svg>
              </span>
            </div>
            <h1>{{ \Illuminate\Support\Facades\Lang::has("messages.sc-4-card-h1-{$item->name}") ? __("messages.sc-4-card-h1-{$item->name}") : $item->name }}</h1>
            <div class="features">
              @foreach ($item->features as $f)
                <div class="item">
                  <svg width="30" height="30" viewBox="0 0 25 25" fill="none">
                    <path opacity="0.5" d="M4 12.9L7.14286 16.5L15 7.5" stroke="var(--colorAverage)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M20.0002 7.5625L11.4286 16.5625L11.0002 16" stroke="var(--colorAverage)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <p>{{$f->name}}</p>
                </div>
              @endforeach
            </div>
            <div class="amount">
              <h1>{{$item->amount}} EGP</h1>
              <p>{{$item->duration}} month</p>
            </div>
            <button data-follow="show-card" >{{__('messages.sc-4-card-button')}}</button>
          </div>
        @endforeach
      @endif
    </div>
  </section>