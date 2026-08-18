<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Team Gym — InBody Yearly Report {{ $year }}</title>
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
    .wrapper { max-width: 960px; margin: 0 auto; }
    .report-header {
      text-align: center;
      padding: 32px 0 40px;
    }
    .report-header img {
      width: 52px; height: 52px;
      border-radius: 14px;
    }
    .report-header h1 {
      margin: 16px 0 4px;
      font-size: 22px; font-weight: 700;
      color: #ffe65b;
      letter-spacing: 0.5px;
    }
    .report-header p {
      font-size: 13px;
      color: rgba(255,255,255,0.3);
      text-transform: uppercase;
      letter-spacing: 1.5px;
    }
    .client-name {
      font-size: 16px; font-weight: 600;
      color: #fff;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .data-table-wrap {
      background: linear-gradient(145deg, #141414, #0d0d0d);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 16px;
      overflow: hidden;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }
    .data-table thead th {
      padding: 14px 12px;
      font-size: 10px; font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: rgba(255,255,255,0.3);
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      background: rgba(255,255,255,0.02);
      white-space: nowrap;
    }
    .data-table thead th:first-child {
      text-align: left;
      padding-left: 20px;
      min-width: 120px;
    }
    .data-table tbody td {
      padding: 10px 12px;
      text-align: center;
      color: rgba(255,255,255,0.6);
      border-bottom: 1px solid rgba(255,255,255,0.03);
      white-space: nowrap;
    }
    .data-table tbody td:first-child {
      text-align: left;
      padding-left: 20px;
      font-weight: 600;
      color: rgba(255,255,255,0.85);
    }
    .data-table tbody tr:last-child td {
      border-bottom: none;
    }
    .data-table tbody tr:hover {
      background: rgba(255,230,91,0.02);
    }
    .note {
      text-align: center;
      font-size: 12px;
      color: rgba(255,255,255,0.2);
      padding: 24px 0;
      line-height: 1.6;
    }
    .report-footer {
      text-align: center;
      padding: 24px 0;
      border-top: 1px solid rgba(255,255,255,0.04);
    }
    .report-footer p {
      font-size: 11px;
      color: rgba(255,255,255,0.2);
    }
    .report-footer a { color: rgba(255,230,91,0.6); text-decoration: none; }
    @media (max-width: 768px) {
      .data-table-wrap { overflow-x: auto; }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="report-header">
      <img src="{{ asset('images/header/Team-Gym.png') }}" alt="Team Gym">
      <h1>InBody Yearly Report</h1>
      <p>{{ $year }}</p>
    </div>
    @php
      $labels = [
        'weight' => __('messages.weight'),
        'BMI' => __('messages.bmi'),
        'PBF' => __('messages.pbf_percent'),
        'SMM' => __('messages.smm_kg'),
        'KCAL' => __('messages.kcal'),
        'water' => __('messages.total_body_water'),
        'fat_mass' => __('messages.body_fat_mass'),
        'protein' => __('messages.protein_kg'),
        'left_arm_lean' => __('messages.left_arm_lean'),
        'right_arm_lean' => __('messages.right_arm_lean'),
        'left_leg_lean' => __('messages.left_leg_lean'),
        'right_leg_lean' => __('messages.right_leg_lean'),
        'left_arm_fat' => __('messages.left_arm_fat'),
        'right_arm_fat' => __('messages.right_arm_fat'),
        'left_leg_fat' => __('messages.left_leg_fat'),
        'right_leg_fat' => __('messages.right_leg_fat'),
      ];
      $monthLabels = [
        'january' => __('messages.card-profile-Jan'),
        'february' => __('messages.card-profile-Feb'),
        'march' => __('messages.card-profile-Mar'),
        'april' => __('messages.card-profile-Apr'),
        'may' => __('messages.card-profile-May'),
        'june' => __('messages.card-profile-Jun'),
        'july' => __('messages.card-profile-Jul'),
        'august' => __('messages.card-profile-Aug'),
        'september' => __('messages.card-profile-Sep'),
        'october' => __('messages.card-profile-Oct'),
        'november' => __('messages.card-profile-Nov'),
        'december' => __('messages.card-profile-Dec'),
      ];
    @endphp
    <div class="client-name">{{ $client->fname }} {{ $client->lname }}</div>
    <div class="data-table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>{{ __('messages.name') }}</th>
            @foreach ($monthLabels as $label)
              <th>{{ $label }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach ($archive as $metric => $months)
            <tr>
              <td>{{ $labels[$metric] ?? $metric }}</td>
              @foreach ($months as $value)
                <td>{{ $value ?? '—' }}</td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <p class="note">{{ __('messages.yearly-inbody-archive-note') }}</p>
    <div class="report-footer">
      <p>Automated report from <a href="{{ url('/') }}">Team Gym</a></p>
    </div>
  </div>
</body>
</html>