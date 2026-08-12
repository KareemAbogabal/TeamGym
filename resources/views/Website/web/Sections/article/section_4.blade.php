<section class="section-4 main-products">
    <h1>{{__('messages.nav-systems')}}</h1>
    <div class="products">
      @if ($supplements)
        @foreach ($supplements as $index => $item)
          <div class="product card"  id="product-{{$index}}"
            data-code="{{$item->code}}"
            data-name="{{$item->name}}"
            data-amount="{{$item->amount}}"
            data-quantity="1"
            data-type="supplement"
          >
            <div class="ribbon right"><span>{{$item->amount}} EGP</span></div>
            @if ($item->discount)
              <div class="ribbon left"><span><del><p class="price-product">{{$item->amount}}</p> EGP</del></span></div>
            @endif
            <img src="{{asset("images/products/$item->img")}}" class="img-product" alt="No Img Product">
            <div class="content">
              <h1>{{$item->name}}</h1>
              <p>{{$item->description}}</p>
            </div>
            <input type="hidden" value="{{$item->code}}" name="code">
            <input type="hidden" value="{{$item->name}}" name="order_name">
            <input type="hidden" value="{{$item->amount}}" name="amount">
            <button type="button" class="show" data-code="{{$item->code}}" data-follow="show-card">
              <span>{{__('messages.sc-3-product-button')}}</span>
            </button>
          </div>
        @endforeach
      @endif
    </div>
  </section>