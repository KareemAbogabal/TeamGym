<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $client->fname }} {{ $client->lname }} — Attendance Barcode</title>
  <link rel="icon" href="{{ asset('images/header/Team-Gym.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: "Cairo", "Segoe UI", Tahoma, Arial, sans-serif; }

    :root {
      --colorGold: rgb(255, 230, 91);
      --colorPearnt: rgb(15, 15, 15);
      --colorReverse: rgb(224, 224, 224);
      --colorParagraph: rgb(150, 150, 150);
    }

    html, body { height: 100%; }

    body {
      min-height: 100vh;
      background:
        radial-gradient(1200px 700px at 80% -10%, rgba(255, 230, 91, 0.10), transparent 60%),
        radial-gradient(1000px 600px at 10% 110%, rgba(255, 230, 91, 0.08), transparent 55%),
        linear-gradient(160deg, rgba(32, 32, 32, 0.72), rgba(12, 12, 12, 0.92)),
        var(--colorPearnt);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 32px 16px;
      gap: 26px;
    }

    body::-webkit-scrollbar { width: 6px; background-color: var(--colorPearnt); }
    body::-webkit-scrollbar-thumb { background-color: var(--colorGold); border-radius: 20px; }

    .toolbar {
      width: 100%;
      max-width: 520px;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 12px;
    }

    .toolbar .btn-print {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 11px 26px;
      font-size: 15px;
      font-weight: 700;
      color: #161616;
      background: linear-gradient(135deg, #ffe65b, var(--colorGold));
      border: none;
      border-radius: 10px;
      cursor: pointer;
      box-shadow: 0 8px 24px rgba(255, 230, 91, 0.22);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .toolbar .btn-print:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(255, 230, 91, 0.34); }
    .toolbar .btn-print:active { transform: translateY(0); }

    .toolbar .btn-close {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 11px 20px;
      font-size: 14px;
      font-weight: 600;
      color: var(--colorReverse);
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 230, 91, 0.22);
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .toolbar .btn-close:hover { background: rgba(255, 230, 91, 0.10); border-color: rgba(255, 230, 91, 0.45); }

    .sheet {
      width: 100%;
      max-width: 520px;
      display: flex;
      justify-content: center;
    }

    .card {
      position: relative;
      width: 100%;
      padding: 34px 32px 30px;
      text-align: center;
      background: rgba(22, 22, 22, 0.6);
      border: 1px solid rgba(255, 230, 91, 0.28);
      border-radius: 18px;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 230, 91, 0.05), 0 0 40px rgba(255, 230, 91, 0.05);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      overflow: hidden;
    }

    .card::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--colorGold), transparent);
    }

    .brand {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-bottom: 4px;
    }

    .brand img { width: 46px; height: 46px; object-fit: contain; }

    .brand .gym {
      font-size: 22px;
      font-weight: 800;
      letter-spacing: 2px;
      text-transform: uppercase;
      background: linear-gradient(135deg, #fffbe6, var(--colorGold), #d9b800);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      color: var(--colorGold);
    }

    .divider {
      width: 64px;
      height: 1px;
      margin: 16px auto 18px;
      background: linear-gradient(90deg, transparent, rgba(255, 230, 91, 0.7), transparent);
    }

    .avatar {
      width: 82px;
      height: 82px;
      margin: 0 auto 14px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 230, 91, 0.5);
      box-shadow: 0 0 24px rgba(255, 230, 91, 0.16);
      padding: 3px;
      background: #161616;
    }

    .name {
      font-size: 20px;
      font-weight: 800;
      color: var(--colorReverse);
      margin-bottom: 6px;
    }

    .meta { font-size: 13px; color: var(--colorParagraph); margin-bottom: 6px; }

    .code-pill {
      display: inline-block;
      padding: 7px 18px;
      margin: 6px 0 20px;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 1px;
      color: var(--colorGold);
      background: rgba(255, 230, 91, 0.08);
      border: 1px solid rgba(255, 230, 91, 0.35);
      border-radius: 30px;
    }

    .barcode-box {
      padding: 18px;
      background: #ffffff;
      border-radius: 12px;
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .barcode-box svg { max-width: 100%; height: auto; display: block; margin: 0 auto; }

    .footer {
      margin-top: 16px;
      font-size: 11px;
      color: var(--colorParagraph);
      letter-spacing: 0.4px;
    }

    @media print {
      .toolbar { display: none; }
      body {
        background: #ffffff;
        padding: 0;
        min-height: auto;
        gap: 0;
      }
      .sheet { max-width: none; }
      .card {
        background: #ffffff;
        border: 1px dashed #999;
        box-shadow: none;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
      }
      .card::before { display: none; }
      .gym { color: #222; -webkit-text-fill-color: #222; background: none; }
      .name { color: #222; }
      .meta, .footer { color: #555; }
      .code-pill { color: #222; border-color: #999; background: transparent; }
      .avatar { border-color: #ccc; box-shadow: none; }
      .brand img { width: 36px; height: 36px; }
      .barcode-box { border: none; padding: 12px; }
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
  </style>
</head>
<body>
  <div class="toolbar">
    <button type="button" class="btn-close tg-btn tg-btn--secondary" onclick="window.close()">Close</button>
    <button type="button" class="btn-print tg-btn tg-btn--primary" onclick="window.print()">Print</button>
  </div>

  <div class="sheet">
    <div class="card">
      <div class="brand">
        <img src="{{ asset('images/header/Team-Gym.png') }}" alt="Team Gym" loading="lazy">
        <span class="gym">Team Gym</span>
      </div>
      <div class="divider"></div>
      <img class="avatar" src="{{ $client->img ? asset('images/subscribers/' . $client->img) : asset('images/header/Team-Gym.png') }}" alt="{{ $client->fname }} {{ $client->lname }}">
      <div class="name">{{ $client->fname }} {{ $client->lname }}</div>
      <div class="meta">{{ __('messages.client-code') }}</div>
      <div class="code-pill">{{ $client->code }}</div>
      <div class="barcode-box">
        <svg id="barcode"></svg>
      </div>
      <div class="footer">{{ __('messages.barcode') }} &middot; {{ $barcode->barcode }} &middot; Team Gym Gym &amp; Fitness</div>
    </div>
  </div>

  <script>
    JsBarcode('#barcode', '{{ $barcode->barcode }}', {
      format: 'ean13',
      width: 3,
      height: 100,
      displayValue: true,
      margin: 6,
      fontSize: 18,
    });
  </script>
</body>
</html>
