@extends('Company.Dashboard.homePageCompany')

@section('title', "Users")

@section('class', "users")

@section('content')
  <div class="content-table">
    <h1>{{__('messages.employees')}}</h1>
  </div>
  <x-components::main-card state="edit" dataFollow="edit-card">
    <div class="header-bg">
        <img src="{{ asset('images/bg-profile-clients/bg-clients.jpg') }}" alt="No Img" loading="lazy">
        <button type="button">
          <svg width="40" height="40" viewBox="0 0 64 64" aria-hidden="true">
            <g stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
              <line x1="12" y1="12" x2="52" y2="52"/>
              <line x1="52" y1="12" x2="12" y2="52"/>
            </g>
          </svg>
        </button>
      </div>
      <div class="body-card">
        <div class="img img-card">
          <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-card" alt="No Img" loading="lazy">
          <div class="content">
            <h1 class="full-name-card"></h1>
            <p>client</p>
          </div>
        </div>
        <form action="{{route("updateClient")}}" method="post">
          @csrf
          <div class="main-input">
            <label for="full-name-card">{{__('messages.form-full-name')}}</label>
            <div class="row-input">
              <input type="text" id="full-name-card" class="fname-card" name="fname">
              <input type="text" class="lname-card" name="lname">
            </div>
          </div>
          <input type="hidden" id="phone-card" class="code" name="code">
          <div class="main-input">
            <label for="communication-card">{{__('messages.form-communication')}}</label>
            <div class="row-input">
              <input type="text" id="communication-card" class="email-card" name="email" placeholder="{{__('messages.form-email')}}">
              <input type="text" class="phone-card" name="phone" placeholder="{{__('messages.form-phone')}}">
            </div>
          </div>
          <div class="main-input">
            <label for="state">{{__('messages.state')}}</label>
            <input type="text" id="state-card" class="state-card" name="category">
          </div>
          <div class="main-input">
            <label for="password">{{__('messages.form-password')}}</label>
            <input type="text" id="password-card" class="password-card" name="password">
          </div>
          <div class="main-switch">
            <div>
              <div class="item-title">{{__('messages.form-documentation')}}</div>
              <div class="item-sub">{{__('messages.form-documentation-d')}}</div>
            </div>
            <label class="switch">
              <input type="checkbox" class="documentation-input" name="documentation">
              <span class="slider"></span>
            </label>
          </div>
          <div class="button-row-card">
            <div class="buttons">
              <button type="button" class="close-profile" data-follow="edit-card">{{__('messages.card-profile-button-close')}}</button>
              <button type="submit" class="view-profile">{{__('messages.form-send')}}</button>
            </div>
          </div>
        </form>
      </div>
    </x-components::main-card>
  <x-components::main-card state="list" dataFollow="list-card">
    <div class="header-bg"></div>
    <div class="body-card">
        <div class="head">
          <div class="img">
            <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-card" alt="No Img" loading="lazy">
            <div class="content">
              <h1 class="full-name-card"></h1>
              <p>client</p>
            </div>
          </div>
          <div class="charts">
            <div class="side">
              <h1>{{__('messages.your-plan')}} <span class="name-category" data-category="pro"></span></h1>
              <p>available</p>
            </div>
            <div class="char">
              <canvas id="chart-1" data-amount="1000" data-paid="500"></canvas>
              <p><span class="amount"></span> {{__('messages.EGP')}}</p>
            </div>
            <div class="side">
              <div class="color">
                <div>
                  <span></span>
                  <p>{{__('messages.paid')}}</p>
                </div>
                <p><span class="paid"></span> {{__('messages.EGP')}}</p>
              </div>
              <div class="color">
                <div>
                  <span></span>
                  <p>{{__('messages.residual')}}</p>
                </div>
                <p><span class="residual"></span> {{__('messages.EGP')}}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="data-client">
          <main>
            <div class="main-char">
              <div class="char">
                <canvas class="chart-show"></canvas>
              </div>
            </div>
            <div class="lineage">
              <ul>
                <li>{{__('messages.lean')}}</li>
                <ul>
                  <li>{{__('messages.right_arm_lean')}}: <span class="right-arm-lean-kg">{{$minor['right_arm_lean'] ?? 0}}</span>{{__('messages.kg')}}</li>
                  <li>{{__('messages.left_arm_lean')}}: <span class="left-arm-lean-kg">{{$minor['left_arm_lean'] ?? 0}}</span>{{__('messages.kg')}}</li>
                  <li>{{__('messages.right_leg_lean')}}: <span class="right-leg-lean-kg">{{$minor['right_leg_lean'] ?? 0}}</span>{{__('messages.kg')}}</li>
                  <li>{{__('messages.left_leg_lean')}}: <span class="left-leg-lean-kg">{{$minor['left_leg_lean'] ?? 0}}</span>{{__('messages.kg')}}</li>
                </ul>
                <li>{{__('messages.fats')}}</li>
                <ul>
                  <li>{{__('messages.right_arm_fat')}}: <span class="right-arm-fat-kg">{{$minor['right_arm_fat'] ?? 0}}</span>{{__('messages.kg')}}</li>
                  <li>{{__('messages.left_arm_fat')}}: <span class="left-arm-fat-kg">{{$minor['left_arm_fat'] ?? 0}}</span>{{__('messages.kg')}}</li>
                  <li>{{__('messages.right_leg_fat')}}: <span class="right-leg-fat-kg">{{$minor['right_leg_fat'] ?? 0}}</span>{{__('messages.kg')}}</li>
                  <li>{{__('messages.left_leg_fat')}}: <span class="left-leg-fat-kg">{{$minor['left_leg_fat'] ?? 0}}</span>{{__('messages.kg')}}</li>
                </ul>
              </ul>
              <ul>
                <li>{{__('messages.weight')}}: <span class="weight"></span> kg</li>
                <li>{{__('messages.bmi')}}: <span class="BMI"></span> %</li>
                <li>{{__('messages.pbf_percent')}}: <span class="PBF-percent"></span> %</li>
                <li>{{__('messages.smm_kg')}}: <span class="SMM-kg"></span> kg</li>
                <li>{{__('messages.kcal')}}: <span class="kcal"></span> kg</li>
                <li>{{__('messages.total_body_water')}}: <span class="water"></span> L</li>
                <li>{{__('messages.body_fat_mass')}}: <span class="body-fat-mass"></span> kg</li>
                <li>{{__('messages.protein_kg')}}: <span class="protein-kg"></span> kg</li>
              </ul>
            </div>
          </main>
          <div class="data-table">
          </div>
        </div>
        <div class="button-row-card">
          <div class="buttons">
            <button type="button" class="close-profile" data-follow="list-card">{{__('messages.card-profile-button-close')}}</button>
          </div>
        </div>
      </div>
    </x-components::main-card>
  <div class="employees">
    @if ($employees)
      @foreach ($employees as $item)
        <div class="card-employees">
          <div class="header">
            <p class="active">active</p>
            <form action="{{route("destroy")}}" method="post">
              @csrf
              <input type="hidden" value="{{$item->id}}" name="id">
              <input type="hidden" value="employee" name="state">
              <button type="submit">
                <svg width="30" height="30" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                  <g stroke="var(--colorSVG1)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
                    <rect x="14" y="10" width="36" height="6" rx="2"/>
                    <rect x="26" y="8" width="12" height="4" rx="1" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="1"/>
                    <path d="M16 20 L48 20 L44 54 L20 54 Z" />
                    <path d="M24 26 L26 48" />
                    <path d="M32 26 L32 48" />
                    <path d="M40 26 L38 48" />
                    <path d="M20 54h24" stroke-width="3" stroke-linecap="round"/>
                  </g>
                </svg>
              </button>
            </form>
          </div>
          <div class="body">
            <img src="{{optional($item)->img ? asset('images/employee/' . optional($item)->img) : asset('images/header/Team-Gym.png')}}" alt="No Img" loading="lazy">
            <div class="content">
              <h1>{{$item->fname}} {{$item->lname}}</h1>
              <p>{{$item->job_role}}</p>
            </div>
          </div>
          <div class="footer">
            <button class="show-details-employee" data-follow="employee-card">{{__('messages.details')}}</button>
          </div>
          <x-components::main-card state="employee" dataFollow="employee-card">
              <div class="header-bg">
                <img src="{{asset('images/bg-profile-clients/bg-clients.jpg')}}" alt="No Img" loading="lazy">
                <button type="button">
                  <svg width="40" height="40" viewBox="0 0 64 64" aria-hidden="true">
                    <g stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
                      <line x1="12" y1="12" x2="52" y2="52"/>
                      <line x1="52" y1="12" x2="12" y2="52"/>
                    </g>
                  </svg>
                </button>
              </div>
              <div class="body-card">
                <div class="img">
                  <img src="{{ $item->img ? asset('images/employee/' . $item->img) : asset('images/header/Team-Gym.png') }}" class="img-profile" alt="No Img" loading="lazy">
                  <div class="content">
                    <h1>{{$item->fname}} {{$item->lname}}</h1>
                    <p>{{$item->job_role}}</p>
                  </div>
                  @if ($item->documentation == "true")
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
                <form action="{{route("updateEmployee")}}" method="post">
                  @csrf
                  <div class="main-input">
                    <label for="full-name-employee">{{__('messages.form-full-name')}}</label>
                    <div class="row-input">
                      <input type="text" id="full-name-employee" value="{{$item->fname}}" name="fname-employee">
                      <input type="text" value="{{$item->lname}}" name="lname-employee">
                    </div>
                  </div>
                  <input type="hidden" id="code" value="{{$item->code}}" name="code-employee">
                  <div class="main-input">
                    <label for="phone-employee">{{__('messages.form-phone')}}</label>
                    <input type="text" id="phone-employee" value="{{$item->phone}}" name="phone-employee">
                  </div>
                  <div class="main-input">
                    <label for="email-employee">{{__('messages.form-email')}}</label>
                    <input type="text" id="email-employee" value="{{$item->email}}" name="email-employee">
                  </div>
                  <div class="main-input">
                    <label for="password-employee">{{__('messages.form-password')}}</label>
                    <input type="text" id="password-employee" value="{{$item->password}}" name="password-employee">
                  </div>
                  <div class="main-switch">
                    <div>
                      <div class="item-title">{{__('messages.form-documentation')}}</div>
                      <div class="item-sub">{{__('messages.form-documentation-d')}}</div>
                    </div>
                    <label class="switch">
                      <input type="checkbox" name="documentation-employee" @if ($item->documentation == "true") checked @endif>
                      <span class="slider"></span>
                    </label>
                  </div>
                  <div class="button-row-card">
                    <div class="buttons">
                      <button type="button" class="close-profile" data-follow="employee-card">{{__('messages.card-profile-button-close')}}</button>
                      <button type="submit" class="view-profile">{{__('messages.form-send')}}</button>
                    </div>
                  </div>
                </form>
              </div>
            </x-components::main-card>
        </div>
      @endforeach
    @endif
  </div>
  <div class="content-table">
    <h1>{{__('messages.clients')}}</h1>
    <input type="text" class="search-input" placeholder="{{__('messages.search')}}">
  </div>
  <main>
    <x-components::table :header="[__('messages.name'), __('messages.phone'), __('messages.state'), __('messages.date'), __('messages.details')]">
      @if ($clients)
        @foreach ($clients as $item)
          <div class="row"
            data-points-smm='@json($dataLineage[$item->code]["SMM"])'
            data-points-fat='@json($dataLineage[$item->code]["fat_mass"])'
            data-img="{{ $item->img ? asset('images/subscribers/' . $item->img) : asset('images/header/Team-Gym.png') }}"
            data-communication="{{$item->email}}"
            data-documentation="{{$item->documentation}}"
            data-code="{{$item->code}}"
            data-weight="{{ $dataValues[$item->code]['weight'] ?? 0 }}"
            data-water="{{ $dataValues[$item->code]['water'] ?? 0 }}"
            data-BMI="{{ $dataValues[$item->code]['BMI'] ?? 0 }}"
            data-PBF="{{ $dataValues[$item->code]['PBF'] ?? 0 }}"
            data-SMM="{{ $dataValues[$item->code]['SMM'] ?? 0 }}"
            data-kcal="{{ $dataValues[$item->code]['kcal'] ?? 0 }}"
            data-fat_mass="{{ $dataValues[$item->code]['fat_mass'] ?? 0 }}"
            data-protein="{{ $dataValues[$item->code]['protein'] ?? 0 }}"
            data-left_arm_lean="{{ $dataValues[$item->code]['left_arm_lean'] ?? 0 }}"
            data-right_arm_lean="{{ $dataValues[$item->code]['right_arm_lean'] ?? 0 }}"
            data-left_leg_lean="{{ $dataValues[$item->code]['left_leg_lean'] ?? 0 }}"
            data-right_leg_lean="{{ $dataValues[$item->code]['right_leg_lean'] ?? 0 }}"
            data-left_arm_fat="{{ $dataValues[$item->code]['left_arm_fat'] ?? 0 }}"
            data-right_arm_fat="{{ $dataValues[$item->code]['right_arm_fat'] ?? 0 }}"
            data-left_leg_fat="{{ $dataValues[$item->code]['left_leg_fat'] ?? 0 }}"
            data-right_leg_fat="{{ $dataValues[$item->code]['right_leg_fat'] ?? 0 }}"
          >
            <p class="search"><img src="{{ $item->img ? asset('images/subscribers/' . $item->img) : asset('images/header/Team-Gym.png') }}" alt="No Img" loading="lazy">{{$item->fname}} {{$item->lname}}</p>
            <p class="phone">{{$item->phone}}</p>
            <p data-state="{{$item->category}}" class="state">{{$item->category}}</p>
            <p>{{$item->created_at}}</p>
            <div class="content-row">
              <button type="button" class="edit" data-follow="edit-card">
                <svg width="30" height="30" viewBox="0 0 64 64" style="transform: rotate(145deg)" fill="none" aria-hidden="true">
                  <g stroke="var(--colorSVG1)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none">
                    <rect x="6"  y="26" width="6"  height="12" rx="1"/>
                    <rect x="12" y="26" width="36" height="12" rx="1"/>
                    <polygon points="48,26 60,32 48,38"/>
                  </g>
                </svg>
              </button>
              <form action="{{route("destroy")}}" method="post">
                @csrf
                <input type="hidden" value="{{$item->id}}" name="id">
                <input type="hidden" value="client" name="state">
                <button type="button">
                  <svg width="30" height="30" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <g stroke="var(--colorSVG1)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none">
                      <rect x="14" y="10" width="36" height="6" rx="2"/>
                      <rect x="26" y="8" width="12" height="4" rx="1" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="1"/>
                      <path d="M16 20 L48 20 L44 54 L20 54 Z" />
                      <path d="M24 26 L26 48" />
                      <path d="M32 26 L32 48" />
                      <path d="M40 26 L38 48" />
                      <path d="M20 54h24" stroke-width="3" stroke-linecap="round"/>
                    </g>
                  </svg>
                </button>
              </form>
              <button type="button" class="show" data-follow="list-card">
                <svg width="30" height="30" viewBox="0 0 64 64" aria-hidden="true">
                  <circle cx="32" cy="22" r="3" fill="var(--colorSVG1)"/>
                  <circle cx="32" cy="32" r="3" fill="var(--colorSVG1)"/>
                  <circle cx="32" cy="42" r="3" fill="var(--colorSVG1)"/>
                </svg>
              </button>
            </div>
          </div>
        @endforeach
      @endif
    </x-components::table>
  </main>
  <script>
    let name = @json(__('messages.name'));
    let orderName = @json(__('messages.order-name'));
    let amount = @json(__('messages.amount'));
    let paid = @json(__('messages.paid'));
    let payDay = @json(__('messages.pay-day'));
    let date = @json(__('messages.date'));
    let type = @json(__('messages.type'));
    let day = @json(__('messages.day'));
    let month = @json(__('messages.month'));
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{asset("js/Company/pages/users.js")}}"></script>
@endsection
