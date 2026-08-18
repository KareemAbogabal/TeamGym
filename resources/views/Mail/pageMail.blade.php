<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Team Gym Notification</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
      background: #0a0a0a;
      min-height: 100vh;
      padding: 40px 20px;
      color: #e0e0e0;
      -webkit-font-smoothing: antialiased;
    }
    .wrapper { width: 100%; max-width: 520px; margin: 0 auto; }
    .card {
      background: linear-gradient(145deg, #141414 0%, #0d0d0d 100%);
      border: 1px solid rgba(255, 230, 91, 0.08);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.03);
    }
    .card-header {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 24px 28px;
      border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .logo-mark {
      width: 44px; height: 44px;
      border-radius: 12px;
      object-fit: cover;
    }
    .brand { flex: 1; }
    .brand h1 {
      font-size: 15px; font-weight: 600;
      color: #ffe65b;
      letter-spacing: 0.3px;
    }
    .brand p {
      font-size: 11px; font-weight: 400;
      color: rgba(255,255,255,0.3);
      margin-top: 1px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .card-body { padding: 28px; }
    .greeting {
      font-size: 15px; font-weight: 400;
      color: rgba(255,255,255,0.65);
      margin-bottom: 24px;
      line-height: 1.5;
    }
    .greeting strong { color: #fff; font-weight: 600; }
    .details {
      background: rgba(255,255,255,0.02);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 14px;
      padding: 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    .detail-item { display: flex; flex-direction: column; gap: 4px; }
    .detail-label {
      font-size: 10px; font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      color: rgba(255,255,255,0.25);
    }
    .detail-value {
      font-size: 14px; font-weight: 500;
      color: rgba(255,255,255,0.85);
    }
    .verification-block {
      grid-column: 1 / -1;
      text-align: center;
      padding: 24px 16px;
      margin-top: 4px;
      background: linear-gradient(135deg, rgba(255,230,91,0.06), rgba(255,230,91,0.02));
      border: 1px solid rgba(255,230,91,0.12);
      border-radius: 12px;
    }
    .verification-block .detail-label { margin-bottom: 8px; }
    .verification-block .detail-value {
      font-size: 32px; font-weight: 700;
      letter-spacing: 10px;
      color: #ffe65b;
    }
    .divider {
      height: 1px;
      background: rgba(255,255,255,0.04);
      margin: 20px 0;
    }
    .note {
      font-size: 12px;
      color: rgba(255,255,255,0.3);
      line-height: 1.6;
    }
    .card-footer {
      padding: 18px 28px;
      border-top: 1px solid rgba(255,255,255,0.04);
      text-align: center;
    }
    .card-footer p {
      font-size: 11px;
      color: rgba(255,255,255,0.2);
      line-height: 1.5;
    }
    .card-footer a {
      color: rgba(255,230,91,0.6);
      text-decoration: none;
    }
    @media (max-width: 480px) {
      .details { grid-template-columns: 1fr; }
      .verification-block .detail-value { font-size: 26px; letter-spacing: 8px; }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="card">
      <div class="card-header">
        <img class="logo-mark" src="{{$message->embed(public_path("images/header/Team-Gym.png"))}}" alt="Team Gym">
        <div class="brand">
          <h1>TEAM GYM</h1>
          <p>Security Alert</p>
        </div>
      </div>
      <div class="card-body">
        <div class="greeting">
          Hello <strong>{{ $userName ?? '' }}</strong>,<br>
          We detected a new activity on your account.
        </div>
        <div class="details">
          <div class="detail-item">
            <span class="detail-label">Name</span>
            <span class="detail-value">{{ $name ?? '' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Code</span>
            <span class="detail-value">{{ $code ?? '' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Time</span>
            <span class="detail-value">{{ $time ?? '' }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Phone</span>
            <span class="detail-value">{{ $phone ?? '' }}</span>
          </div>
          @if (!empty($verificationCode))
            <div class="verification-block">
              <span class="detail-label">Your Verification Code</span>
              <span class="detail-value">{{$verificationCode}}</span>
            </div>
          @endif
        </div>
        <div class="divider"></div>
        <p class="note">If you did not request this, please secure your account immediately by changing your password.</p>
      </div>
      <div class="card-footer">
        <p>This is an automated notification from <a href="{{ url('/') }}">Team Gym</a></p>
      </div>
    </div>
  </div>
</body>
</html>