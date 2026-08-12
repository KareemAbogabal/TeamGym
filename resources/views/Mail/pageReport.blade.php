<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login Notification Card</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --yellow: #FFEE53;
      --purple: #f8bf43;
      --muted: #4b4b4b;
      --card-width: 600px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family:  "Lato", Arial, sans-serif;
      background: linear-gradient(180deg, #fff 0%, #f7f7f8 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
      color: var(--muted);
    }

    .wrapper {
      width: 100%;
      max-width: var(--card-width);
    }

    .card {
      background:  var(--yellow);
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 12px 30px rgba(50,8,165,0.12);
      border: 1px solid rgba(0,0,0,0.04);
    }

    .card-header {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 22px;
      background: linear-gradient(90deg, rgba(255,238,83,0.95) 0%, rgba(255,243,180,0.95) 100%);
    }

    .card-header img {
      margin: 10px;
      width: 70px;
      height: 70px;
      display: block;
      object-fit: cover;
    }

    .title  {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .title h1 {
      font-size: 16px;
      color: var(--muted);
      margin-bottom: 6px;
      letter-spacing: 0.2px;
    }

    .title p {
      font-size: 13px;
      color: rgba(0,0,0,0.6);
    }

    .card-body {
      padding: 20px 22px;
      background: linear-gradient(90deg, rgba(255,243,180,0.95) 0%, rgba(255,238,83,0.95) 100%);
    }

    .greeting {
      font-size: 16px;
      margin-bottom: 14px;
      color: #222;
    }

    .details {
      background: rgba(255,255,255,0.35);
      border-radius: 10px;
      padding: 12px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .detail-item {
      display: flex;
      gap: 10px;
      align-items: flex-start;
    }

    .icon-wrap {
      width: 36px;
      height: 36px;
      min-width: 36px;
      border-radius: 8px;
      background: rgba(50,8,165,0.08);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .detail-text {
      font-size: 13px;
      color: #222;
      display: flex;
      align-items: center;
    }

    .detail-text .label {
      display: block;
      font-size: 13px;
      color: rgba(0,0,0,0.55);
    }

    .detail-text .label p {
      margin: 0px 5px;
      font-weight: 700;
      color: var(--purple);
      margin-top: 4px;
      display: flex;
      align-items: center;
    }

    .note {
      margin-top: 14px;
      font-size: 13px;
      color: #333;
      line-height: 1.45;
    }

    .card-footer {
      background:  var(--purple);
      color: #f0e9ff;
      padding: 18px 22px;
      text-align: center;
    }

    .card-footer p {
      margin: 0;
      font-size: 14px;
    }
    .muted-small {
      font-size: 12px;
      color: rgba(240,233,255,0.85);
      margin-top: 6px;
    }

    @media (max-width: 480px) {
      .details {
        grid-template-columns: 1fr;
      }
      .logo {
        width: 64px;
        height: 64px;
        flex: 0 0 64px;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <article class="card" role="article" aria-label="Login notification">
      <header class="card-header">
        {{-- <img src="{{ asset('images/header/Team-Gym.png') }}" alt="Logo"> --}}
        <img src="{{$message->embed(public_path("images/header/Team-Gym.png"))}}" alt="Logo">
        <div class="title">
          <h1>Notifications to you</h1>
        </div>
      </header>
      <div class="card-body">
        <div class="greeting">
          Hello <strong>{{ $userName ?? '' }}</strong>,
        </div>
        <div class="details" role="list">
          <div class="detail-item" role="listitem">
            <div class="detail-text">
              <span class="label">{{$description}}</span>
            </div>
          </div>
        </div>
        <p class="note">Good day.</p>
      </div>
      <footer class="card-footer">
        <p>هذا إشعار آلي — If you didn't sign in, please secure your account.</p>
        <div class="muted-small">Company Website — {{ url('/') }}</div>
      </footer>
    </article>
  </div>
</body>
</html>
