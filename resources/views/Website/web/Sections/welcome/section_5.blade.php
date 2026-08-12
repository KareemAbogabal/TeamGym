<section class="section-5">
  <x-web::header title="{!!__('messages.sc-5-h1')!!}" paragraph="{{__('messages.sc-5-paragraph')}}"/>
  <div class="subscription">
    <img src="{{asset("images/content/location.jpeg")}}" alt="No Img Form">
    <form action="{{route("addRequestCustomer")}}" method="post">
      @csrf
      <h1>{{__('messages.sc-5-form')}}</h1>
      <div class="row">
        <div class="pearnt-input">
          <label for="fname">{{__('messages.form-fname')}}</label>
          <input type="text" id="fname" name="fname" class="input" />
        </div>
        <div class="pearnt-input">
          <label for="lname">{{__('messages.form-lname')}}</label>
          <input type="text" id="lname" name="lname" class="input" />
        </div>
      </div>
      <div class="pearnt-input">
        <label for="phone" class="phone">{{__('messages.sc-5-form-phone')}}</label>
        <input type="text" id="phone" name="phone">
      </div>
      <input type="hidden" value="system" name="type">
      <div class="pearnt-input">
        <label for="email" class="email">{{__('messages.sc-5-form-email')}}</label>
        <input type="text" id="email" name="email">
      </div>
      <div class="pearnt-input">
        <label for="system" class="add-system-lable">{{__('messages.sc-5-form-system')}}</label>
        <div contenteditable="true" class="add-system">
        </div>
        <input type="text" name="system" id="system">
      </div>
      <div class="systems-form">
        @if ($systems)
          @foreach ($systems as $item)
            <div class="system card-system {{$item->name}}">
              <p class="name-system" data-code="{{$item->code}}" data-name="{{$item->name}}" data-amount="{{$item->amount}}">{{ \Illuminate\Support\Facades\Lang::has("messages.sc-4-card-h1-{$item->name}") ? __("messages.sc-4-card-h1-{$item->name}") : $item->name }}</p>
            </div>
          @endforeach
        @endif
      </div>
      <button type="submit">
        <div class="svg-wrapper-1">
          <div class="svg-wrapper">
            <svg viewBox="0 0 24 24" width="24" height="24">
              <path fill="none" d="M0 0h24v24H0z"></path>
              <path fill="currentColor" d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path>
            </svg>
          </div>
        </div>
        <span>{{__('messages.sc-5-form-button')}}</span>
      </button>
    </form>
  </div>
</section>
