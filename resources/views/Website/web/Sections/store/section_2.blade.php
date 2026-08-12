<section id="section-2" class="section-2">
    <div class="product" role="region">
      <div class="content">
        <div class="products">
          <div class="thumbs" aria-hidden="false">
            @if ($supplements)
              @foreach ($supplements as $item)
                <div class="thumb"
                  data-code="{{$item->code}}"
                  data-title="{{$item->name}}"
                  data-description="{{$item->description}}"
                  data-amount="{{$item->amount}}"
                  data-discount="{{$item->discount}}"
                  data-quantity="{{$item->imports->quantity}}"
                >
                  <img src="{{asset("images/products/$item->img")}}" alt="thumb1">
                  <button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M6 6c-0.5 0-1 0.4-1 0.9l1.5 9.1c0 0.5 0.4 0.9 0.9 0.9h13c0.5 0 0.9-0.4 0.9-0.9L21 6.9c0-0.5-0.4-0.9-0.9-0.9H6z"></path>
                      <circle cx="3" cy="3" r="1.5"></circle>
                      <circle cx="9" cy="21" r="1"></circle>
                      <circle cx="18" cy="21" r="1"></circle>
                    </svg>
                  </button>
                </div>
              @endforeach
            @endif
          </div>
        </div>
        <div class="gallery">
          <figure role="img">
            <div class="badge"></div>
            <div class="photo">
              <img src="" alt="thumb1">
            </div>
          </figure>
        </div>
        <div class="details">
          <div class="title"></div>
          <div class="meta">
            <p class="meta-text"></p>
          </div>
          <div class="stars" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <defs>
                <linearGradient id="sg" x1="0" x2="1">
                  <stop offset="0" stop-color="#FFC600"/>
                  <stop offset="1" stop-color="#FF9A00"/>
                </linearGradient>
              </defs>
              <g fill="url(#sg)" transform="translate(4,3) scale(1.6)">
                <path d="M6 0l1.9 4.1L12.5 5l-3.2 2.8L10.7 12 6 9.9 1.3 12l1.4-4.2L-0.5 5l4.6-.9L6 0z"/>
              </g>
            </svg>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <defs>
                <linearGradient id="sg" x1="0" x2="1">
                  <stop offset="0" stop-color="#FFC600"/>
                  <stop offset="1" stop-color="#FF9A00"/>
                </linearGradient>
              </defs>
              <g fill="url(#sg)" transform="translate(4,3) scale(1.6)">
                <path d="M6 0l1.9 4.1L12.5 5l-3.2 2.8L10.7 12 6 9.9 1.3 12l1.4-4.2L-0.5 5l4.6-.9L6 0z"/>
              </g>
            </svg>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <defs>
                <linearGradient id="sg" x1="0" x2="1">
                  <stop offset="0" stop-color="#FFC600"/>
                  <stop offset="1" stop-color="#FF9A00"/>
                </linearGradient>
              </defs>
              <g fill="url(#sg)" transform="translate(4,3) scale(1.6)">
                <path d="M6 0l1.9 4.1L12.5 5l-3.2 2.8L10.7 12 6 9.9 1.3 12l1.4-4.2L-0.5 5l4.6-.9L6 0z"/>
              </g>
            </svg>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <defs>
                <linearGradient id="sg" x1="0" x2="1">
                  <stop offset="0" stop-color="#FFC600"/>
                  <stop offset="1" stop-color="#FF9A00"/>
                </linearGradient>
              </defs>
              <g fill="url(#sg)" transform="translate(4,3) scale(1.6)">
                <path d="M6 0l1.9 4.1L12.5 5l-3.2 2.8L10.7 12 6 9.9 1.3 12l1.4-4.2L-0.5 5l4.6-.9L6 0z"/>
              </g>
            </svg>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <defs>
                <linearGradient id="sg" x1="0" x2="1">
                  <stop offset="0" stop-color="#FFC600"/>
                  <stop offset="1" stop-color="#FF9A00"/>
                </linearGradient>
              </defs>
              <g fill="url(#sg)" transform="translate(4,3) scale(1.6)">
                <path d="M6 0l1.9 4.1L12.5 5l-3.2 2.8L10.7 12 6 9.9 1.3 12l1.4-4.2L-0.5 5l4.6-.9L6 0z"/>
              </g>
            </svg>
          </div>
          <div class="price-row">
            <div class="price"></div>
            <div class="old-price"></div>
            <span class="badge-tag">Top</span>
          </div>
          <div class="controls">
            <div class="qty" aria-label="الكمية">
              <button class="minus" aria-label="نقص">−</button>
              <div class="quantity">1</div>
              <button class="plus" aria-label="زيادة">+</button>
            </div>
          </div>
          <div class="actions">
            <button class="btn add">Add to cart</button>
            <button class="btn buy">Buy Now</button>
          </div>
        </div>
      </div>
      <div class="bottom-bar" aria-hidden="false">
        <div class="total-price">
          <div class="pill">Total Price: <span style="width:6px"></span><strong class="price-total"></strong></div>
        </div>
        <div class="buttons-products-choose">
          <button class="btn buy" style="padding:10px 18px">Buy Now</button>
        </div>
      </div>
    </div>
  </section>