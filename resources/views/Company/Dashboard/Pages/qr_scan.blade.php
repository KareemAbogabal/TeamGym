@extends('Company.Dashboard.homePageCompany')

@section('title', 'QR Scanner')

@section('class', 'qr-scan')

@section('content')
<main>
  <section class="qr-terminal">
    <h3>{{ __('messages.qr-terminal') }}</h3>
    <p>{{ __('messages.qr-terminal-desc') }}</p>

    <input type="text" id="scanToken" placeholder="{{ __('messages.scan-token-placeholder') }}" />

    <div class="actions">
      <button type="button" id="btnLookup" class="btn">{{ __('messages.lookup') }}</button>
      <button type="button" id="btnRecord" class="btn primary">{{ __('messages.check-in-out') }}</button>
      <button type="button" id="btnRecordCode" class="btn" data-code>{{ __('messages.check-in-out-code') }}</button>
      <button type="button" id="btnRecordBarcode" class="btn" data-barcode>{{ __('messages.scan-attendance-barcode') }}</button>
    </div>

    <div id="scanResult" class="result"></div>
  </section>
</main>

<script>
  (function () {
    var csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    function token() { return document.getElementById('scanToken').value.trim(); }
    function post(url, body) {
      return fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json',
        },
        body: JSON.stringify(body),
      }).then(function (r) { return r.json(); });
    }
    function esc(s) {
      return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
      });
    }
    function fmtTime(iso) {
      if (!iso) return '';
      var d = new Date(iso);
      var h = d.getHours(), m = d.getMinutes();
      var ap = h >= 12 ? 'PM' : 'AM';
      h = h % 12; if (h === 0) h = 12;
      function p(n){ return (n < 10 ? '0' : '') + n; }
      return h + ':' + p(m) + ' ' + ap;
    }
    function showResult(data) {
      var box = document.getElementById('scanResult');
      if (data.ok) {
        // EAN-13 barcode result (object client with name/code + barcode + state).
        if (data.client && typeof data.client === 'object' && data.client.name) {
          var stateLabel = data.state === 'entrance'
            ? '{{ __("messages.attendance-entrance") }}'
            : '{{ __("messages.attendance-exit") }}';
          var html = '<strong>' + esc(data.client.name) + '</strong>';
          html += '<br/><small>' + '{{ __("messages.client-code") }}: ' + esc(data.client.code) + '</small>';
          if (data.barcode) html += '<br/><small>' + '{{ __("messages.barcode") }}: ' + esc(data.barcode) + '</small>';
          html += '<br/><strong class="state">' + esc(stateLabel) + '</strong>';
          if (data.at) html += '<br/><span class="time">' + fmtTime(data.at) + '</span>';
          box.innerHTML = html;
          box.className = 'result ok';
          return;
        }
        if (data.client && typeof data.client === 'object') {
          var html = '<strong>' + esc(data.client.name) + '</strong><br/><small>' + esc(data.client.code || '') + ' &middot; ' + esc(data.client.category || '') + '</small>';
          html += '<br/><span class="pill ' + (data.currently_inside ? 'inside' : 'outside') + '">' +
            (data.currently_inside ? '{{ __("messages.inside") }}' : '{{ __("messages.outside") }}') + '</span>';
          box.innerHTML = html;
          box.className = 'result ok';
          return;
        }
        box.innerHTML = '<strong>' + esc(data.client || '') + '</strong><br/><span class="pill">' + esc(data.state || '') + '</span>';
        box.className = 'result ok';
      } else {
        box.textContent = data.message || '{{ __("messages.error") }}';
        box.className = 'result err';
      }
    }
    document.getElementById('btnLookup').addEventListener('click', function () {
      post('{{ route("qrScan.scan") }}', { token: token() }).then(showResult);
    });
    document.getElementById('btnRecord').addEventListener('click', function () {
      post('{{ route("qrScan.record") }}', { token: token() }).then(showResult);
    });
    document.getElementById('btnRecordCode').addEventListener('click', function () {
      post('{{ route("qrScan.recordCode") }}', { code: token() }).then(showResult);
    });
    document.getElementById('btnRecordBarcode').addEventListener('click', function () {
      post('{{ route("qrScan.recordBarcode") }}', { barcode: token() }).then(showResult);
    });
  })();
</script>
@endsection
