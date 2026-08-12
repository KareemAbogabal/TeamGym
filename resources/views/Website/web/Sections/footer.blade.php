<footer>
  <div class="footer-banner">
    <div class="banner-text">
      <h1>{{__('messages.footer-h1')}}</h1>
      <p>{{__('messages.footer-p')}}</p>
      <div class="banner-buttons">
        <a href="#" class="btn btn-dark">{{__('messages.footer-button-1')}}</a>
        <a href="#" class="cta"> <span>{{__('messages.footer-button-2')}}</span>
          <svg width="15px" height="10px" viewBox="0 0 13 10">
            <path d="M1,5 L11,5"></path>
            <polyline points="8 1 12 5 8 9"></polyline>
          </svg>
        </a>
      </div>
    </div>
    <div class="banner-image">
      <img src="{{asset("images/header/Team-Gym.png")}}" alt="No Img Footer">
    </div>
  </div>
  <div class="footer-columns">
    <div class="column">
      <h4>{{__('messages.footer-useful-links')}}</h4>
      <ul>
        <li><a href="{{route("stores")}}" >{{__('messages.footer-store')}}</a></li>
        <li><a href="{{route("contactUs") }}" class="cta-btn">{{ __('messages.cta_btn')}}</a></li>
        <li><a href="{{route("packages")}}" >{{__('messages.footer-packages')}}</a></li>
        <li><a href="{{route("privacyPolicy")}}">{{__('messages.footer-privacy')}}</a></li>
        <li><a href="{{route("mainArticles")}}" >{{__('messages.footer-home-articles')}}</a></li>
        <li><a href="{{route("mainArticles")}}#article-sports-experience">{{__('messages.footer-article1')}}</a></li>
      </ul>
    </div>
    <div class="column">
      <h4>{{__('messages.footer-blog')}}</h4>
      <ul>
        <li><a href="{{route("mainArticles")}}#article-progress-in-gym">{{__('messages.footer-article2')}}</a></li>
        <li><a href="{{route("mainArticles")}}#article-lose-weight">{{__('messages.footer-article3')}}</a></li>
        <li><a href="{{route("mainArticles")}}#article-build-muscle">{{__('messages.footer-article4')}}</a></li>
        <li><a href="{{route("mainArticles")}}#article-dietary-supplements">{{__('messages.footer-article5')}}</a></li>
        <li><a href="{{route("mainArticles")}}#article-healthy-eating-sleep">{{__('messages.footer-article6')}}</a></li>
      </ul>
    </div>
    <div class="column">
      <h4>{{__('messages.footer-resources')}}</h4>
      <ul>
        <li><a href="#">{{__('messages.footer-events')}}</a></li>
        <li><a href="#">{{__('messages.footer-community')}}</a></li>
        <li><a href="#">{{__('messages.footer-social-media')}}</a></li>
        <li><a href="#">{{__('messages.footer-newsletter')}}</a></li>
        <li><a href="#">{{__('messages.footer-subscribe')}}</a></li>
      </ul>
    </div>
    <div class="column subscribe">
      <h4>{{__('messages.footer-subscribe-title')}}</h4>
      <p>{{__('messages.footer-subscribe-text')}}</p>
      <form action="" method="POST" class="subscribe-form">
        <input type="email" name="email-footer" placeholder="Enter your email" required />
        <button type="submit">{{__('messages.footer-subscribe-button')}}</button>
      </form>
      <small>
        {{__('messages.footer-subscribe-note')}}
        <a href="{{route("privacyPolicy")}}">{{__('messages.footer-privacy-policy')}}</a>
      </small>
    </div>
  </div>
  <div class="footer-bottom">
    <p>{{__('messages.footer-copyright')}}</p>
    <div class="social-icons">
      <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="#"><i class="fa-brands fa-instagram"></i></a>
      <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
      <a href="#"><i class="fa-brands fa-tiktok"></i></a>
    </div>
  </div>
</footer>
