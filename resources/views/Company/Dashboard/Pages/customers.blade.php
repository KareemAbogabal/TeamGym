@extends('Company.Dashboard.homePageCompany')

@section('title', "Customers")

@section('class', "customers")

@section('content')
  <x-components::main-card state="edit" dataFollow="edit-card">
    <div class="body-card">
      <div class="img img-card">
        <img src="{{ asset('images/header/Team-Gym.png') }}" class="img-card" alt="No Img" loading="lazy">
        <div class="content">
          <h1 class="full-name-card"></h1>
          <p>client</p>
        </div>
      </div>
      <form action="{{ route('customers.updateClient') }}" method="post">
        @csrf
        <div class="main-input">
          <label for="full-name-card">{{ __('messages.form-full-name') }}</label>
          <div class="row-input">
            <input type="text" id="full-name-card" class="fname-card" name="fname">
            <input type="text" class="lname-card" name="lname">
          </div>
        </div>
        <input type="hidden" id="phone-card" class="code" name="code">
        <div class="main-input">
          <label for="communication-card">{{ __('messages.form-communication') }}</label>
          <div class="row-input">
            <input type="text" id="communication-card" class="email-card" name="email" placeholder="{{ __('messages.form-email') }}">
            <input type="text" class="phone-card" name="phone" placeholder="{{ __('messages.form-phone') }}">
          </div>
        </div>
        <div class="main-input">
          <label for="state">{{ __('messages.state') }}</label>
          <input type="text" id="state-card" class="state-card" name="category">
        </div>
        <div class="main-input">
          <label for="password">{{ __('messages.form-password') }}</label>
          <input type="text" id="password-card" class="password-card" name="password">
        </div>
        <div class="main-switch">
          <div>
            <div class="item-title">{{ __('messages.form-documentation') }}</div>
            <div class="item-sub">{{ __('messages.form-documentation-d') }}</div>
          </div>
          <label class="switch">
            <input type="checkbox" class="documentation-input" name="documentation">
            <span class="slider"></span>
          </label>
        </div>
        <div class="button-row-card">
          <div class="buttons">
            <x-components::close-button follow="edit-card" />
            <button type="submit" class="view-profile">{{ __('messages.form-send') }}</button>
          </div>
        </div>
      </form>
    </div>
  </x-components::main-card>

  <x-components::main-card state="list" dataFollow="list-card">
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
            <h1>{{ __('messages.your-plan') }} <span class="name-category" data-category="pro"></span></h1>
            <p>available</p>
          </div>
          <div class="char">
            <canvas id="chart-1" data-amount="1000" data-paid="500"></canvas>
            <p><span class="amount"></span> {{ __('messages.EGP') }}</p>
          </div>
          <div class="side">
            <div class="color">
              <div>
                <span></span>
                <p>{{ __('messages.paid') }}</p>
              </div>
              <p><span class="paid"></span> {{ __('messages.EGP') }}</p>
            </div>
            <div class="color">
              <div>
                <span></span>
                <p>{{ __('messages.residual') }}</p>
              </div>
              <p><span class="residual"></span> {{ __('messages.EGP') }}</p>
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
            <div class="lineage-block">
              <h1 class="lineage-title">{{ __('messages.lean') }} &amp; {{ __('messages.fats') }}</h1>
              <div class="metric-grid">
                <div class="metric-tile"><span class="metric-label">{{ __('messages.right_arm_lean') }}</span><span class="metric-value"><span class="right-arm-lean-kg"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.left_arm_lean') }}</span><span class="metric-value"><span class="left-arm-lean-kg"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.right_leg_lean') }}</span><span class="metric-value"><span class="right-leg-lean-kg"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.left_leg_lean') }}</span><span class="metric-value"><span class="left-leg-lean-kg"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.right_arm_fat') }}</span><span class="metric-value"><span class="right-arm-fat-kg"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.left_arm_fat') }}</span><span class="metric-value"><span class="left-arm-fat-kg"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.right_leg_fat') }}</span><span class="metric-value"><span class="right-leg-fat-kg"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.left_leg_fat') }}</span><span class="metric-value"><span class="left-leg-fat-kg"></span> {{ __('messages.kg') }}</span></div>
              </div>
            </div>
            <div class="lineage-block">
              <h1 class="lineage-title">{{ __('messages.profile-body-metrics') }}</h1>
              <div class="metric-grid">
                <div class="metric-tile"><span class="metric-label">{{ __('messages.weight') }}</span><span class="metric-value"><span class="weight"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.bmi') }}</span><span class="metric-value"><span class="BMI"></span> %</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.pbf_percent') }}</span><span class="metric-value"><span class="PBF-percent"></span> %</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.smm_kg') }}</span><span class="metric-value"><span class="SMM-kg"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.kcal') }}</span><span class="metric-value"><span class="kcal"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.total_body_water') }}</span><span class="metric-value"><span class="water"></span> L</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.body_fat_mass') }}</span><span class="metric-value"><span class="body-fat-mass"></span> {{ __('messages.kg') }}</span></div>
                <div class="metric-tile"><span class="metric-label">{{ __('messages.protein_kg') }}</span><span class="metric-value"><span class="protein-kg"></span> {{ __('messages.kg') }}</span></div>
              </div>
            </div>
          </div>
        </main>
        <div class="data-table">
        </div>
        <div class="side-panels">
          <div class="barcode-status-panel">
            <div class="barcode-box">
              <p class="barcode-title">{{ __('messages.barcode') }}</p>
              <div id="clientBarcodeBox"></div>
            </div>
            <div class="barcode-status-row" data-for="barcode">
              <span class="barcode-status-label">{{ __('messages.barcode') }}</span>
              <span class="barcode-status-value"></span>
            </div>
            <div class="barcode-status-row" data-for="status">
              <span class="barcode-status-label">{{ __('messages.status') }}</span>
              <span class="barcode-status-value barcode-status-badge"></span>
            </div>
            <div class="barcode-status-row" data-for="last_scanned_at">
              <span class="barcode-status-label">{{ __('messages.qr-last-scanned-at') }}</span>
              <span class="barcode-status-value"></span>
            </div>
            <div class="barcode-status-row" data-for="scan_count">
              <span class="barcode-status-label">{{ __('messages.qr-scan-count') }}</span>
              <span class="barcode-status-value"></span>
            </div>
            <div class="barcode-actions">
              <button type="button" class="btn-barcode" id="btnBarcodePrint">{{ __('messages.print') }}</button>
              <button type="button" class="btn-barcode" id="btnBarcodeRegenerate">{{ __('messages.regenerate') }}</button>
              <button type="button" class="btn-barcode danger" id="btnBarcodeRevoke">{{ __('messages.revoke') }}</button>
            </div>
          </div>
        </div>
      </div>
      <div class="button-row-card">
        <div class="buttons">
          <x-components::close-button follow="list-card" />
        </div>
      </div>
    </div>
  </x-components::main-card>

  <main class="main-tabel-row-search">
    <div class="content">
      <h1>{{ __('messages.clients') }}</h1>
      <input type="text" class="search-input" placeholder="{{ __('messages.search') }}">
    </div>
    <x-components::table :header="[__('messages.name'), __('messages.phone'), __('messages.state'), __('messages.date'), __('messages.details')]">
      @if ($clients)
        @foreach ($clients as $item)
          <div class="row"
            data-points-smm='@json($dataLineage[$item->code]["SMM"])'
            data-points-fat='@json($dataLineage[$item->code]["fat_mass"])'
            data-img="{{ $item->img ? asset('images/subscribers/' . $item->img) : asset('images/header/Team-Gym.png') }}"
            data-communication="{{ $item->email }}"
            data-documentation="{{ $item->documentation }}"
            data-code="{{ $item->code }}"
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
            <p class="search"><img src="{{ $item->img ? asset('images/subscribers/' . $item->img) : asset('images/header/Team-Gym.png') }}" alt="No Img" loading="lazy">{{ $item->fname }} {{ $item->lname }}</p>
            <p class="phone">{{ $item->phone }}</p>
            <p data-state="{{ $item->category }}" class="state">{{ $item->category }}</p>
            <p>{{ $item->created_at }}</p>
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
              <form action="{{ route('customers.destroy') }}" method="post">
                @csrf
                <input type="hidden" value="{{ $item->id }}" name="id">
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
    let qrActiveLabel = @json(__('messages.qr-status-active'));
    let qrRevokedLabel = @json(__('messages.qr-status-revoked'));
    let qrExpiredLabel = @json(__('messages.qr-status-expired'));
    let qrGeneratingLabel = @json(__('messages.qr-generating'));
    let qrUnavailableLabel = @json(__('messages.qr-unavailable'));
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
  <script src="{{ asset('js/Company/pages/customers.js') }}"></script>
@endsection
