<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Team Gym — Company Report</title>
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
    .wrapper { max-width: 900px; margin: 0 auto; }
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
    .stat-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 32px;
    }
    .stat-card {
      background: linear-gradient(145deg, #141414, #0d0d0d);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 16px;
      padding: 24px;
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 2px;
    }
    .stat-card.positive::before { background: linear-gradient(90deg, rgba(82,235,88,0.5), transparent); }
    .stat-card.negative::before { background: linear-gradient(90deg, rgba(255,76,76,0.5), transparent); }
    .stat-card .stat-label {
      font-size: 11px; font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255,255,255,0.3);
      margin-bottom: 8px;
    }
    .stat-card .stat-value {
      font-size: 28px; font-weight: 700;
      color: #fff;
      margin-bottom: 8px;
    }
    .stat-card .stat-meta {
      font-size: 12px;
      color: rgba(255,255,255,0.35);
    }
    .stat-card .stat-pct {
      display: inline-block;
      font-size: 13px; font-weight: 600;
      padding: 2px 10px;
      border-radius: 20px;
      margin-left: 6px;
    }
    .stat-card.positive .stat-pct { background: rgba(82,235,88,0.1); color: #52eb58; }
    .stat-card.negative .stat-pct { background: rgba(255,76,76,0.1); color: #ff4c4c; }
    .section-title {
      font-size: 14px; font-weight: 600;
      color: rgba(255,255,255,0.5);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin: 32px 0 16px;
      padding-bottom: 12px;
      border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 32px;
    }
    .data-table thead th {
      padding: 12px 16px;
      font-size: 10px; font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255,255,255,0.3);
      text-align: left;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      background: rgba(255,255,255,0.02);
    }
    .data-table thead th:first-child { border-radius: 10px 0 0 0; }
    .data-table thead th:last-child { border-radius: 0 10px 0 0; }
    .data-table tbody td {
      padding: 12px 16px;
      font-size: 13px;
      color: rgba(255,255,255,0.7);
      border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    .data-table tbody tr:hover { background: rgba(255,255,255,0.015); }
    .badge {
      display: inline-block;
      padding: 3px 10px;
      font-size: 11px; font-weight: 600;
      border-radius: 20px;
      text-transform: capitalize;
    }
    .badge-green { background: rgba(82,235,88,0.1); color: #52eb58; }
    .badge-red { background: rgba(255,76,76,0.1); color: #ff4c4c; }
    .badge-blue { background: rgba(107,134,255,0.1); color: #6b86ff; }
    .badge-purple { background: rgba(192,98,247,0.1); color: #c062f7; }
    .badge-orange { background: rgba(255,154,107,0.1); color: #ff9a6b; }
    .badge-gray { background: rgba(153,153,153,0.1); color: #999; }
    .report-footer {
      text-align: center;
      padding: 24px 0;
      margin-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.04);
    }
    .report-footer p {
      font-size: 11px;
      color: rgba(255,255,255,0.2);
    }
    .report-footer a { color: rgba(255,230,91,0.6); text-decoration: none; }
    @media (max-width: 600px) {
      .stat-grid { grid-template-columns: 1fr; }
      .data-table { font-size: 11px; }
      .data-table thead th, .data-table tbody td { padding: 8px 10px; }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="report-header">
      <img src="{{ asset('images/header/Team-Gym.png') }}" alt="Team Gym">
      <h1>Company Report</h1>
      <p>Annual Financial Overview</p>
    </div>

    <div class="stat-grid">
      <div class="stat-card @if ($expenses['state'] == 1) positive @else negative @endif">
        <div class="stat-label">Expenses</div>
        <div class="stat-value">{{$expenses['total']}} <small style="font-size:14px; color:rgba(255,255,255,0.3);">EGP</small></div>
        <div class="stat-meta">
          {{ $expenses['lineage'] }}%<span class="stat-pct">of total income</span>
        </div>
      </div>
      <div class="stat-card @if ($revenues['state'] == 1) positive @else negative @endif">
        <div class="stat-label">Revenues</div>
        <div class="stat-value">{{$revenues['total']}} <small style="font-size:14px; color:rgba(255,255,255,0.3);">EGP</small></div>
        <div class="stat-meta">
          {{ $revenues['lineage'] }}%<span class="stat-pct">of total income</span>
        </div>
      </div>
      <div class="stat-card positive">
        <div class="stat-label">Supplements</div>
        <div class="stat-value">{{$supplement}} <small style="font-size:14px; color:rgba(255,255,255,0.3);">EGP</small></div>
        <div class="stat-meta">
          {{round(($supplement / $total) * 100, 2)}}%<span class="stat-pct">of total income</span>
        </div>
      </div>
      <div class="stat-card positive">
        <div class="stat-label">Subscriptions</div>
        <div class="stat-value">{{$system}} <small style="font-size:14px; color:rgba(255,255,255,0.3);">EGP</small></div>
        <div class="stat-meta">
          {{round(($system / $total) * 100, 2)}}%<span class="stat-pct">of total income</span>
        </div>
      </div>
    </div>

    <h2 class="section-title">Transaction History</h2>
    <table class="data-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Code</th>
          <th>Price</th>
          <th>Status</th>
          <th>Attachment</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @if ($histories)
          @foreach ($histories as $history)
            <tr>
              <td>{{ $history->code }}</td>
              <td>{{ $history->code }}</td>
              <td>{{ $history->amount ?? '--' }}</td>
              <td><span class="badge
                @if (in_array($history->state, ['exit', 'paid', 'easy', 'acceptance', 'Revenues', 'sold'])) badge-green
                @elseif (in_array($history->state, ['entrance', 'request', 'Expenses', 'reject'])) badge-red
                @elseif ($history->state == 'login') badge-purple
                @elseif ($history->state == 'PRO') badge-orange
                @elseif ($history->state == 'middle') badge-blue
                @else badge-gray
                @endif
              ">{{ $history->state }}</span></td>
              <td>{{ $history->attachment ?? '--' }}</td>
              <td>{{ $history->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>

    <h2 class="section-title">Income Statement</h2>
    <table class="data-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Code</th>
          <th>Status</th>
          <th>Price</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @if ($incomeStatement)
          @foreach ($incomeStatement as $item)
            <tr>
              <td>{{$item->name}}</td>
              <td>{{$item->code}}</td>
              <td><span class="badge
                @if (in_array($item->type, ['Revenues', 'paid', 'exit'])) badge-green
                @elseif (in_array($item->type, ['Expenses', 'entrance'])) badge-red
                @else badge-gray
                @endif
              ">{{$item->type}}</span></td>
              <td>{{$item->amount}}</td>
              <td>{{$item->created_at}}</td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>

    <h2 class="section-title">Imports</h2>
    <table class="data-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Code</th>
          <th>Status</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @if ($imports)
          @foreach ($imports as $item)
            <tr>
              <td>{{$item->name}}</td>
              <td>{{$item->code}}</td>
              <td><span class="badge badge-gray">{{$item->state}}</span></td>
              <td>{{$item->quantity}}</td>
              <td>{{$item->amount}}</td>
              <td>{{$item->created_at}}</td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>

    <h2 class="section-title">Supplements</h2>
    <table class="data-table">
      <thead>
        <tr>
          <th>Client</th>
          <th>Supplement</th>
          <th>Price</th>
          <th>Recorded By</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @if ($supplements)
          @foreach ($supplements as $item)
            @if ($item->client)
              @foreach ($item->client as $index => $client)
                <tr>
                  <td>{{optional($item->client)->fname}} {{optional($item->client)->lname}}</td>
                  <td>{{optional($item->supplement)->name}}</td>
                  <td>{{$item->amount}}</td>
                  <td>{{optional($item->employee)->fname}} {{optional($item->employee)->lname}}</td>
                  <td>{{$item->created_at}}</td>
                </tr>
              @endforeach
            @endif
          @endforeach
        @endif
      </tbody>
    </table>

    <h2 class="section-title">Systems</h2>
    <table class="data-table">
      <thead>
        <tr>
          <th>Client</th>
          <th>System</th>
          <th>Price</th>
          <th>Recorded By</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @if ($systems)
          @foreach ($systems as $item)
            @if ($item->client)
              @foreach ($item->client as $index => $client)
                <tr>
                  <td>{{optional($item->client)->fname}} {{optional($item->client)->lname}}</td>
                  <td>{{optional($item->system)->name}}</td>
                  <td>{{$item->amount}}</td>
                  <td>{{optional($item->employee)->fname}} {{optional($item->employee)->lname}}</td>
                  <td>{{$item->created_at}}</td>
                </tr>
              @endforeach
            @endif
          @endforeach
        @endif
      </tbody>
    </table>

    <h2 class="section-title">Payment Registry</h2>
    <table class="data-table">
      <thead>
        <tr>
          <th>Employee</th>
          <th>Order</th>
          <th>Type</th>
          <th>Amount</th>
          <th>Pay Month</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @if ($paymentRegistry)
          @foreach ($paymentRegistry as $item)
            <tr>
              <td>{{optional($item->employee)->fname}} {{optional($item->employee)->lname}}</td>
              <td>{{$item->order_name}}</td>
              <td><span class="badge
                @if ($item->type == 'Revenues') badge-green
                @elseif ($item->type == 'Expenses') badge-red
                @else badge-gray
                @endif
              ">{{$item->type}}</span></td>
              <td>{{$item->amount}}</td>
              <td>{{$item->paymonth}}</td>
              <td>{{$item->created_at}}</td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>

    <div class="report-footer">
      <p>Automated report from <a href="{{ url('/') }}">Team Gym</a></p>
    </div>
  </div>
</body>
</html>