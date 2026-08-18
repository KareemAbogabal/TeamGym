<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Team Gym Report</title>
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
    .message-block {
      background: rgba(255,255,255,0.02);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 14px;
      padding: 20px;
    }
    .message-block .detail-label {
      font-size: 10px; font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      color: rgba(255,255,255,0.25);
      margin-bottom: 10px;
    }
    .message-block .detail-value {
      font-size: 14px; font-weight: 400;
      color: rgba(255,255,255,0.75);
      line-height: 1.6;
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
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="card">
      <div class="card-header">
        <img class="logo-mark" src="{{$message->embed(public_path("images/header/Team-Gym.png"))}}" alt="Team Gym">
        <div class="brand">
          <h1>TEAM GYM</h1>
          <p>Report Notification</p>
        </div>
      </div>
      <div class="card-body">
        <div class="greeting">
          Hello <strong>{{ $userName ?? '' }}</strong>,
        </div>
        <div class="message-block">
          <span class="detail-label">Details</span>
          <span class="detail-value">{{$description}}</span>
        </div>
        <div class="divider"></div>
        <p class="note">If you have any questions regarding this report, please contact your administrator.</p>
      </div>
      <div class="card-footer">
        <p>Automated report from <a href="{{ url('/') }}">Team Gym</a></p>
      </div>
    </div>
  </div>
</body>
</html>