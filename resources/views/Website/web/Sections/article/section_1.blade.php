<section class="section-1">
    <h1>TEAM GYM</h1>
    <main>
      <div class="content">
        <h1>TEAM GYM</h1>
        <img src="{{asset("images/content/img-article.jpeg")}}" alt="No Img Section 1">
      </div>
      <div class="averages">
        <div class="accounts">
          <div class="average" style="--degAverage: -25deg; --vertical: 100%; --horizontal1: -120px; --horizontal2: 20px;">
            <img src="{{asset("images/content/location.jpeg")}}" alt="No Img Card">
          </div>
          <div class="average" style="--degAverage: 25deg; --vertical: -100%; --horizontal1: -120px; --horizontal2: 20px;">
            <img src="{{asset("images/content/location.jpeg")}}" alt="No Img Card">
          </div>
          <div class="average" style="--degAverage: -25deg; --vertical: 100%; --horizontal1: -310px; --horizontal2: -220px;">
            <img src="{{asset("images/content/location.jpeg")}}" alt="No Img Card">
          </div>
          <div class="average" style="--degAverage: 25deg; --vertical: -100%; --horizontal1: -310px; --horizontal2: -220px;">
            <img src="{{asset("images/content/location.jpeg")}}" alt="No Img Card">
          </div>
        </div>
      </div>
      <div class="main-card-video">
        <div class="card-video">
          <div class="video">
            <video src="{{asset("video/exercises/push.mp4")}}"></video>
          </div>
          <div class="control">
            <button class="button-show-video">
              <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" aria-hidden="true" role="img">
                <path d="M6 4v16l13-8-13-8z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>
          <div class="content-video">
            <div class="head">
              <button class="mute">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" aria-hidden="true" role="img">
                  <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 5L6 9H3v6h3l5 4V5z" />
                    <line x1="23" y1="9" x2="17" y2="15" />
                    <line x1="17" y1="9" x2="23" y2="15" />
                  </g>
                </svg>
              </button>
              <button class="minimize">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" role="img">
                  <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 3v6H3" />
                    <path d="M15 21v-6h6" />
                    <path d="M9 21v-6H3" />
                    <path d="M15 3v6h6" />
                  </g>
                </svg>
              </button>
            </div>
            <div class="texts">
              <h1>{{__('messages.welcome-in-video')}}</h1>
              <p>{{__('messages.train-in-video')}}</p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </section>