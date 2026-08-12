<section class="section-3">
  <x-web::header title="{!!__('messages.sc-3-h1')!!}" paragraph="{{__('messages.sc-3-paragraph')}}"/>
  <main>
    <div class="products">
      @if ($supplements)
        @foreach ($supplements as $index => $item)
          <div class="product {{ $index == 0 ? 'active' : '' }}"  id="product-{{$index}}" data-code="{{$item->code}}">
            <div class="ribbon right"><span> <p class="price">{{$item->amount}}</p> EGP</span></div>
            @if ($item->discount)
              <div class="ribbon left"><span><del><p class="discount">{{$item->discount}}</p> EGP</del></span></div>
            @endif
            <img src="{{asset("images/products/$item->img")}}" class="img-product" alt="No Img Product">
            <div class="content">
              <h1 class="title">{{ \Illuminate\Support\Facades\Lang::has("messages.sc-4-card-h1-{$item->name}") ? __("messages.sc-4-card-h1-{$item->name}") : $item->name }}</h1>
              <p class="meta-text">{{$item->description}}</p>
            </div>
            <a href="{{route("stores")}}"  class="product-button">
              <span>{{__('messages.sc-3-product-button')}}</span>
            </a>
          </div>
        @endforeach
      @endif
    </div>
    <div class="aligns">
      <div class="btns">
        @foreach ($supplements as $index => $item)
          <a href="#product-{{$index}}" class="align-sec-3 {{ $index == 0 ? 'show-align' : '' }}"></a>
        @endforeach
      </div>
    </div>
  </main>
</section>
