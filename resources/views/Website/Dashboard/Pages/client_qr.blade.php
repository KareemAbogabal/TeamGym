@extends('Website.Dashboard.homePage')

@section('title', 'My QR Code')

@section('class', 'client-qr')

@section('content')
<main>
  <section class="qr-card">
    <h3>{{ __('messages.my-qr') }}</h3>
    <p>{{ __('messages.my-qr-desc') }}</p>

    <div class="qr-box" id="qrBox">
      @if ($raw)
        <img id="qrImage" alt="QR Code" style="display:none" />
        <div id="qrPlaceholder">
          <p>{{ __('messages.qr-generating') }}</p>
        </div>
      @else
        <p>{{ __('messages.qr-unavailable') }}</p>
      @endif
    </div>

    <script>
      (function () {
        var raw = @json($raw ?? null);
        if (!raw) return;
        function renderQr(url) {
          var img = document.getElementById("qrImage");
          img.src = url;
          img.style.display = "inline-block";
          document.getElementById("qrPlaceholder")?.remove();
        }
        if (window.QRCode) {
          var qr = new QRCode(document.getElementById("qrBox"), { width: 220, height: 220 });
          qr.makeCode(raw);
          document.getElementById("qrPlaceholder")?.remove();
        } else {
          var q = document.createElement("script");
          q.src = "https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js";
          q.onload = function () {
            var el = document.getElementById("qrBox");
            var qr = new QRCode(el, { width: 220, height: 220 });
            qr.makeCode(raw);
            document.getElementById("qrPlaceholder")?.remove();
          };
          q.onerror = function () { renderQr("https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" + encodeURIComponent(raw)); };
          document.head.appendChild(q);
        }
      })();
    </script>

    <p class="qr-hint">{{ __('messages.qr-keep-private') }}</p>

    <form method="post" action="{{ route('myQr.rotate') }}">
      @csrf
      <button type="submit" class="btn tg-btn tg-btn--primary">{{ __('messages.rotate-qr') }}</button>
    </form>

    <p class="qr-status">
      {{ $active ? __('messages.qr-status', ['status' => $active->status]) : __('messages.qr-unavailable') }}
    </p>
  </section>
</main>
@endsection
