<section class="section-1" id="section-1">
  <div class="content">
    <h1>{{__('messages.contact-us-title')}}</h1>
    <p>{{__('messages.contact-us-desc')}}</p>
    <form action="{{route("contactUsStore")}}" method="post" class="contact-form">
      @csrf
      <div class="main-input">
        <label for="name">{{__('messages.name')}}</label>
        <input type="text" id="name" name="name" value="{{old('name')}}" required>
      </div>
      <div class="main-input">
        <label for="phone">{{__('messages.form-phone')}}</label>
        <input type="text" id="phone" name="phone" value="{{old('phone')}}" required>
      </div>
      <div class="main-input">
        <label for="subject">{{__('messages.subject')}}</label>
        <textarea id="subject" name="subject" rows="5" required>{{old('subject')}}</textarea>
      </div>
      <button type="submit" class="card-btn btn-gold">{{__('messages.form-send')}}</button>
    </form>
  </div>
</section>
