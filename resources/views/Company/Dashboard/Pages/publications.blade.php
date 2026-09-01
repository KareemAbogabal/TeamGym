@extends('Company.Dashboard.homePageCompany')

@section('title', "Publications")

@section('class', "publications")

@section('content')
  <x-components::main-card state="show" extraClass="main-card-systems" dataFollow="show-card">
    <form action="{{route("updateSystem")}}" method="post">
      @csrf
      <div class="systems systems-card">
      </div>
      <div class="button-row-card">
        <div class="buttons">
          <x-components::close-button follow="show-card" />
          <button type="submit" class="view-profile tg-btn tg-btn--primary">{{__('messages.form-send')}}</button>
        </div>
      </div>
    </form>
  </x-components::main-card>
  <x-components::main-card state="defult" dataFollow="defult-card">
    <div class="body-card">
        <div class="img">
          @php
            $employee = Auth::guard('employee')->user();
          @endphp
          <img src="{{optional($employee)->img ? asset('images/employee/' . optional($employee)->img) : asset('images/header/Team-Gym.png')}}" class="img-profile" alt="No Img" loading="lazy">
          <div class="content">
            <h1>{{$employee->fname}} {{$employee->lname}}</h1>
            <p>{{$employee->job_role}}</p>
          </div>
          @if ($employee->documentation == "true")
            <svg viewBox="0 0 256 256" width="20" height="20">
              <defs>
                <path id="tooth" d="M 0,-110 C 5,-106 10,-98 14,-84 L 6,-62 C 3,-56 0,-54 0,-54 C 0,-54 -3,-56 -6,-62 L -14,-84 C -10,-98 -5,-106 0,-110 Z" />
                <linearGradient id="yellow-white-yellow-45" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stop-color="#f6d93b"/>
                  <stop offset="50%" stop-color="#ffffff"/>
                  <stop offset="100%" stop-color="#f6d93b"/>
                </linearGradient>
              </defs>
              <g transform="translate(128 128)">
                <g fill="url(#yellow-white-yellow-45)" stroke-linejoin="round" transform="scale(1.02)">
                  <use href="#tooth" transform="rotate(0)"/>
                  <use href="#tooth" transform="rotate(10)"/>
                  <use href="#tooth" transform="rotate(20)"/>
                  <use href="#tooth" transform="rotate(30)"/>
                  <use href="#tooth" transform="rotate(40)"/>
                  <use href="#tooth" transform="rotate(50)"/>
                  <use href="#tooth" transform="rotate(60)"/>
                  <use href="#tooth" transform="rotate(70)"/>
                  <use href="#tooth" transform="rotate(80)"/>
                  <use href="#tooth" transform="rotate(90)"/>
                  <use href="#tooth" transform="rotate(100)"/>
                  <use href="#tooth" transform="rotate(110)"/>
                  <use href="#tooth" transform="rotate(120)"/>
                  <use href="#tooth" transform="rotate(130)"/>
                  <use href="#tooth" transform="rotate(140)"/>
                  <use href="#tooth" transform="rotate(150)"/>
                  <use href="#tooth" transform="rotate(160)"/>
                  <use href="#tooth" transform="rotate(170)"/>
                  <use href="#tooth" transform="rotate(180)"/>
                  <use href="#tooth" transform="rotate(190)"/>
                  <use href="#tooth" transform="rotate(200)"/>
                  <use href="#tooth" transform="rotate(210)"/>
                  <use href="#tooth" transform="rotate(220)"/>
                  <use href="#tooth" transform="rotate(230)"/>
                  <use href="#tooth" transform="rotate(240)"/>
                  <use href="#tooth" transform="rotate(250)"/>
                  <use href="#tooth" transform="rotate(260)"/>
                  <use href="#tooth" transform="rotate(270)"/>
                  <use href="#tooth" transform="rotate(280)"/>
                  <use href="#tooth" transform="rotate(290)"/>
                  <use href="#tooth" transform="rotate(300)"/>
                  <use href="#tooth" transform="rotate(310)"/>
                  <use href="#tooth" transform="rotate(320)"/>
                  <use href="#tooth" transform="rotate(330)"/>
                  <use href="#tooth" transform="rotate(340)"/>
                  <use href="#tooth" transform="rotate(350)"/>
                  <circle r="92" fill="url(#yellow-white-yellow-45)" stroke-width="1.4"/>
                </g>
                <path d="M -34 0 L -4 40 L 56 -20" fill="none" transform="translate(-15, -6)" stroke="#000" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
              </g>
            </svg>
          @endif
        </div>
        <form action="" method="post" class="system-form">
          @csrf
          <div class="main-inputs main-inputs-defult">
            <div class="main-input">
              <label for="name-defult">{{__('messages.name-system')}}</label>
              <input type="text" id="name-defult" placeholder="{{__('messages.name-system')}}" value="{{$systemDefult->name}}" class="name-defult" name="name_system">
            </div>
            <div class="main-input">
              <label for="price-defult">{{__('messages.price-system')}}</label>
              <input type="text" id="price-defult" placeholder="{{__('messages.price-system')}}" value="{{$systemDefult->amount}}" class="price-defult" name="price_system">
            </div>
            <div class="main-input">
              <label for="duration-defult">{{__('messages.duration-system')}}</label>
              <input type="text" id="duration-defult" placeholder="{{__('messages.duration-system')}}" value="{{$systemDefult->duration}}" class="duration-defult" name="duration_system">
            </div>
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <x-components::close-button follow="defult-card" />
              <button type="submit" class="view-profile tg-btn tg-btn--primary">{{__('messages.form-send')}}</button>
            </div>
          </div>
        </form>
      </div>
    </x-components::main-card>
  <x-components::main-card state="system" dataFollow="system-card">
    <div class="body-card">
        <div class="img">
          @php
            $employee = Auth::guard('employee')->user();
          @endphp
          <img src="{{optional($employee)->img ? asset('images/employee/' . optional($employee)->img) : asset('images/header/Team-Gym.png')}}" class="img-profile" alt="No Img" loading="lazy">
          <div class="content">
            <h1>{{$employee->fname}} {{$employee->lname}}</h1>
            <p>{{$employee->job_role}}</p>
          </div>
          @if ($employee->documentation == "true")
            <svg viewBox="0 0 256 256" width="20" height="20">
              <defs>
                <path id="tooth" d="M 0,-110 C 5,-106 10,-98 14,-84 L 6,-62 C 3,-56 0,-54 0,-54 C 0,-54 -3,-56 -6,-62 L -14,-84 C -10,-98 -5,-106 0,-110 Z" />
                <linearGradient id="yellow-white-yellow-45" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stop-color="#f6d93b"/>
                  <stop offset="50%" stop-color="#ffffff"/>
                  <stop offset="100%" stop-color="#f6d93b"/>
                </linearGradient>
              </defs>
              <g transform="translate(128 128)">
                <g fill="url(#yellow-white-yellow-45)" stroke-linejoin="round" transform="scale(1.02)">
                  <use href="#tooth" transform="rotate(0)"/>
                  <use href="#tooth" transform="rotate(10)"/>
                  <use href="#tooth" transform="rotate(20)"/>
                  <use href="#tooth" transform="rotate(30)"/>
                  <use href="#tooth" transform="rotate(40)"/>
                  <use href="#tooth" transform="rotate(50)"/>
                  <use href="#tooth" transform="rotate(60)"/>
                  <use href="#tooth" transform="rotate(70)"/>
                  <use href="#tooth" transform="rotate(80)"/>
                  <use href="#tooth" transform="rotate(90)"/>
                  <use href="#tooth" transform="rotate(100)"/>
                  <use href="#tooth" transform="rotate(110)"/>
                  <use href="#tooth" transform="rotate(120)"/>
                  <use href="#tooth" transform="rotate(130)"/>
                  <use href="#tooth" transform="rotate(140)"/>
                  <use href="#tooth" transform="rotate(150)"/>
                  <use href="#tooth" transform="rotate(160)"/>
                  <use href="#tooth" transform="rotate(170)"/>
                  <use href="#tooth" transform="rotate(180)"/>
                  <use href="#tooth" transform="rotate(190)"/>
                  <use href="#tooth" transform="rotate(200)"/>
                  <use href="#tooth" transform="rotate(210)"/>
                  <use href="#tooth" transform="rotate(220)"/>
                  <use href="#tooth" transform="rotate(230)"/>
                  <use href="#tooth" transform="rotate(240)"/>
                  <use href="#tooth" transform="rotate(250)"/>
                  <use href="#tooth" transform="rotate(260)"/>
                  <use href="#tooth" transform="rotate(270)"/>
                  <use href="#tooth" transform="rotate(280)"/>
                  <use href="#tooth" transform="rotate(290)"/>
                  <use href="#tooth" transform="rotate(300)"/>
                  <use href="#tooth" transform="rotate(310)"/>
                  <use href="#tooth" transform="rotate(320)"/>
                  <use href="#tooth" transform="rotate(330)"/>
                  <use href="#tooth" transform="rotate(340)"/>
                  <use href="#tooth" transform="rotate(350)"/>
                  <circle r="92" fill="url(#yellow-white-yellow-45)" stroke-width="1.4"/>
                </g>
                <path d="M -34 0 L -4 40 L 56 -20" fill="none" transform="translate(-15, -6)" stroke="#000" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
              </g>
            </svg>
          @endif
        </div>
        <form action="{{route("addSystem")}}" method="post" class="system-form">
          @csrf
          <div class="main-inputs main-inputs-system">
            <div class="main-input">
              <label for="name-system">{{__('messages.name-system')}}</label>
              <input type="text" id="name-system" placeholder="{{__('messages.name-system')}}" class="name-system" name="name_system">
            </div>
            <div class="main-input">
              <label for="price-system">{{__('messages.price-system')}}</label>
              <input type="text" id="price-system" placeholder="{{__('messages.price-system')}}" class="price-system" name="price_system">
            </div>
            <div class="main-input">
              <label for="duration-system">{{__('messages.duration-system')}}</label>
              <input type="text" id="duration-system" placeholder="{{__('messages.duration-system')}}" class="duration-system" name="duration_system">
            </div>
            <div class="main-input">
              <label for="">{{__('messages.feature-system')}}</label>
              <div class="row-input">
                <input type="text" placeholder="{{__('messages.feature-name')}}" name="feature[]">
                <button type="button" class="remove">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="30" height="30" aria-label="X in circle">
                    <circle cx="32" cy="32" r="28" fill="none" />
                    <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                    <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <div class="main-input input-add-row">
            <label for="">{{__('messages.feature-name')}}</label>
            <div class="row-input">
              <label for="" class="system-feature">
                <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
                  <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
                </svg>
                <span>{{__('messages.add-feature')}}</span>
              </label>
            </div>
          </div>
          <label class="container">
            <input type="checkbox" id="remember-2" name="defult" />
            <svg viewBox="0 0 64 64" height="1em" width="1em">
              <path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16" pathLength="575.0541381835938" class="path"></path>
            </svg>
            <label for="remember-2">{{__('messages.defult')}}</label>
          </label>
          <div class="button-row-card">
            <div class="buttons">
              <x-components::close-button follow="system-card" />
              <button type="submit" class="view-profile tg-btn tg-btn--primary">{{__('messages.form-send')}}</button>
            </div>
          </div>
        </form>
      </div>
    </x-components::main-card>
  <x-components::main-card state="edit" dataFollow="edit-card">
    <div class="body-card">
        <form action="{{route("updateSupplement")}}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="img-products">
            <img src="" id="img-product" alt="No Img" loading="lazy">
            <input type="file" name="img" id="upload-product" class="upload-product">
            <label for="upload-product">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 48" width="40" height="19" role="img" aria-label="simple camera face">
                <rect x="6" y="8" width="52" height="32" rx="6" ry="6" fill="none" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                <rect x="10" y="4" width="10" height="6" rx="1.2" fill="#fff"/>
                <circle cx="50" cy="6" r="3" fill="#fff"/>
                <circle cx="32" cy="24" r="9" fill="none" stroke="#fff" stroke-width="2"/>
                <circle cx="32" cy="24" r="4" fill="#fff"/>
                <circle cx="36" cy="20" r="1.2" fill="#fff" opacity="0.9"/>
              </svg>
            </label>
          </div>
          <div class="main-input">
            <label for="name-product-update">{{__('messages.name-product')}}</label>
            <input type="text" id="name-product-update" name="name_product">
          </div>
          <div class="main-input">
            <label for="price-product-update">{{__('messages.price-product')}}</label>
            <input type="text" id="price-product-update" name="price_product">
          </div>
          <div class="main-input">
            <label for="content-product-update">{{__('messages.description-product')}}</label>
            <input type="text" id="content-product-update" name="content_product">
          </div>
          <div class="main-input">
            <label for="discount-product-update">{{__('messages.discount-product')}}</label>
            <input type="text" id="discount-product-update" name="discount_product">
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <x-components::close-button follow="edit-card" />
              <button type="submit" class="view-profile tg-btn tg-btn--primary">{{__('messages.form-send')}}</button>
            </div>
          </div>
        </form>
      </div>
    </x-components::main-card>
  {{-- <div class="main-card" data-state="show">
    <div class="card">
      <div class="body-card">
        <form action="{{route("addSupplement")}}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="img-products img-products-post">
            <img src="" class="img-product-post" alt="No Img" loading="lazy">
            <input type="file" name="img" id="upload-product-post">
            <label for="upload-product-post">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 48" width="40" height="19" role="img" aria-label="simple camera face">
                <rect x="6" y="8" width="52" height="32" rx="6" ry="6" fill="none" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                <rect x="10" y="4" width="10" height="6" rx="1.2" fill="#fff"/>
                <circle cx="50" cy="6" r="3" fill="#fff"/>
                <circle cx="32" cy="24" r="9" fill="none" stroke="#fff" stroke-width="2"/>
                <circle cx="32" cy="24" r="4" fill="#fff"/>
                <circle cx="36" cy="20" r="1.2" fill="#fff" opacity="0.9"/>
              </svg>
            </label>
          </div>
          <div class="main-input">
            <label for="name-product-update">{{__('messages.name-product')}}</label>
            <input type="text" id="name-product-update" name="name_product">
          </div>
          <div class="main-input">
            <label for="price-product-update">{{__('messages.price-product')}}</label>
            <input type="text" id="price-product-update" name="price_product">
          </div>
          <div class="main-input">
            <label for="content-product-update">{{__('messages.description-product')}}</label>
            <input type="text" id="content-product-update" name="content_product">
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <button type="button" class="close-profile">{{__('messages.card-profile-button-close')}}</button>
              <button type="submit" class="view-profile tg-btn tg-btn--primary">{{__('messages.form-send')}}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div> --}}
  {{-- <div class="main-card" data-state="snacks">
    <div class="card">
      <div class="body-card">
        <div class="img">
          @php
            $employee = Auth::guard('employee')->user();
          @endphp
          <img src="{{optional($employee)->img ? asset('images/employee/' . optional($employee)->img) : asset('images/header/Team-Gym.png')}}" class="img-profile" alt="No Img" loading="lazy">
          <div class="content">
            <h1>{{$employee->fname}} {{$employee->lname}}</h1>
            <p>{{$employee->job_role}}</p>
          </div>
          @if ($employee->documentation == "true")
            <svg viewBox="0 0 256 256" width="20" height="20">
              <defs>
                <path id="tooth" d="M 0,-110 C 5,-106 10,-98 14,-84 L 6,-62 C 3,-56 0,-54 0,-54 C 0,-54 -3,-56 -6,-62 L -14,-84 C -10,-98 -5,-106 0,-110 Z" />
                <linearGradient id="yellow-white-yellow-45" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stop-color="#f6d93b"/>
                  <stop offset="50%" stop-color="#ffffff"/>
                  <stop offset="100%" stop-color="#f6d93b"/>
                </linearGradient>
              </defs>
              <g transform="translate(128 128)">
                <g fill="url(#yellow-white-yellow-45)" stroke-linejoin="round" transform="scale(1.02)">
                  <use href="#tooth" transform="rotate(0)"/>
                  <use href="#tooth" transform="rotate(10)"/>
                  <use href="#tooth" transform="rotate(20)"/>
                  <use href="#tooth" transform="rotate(30)"/>
                  <use href="#tooth" transform="rotate(40)"/>
                  <use href="#tooth" transform="rotate(50)"/>
                  <use href="#tooth" transform="rotate(60)"/>
                  <use href="#tooth" transform="rotate(70)"/>
                  <use href="#tooth" transform="rotate(80)"/>
                  <use href="#tooth" transform="rotate(90)"/>
                  <use href="#tooth" transform="rotate(100)"/>
                  <use href="#tooth" transform="rotate(110)"/>
                  <use href="#tooth" transform="rotate(120)"/>
                  <use href="#tooth" transform="rotate(130)"/>
                  <use href="#tooth" transform="rotate(140)"/>
                  <use href="#tooth" transform="rotate(150)"/>
                  <use href="#tooth" transform="rotate(160)"/>
                  <use href="#tooth" transform="rotate(170)"/>
                  <use href="#tooth" transform="rotate(180)"/>
                  <use href="#tooth" transform="rotate(190)"/>
                  <use href="#tooth" transform="rotate(200)"/>
                  <use href="#tooth" transform="rotate(210)"/>
                  <use href="#tooth" transform="rotate(220)"/>
                  <use href="#tooth" transform="rotate(230)"/>
                  <use href="#tooth" transform="rotate(240)"/>
                  <use href="#tooth" transform="rotate(250)"/>
                  <use href="#tooth" transform="rotate(260)"/>
                  <use href="#tooth" transform="rotate(270)"/>
                  <use href="#tooth" transform="rotate(280)"/>
                  <use href="#tooth" transform="rotate(290)"/>
                  <use href="#tooth" transform="rotate(300)"/>
                  <use href="#tooth" transform="rotate(310)"/>
                  <use href="#tooth" transform="rotate(320)"/>
                  <use href="#tooth" transform="rotate(330)"/>
                  <use href="#tooth" transform="rotate(340)"/>
                  <use href="#tooth" transform="rotate(350)"/>
                  <circle r="92" fill="url(#yellow-white-yellow-45)" stroke-width="1.4"/>
                </g>
                <path d="M -34 0 L -4 40 L 56 -20" fill="none" transform="translate(-15, -6)" stroke="#000" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
              </g>
            </svg>
          @endif
        </div>
        <form action="{{route("addSnack")}}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="main-input">
            <label for="name-snack">{{__('messages.name-snack')}}</label>
            <input type="text" id="name-snack" name="name_snack">
          </div>
          <div class="main-input">
            <label for="price-snack">{{__('messages.price-product')}}</label>
            <input type="text" id="price-snack" name="price_snack">
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <button type="button" class="close-profile">{{__('messages.card-profile-button-close')}}</button>
              <button type="submit" class="view-profile tg-btn tg-btn--primary">{{__('messages.form-send')}}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div> --}}
  <div class="btns-system">
    <button type="button" class="add add-system tg-btn tg-btn--primary" data-follow="system-card">
      <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
      </svg>
      <span>{{__('messages.add-system')}}</span>
    </button>
    {{-- <button class="add add-file">
      <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
      </svg>
      <span>{{__('messages.add-post')}}</span>
    </button> --}}
    {{-- <button class="add add-snacks">
      <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
      </svg>
      <span>{{__('messages.add-snacks')}}</span>
    </button> --}}
    @if ($systemDefult)
      <button type="button" class="add defult-system tg-btn tg-btn--primary" data-follow="defult-card">
        <svg aria-hidden="true" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path stroke-width="2" stroke="#fffffff" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke-linejoin="round" stroke-linecap="round"></path>
          <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#fffffff" d="M17 15V18M17 21V18M17 18H14M17 18H20"></path>
        </svg>
        <span>{{__('messages.defult-system')}}</span>
      </button>
    @endif
  </div>
  <div class="systems">
    <h2>{{__("messages.system")}}</h2>
    <div class="main-systems">
      @if ($systems)
        @foreach ($systems as $item)
          <div class="card system {{$item->name}}">
            <form action="{{route("removeSystem")}}" method="post">
              @csrf
              <input type="hidden" value="{{$item->code}}" name="code">
              <button type="submit" class="remove remove-system">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="18" height="18" aria-label="X in circle">
                  <circle cx="32" cy="32" r="28" fill="none" />
                  <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                  <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                </svg>
              </button>
            </form>
            <input type="hidden" value="{{$item->code}}" name="system_code">
            <div class="ribbon"><span><i class="material-symbols-outlined">electric_bolt</i></span></div>
            <h1>{{ \Illuminate\Support\Facades\Lang::has("messages.sc-4-card-h1-{$item->name}") ? __("messages.sc-4-card-h1-{$item->name}") : $item->name }}</h1>
            <div class="features">
              <div class="add-features">
                @foreach ($item->features as $f)
                  <p>
                    <input type="hidden" value="{{$f->name}}" name="feature_name[]">
                    <label class="container">
                      <input type="checkbox" id="remember" name="feature[]"
                        @if ($f->state == "true")
                          @checked(true)
                        @endif
                      />
                      <svg viewBox="0 0 64 64" height="1em" width="1em">
                        <path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16" pathLength="575.0541381835938" class="path"></path>
                      </svg>
                    </label> {{$f->name}}
                  </p>
                @endforeach
              </div>
            </div>
            <div class="amount">
              <h1>{{$item->amount}} {{__('messages.EGP')}}</h1>
              <p>{{$item->duration}} {{__('messages.month')}}</p>
            </div>
            <button type="button" class="btn-edit-system tg-btn tg-btn--outline" data-follow="show-card">{{__('messages.edit')}}</button>
          </div>
        @endforeach
      @endif
    </div>
  </div>
  <div class="main-product">
    <h2>{{__("messages.nav-systems")}}</h2>
    <div class="products">
      @if ($supplements)
        @foreach ($supplements as $index => $item)
          <div class="product {{ $index == 0 ? 'active' : '' }}"  id="product-{{$index}}">
            <form action="{{route("destroySupplements")}}" method="post">
              @csrf
              <input type="hidden" value="{{$item->code}}" name="code">
              <button type="submit" class="remove-product">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="30" height="30" aria-label="X in circle">
                  <circle cx="32" cy="32" r="28" fill="#000" />
                  <line x1="22" y1="22" x2="42" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                  <line x1="42" y1="22" x2="22" y2="42" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
                </svg>
              </button>
            </form>
            <div class="ribbon right"><span><p class="price-product">{{$item->amount}}</p> EGP</span></div>
            @if ($item->discount)
              <div class="ribbon left"><span><del><p class="discount-product">{{$item->discount}}</p> EGP</del></span></div>
            @endif
            <img src="{{asset("images/products/$item->img")}}" class="img-product" alt="No Img Product">
            <div class="content">
              <h1 class="name-product">{{$item->name}}</h1>
              <p class="content-product">{{$item->description}}</p>
            </div>
            <button type="button" data-code="{{$item->code}}" class="edit-product tg-btn tg-btn--outline" data-follow="edit-card">
              <span>{{__('messages.edit')}}</span>
            </button>
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
  </div>
  <script src="{{asset("js/Company/pages/publications.js")}}"></script>
@endsection
