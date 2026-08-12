<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login Notification Card</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --colorGold: rgb(209, 187, 59);
      --colorAverage: rgb(236, 234, 74);
      --colorPearntSection: rgb(22, 22, 22);
      --colorPearnt: rgb(15, 15, 15);
      --colorOfOpacityLight: rgba(165, 164, 164, 0.295);
      --colorParagraph: rgb(126, 126, 126);
      --colorReverse: rgb(224, 224, 224);
      --colorReverseBg: rgb(46, 46, 46);
      --colorSVGAnalytcis: #82eb58;
      --colorCheck: #82eb58;
      --colorError: #ff4a4a;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family:  "Lato", Arial, sans-serif;
      background: var(--colorPearnt);
      min-height: 100vh;
      padding: 20px;
    }

    .main-header-lineage {
      display: flex;
      align-items: center;
    }

    .main-header-lineage .main-lineage {
      width: 100%;
      height: auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      grid-auto-rows: minmax(120px, auto);
      gap: 20px;
      align-items: stretch;
      box-sizing: border-box;
      grid-auto-flow: dense;
      padding: 0;
    }

    .main-header-lineage .lineage {
      padding: 10px;
      margin: 10px;
      width: 95%;
      height: 150px;
      background-color: var(--colorOfOpacityLight);
      border-radius: 10px;
      -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
      -ms-border-radius: 10px;
      -o-border-radius: 10px;
    }

    .main-header-lineage .lineage .header {
      padding: 10px;
      width: 93%;
      height: 50px;
      background-color: var(--colorPearntSection);
      border-radius: 7px;
      -webkit-border-radius: 7px;
      -moz-border-radius: 7px;
      -ms-border-radius: 7px;
      -o-border-radius: 7px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .main-header-lineage .lineage .header h1 {
      color: var(--colorReverse);
      font-size: 20px;
    }

    .main-header-lineage .lineage main {
      height: fit-content;
      margin: 5px;
      padding: 5px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .main-header-lineage .lineage main .content {
      display: flex;
      justify-content: space-between;
      align-items: start;
      flex-direction: column;
    }

    .main-header-lineage .lineage main .content h1 {
      font-size: 25px;
      margin: 10px 0px;
      color: var(--colorReverse);
    }

    .main-header-lineage .lineage main .content p {
      font-size: 11px;
      color: var(--colorParagraph);
    }

    .main-header-lineage .lineage main .content p span {
      color: var(--colorSVGAnalytcis);
    }

    .main-header-lineage .lineage main .stock-index {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .main-header-lineage .lineage main .stock-index p {
      font-size: 11px;
      color: var(--colorSVGAnalytcis);
      text-wrap: nowrap;
    }

    .main-header-lineage .descending .stock-index svg {
      transform: rotate(5deg) rotateX(-180deg);
      -webkit-transform: rotate(5deg) rotateX(-180deg);
      -moz-transform: rotate(5deg) rotateX(-180deg);
      -ms-transform: rotate(5deg) rotateX(-180deg);
      -o-transform: rotate(5deg) rotateX(-180deg);
      fill: var(--colorSVGAnalytcis);
    }

    .main-header-lineage .descending .stock-index svg path {
      fill: var(--colorSVGAnalytcis);
    }


    .lineage.descending {
      --colorSVGAnalytcis: #ff4c4c;
    }

    .lineage .stock-index svg {
      color: var(--colorSVGAnalytcis);
    }

    main:has(.table) {
      margin-bottom: 40px !important;
    }

    .table {
      clear: both !important;
      height: auto;
      box-sizing: border-box !important;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-direction: column;
      background-color: var(--colorOfOpacityLight);
      border-radius: 10px;
      -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
      -ms-border-radius: 10px;
      -o-border-radius: 10px;
      margin-bottom: 12px !important;
    }

    .table,
    .table .body {
      display: block !important;
      height: auto !important;
      overflow: visible !important;
    }

    .table .header {
      padding: 5px 10px;
      width: 100%;
      height: 28px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: var(--colorAverage);
      border-radius: 10px 10px 0px 0px;
    }

    .table .header h4 {
      position: relative;
      font-size: 14px;
      text-transform: capitalize;
      text-align: center;
      width: 100%;
    }

    .table .header h4:not(:last-child)::after {
      content: "";
      position: absolute;
      right: 0px;
      width: 1px;
      height: 100%;
      background-color: var(--colorPearnt);
    }

    .table .body:not(:has(.choose)) {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: start;
      align-items: center;
      flex-direction: column;
      overflow-y: auto;
    }

    .table .body:has(.choose) {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow-y: auto;
    }

    .table .body .choose p {
      color: var(--colorReverse);
    }

    .table .body .row {
      padding: 10px 0px;
      width: 100%;
      height: 30px;
      border-bottom: 1px solid var(--colorReverse);
      display: flex !important;
      justify-content: space-between;
      align-items: center;
      page-break-inside: avoid;
    }

    .table .body .row .content {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .table .body .row .details {
      margin: 25px 0px;
    }

    .table .body .row .details ul {
      margin: 10px;
    }

    .table .body .row .details ul li {
      margin: 10px;
      color: var(--colorReverse);
      text-transform: capitalize;
    }

    .table .body .row .details ul li button {
      padding: 7px 13px;
      background-color: var(--colorAverage);
      color: black;
      border-radius: 10px;
      -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
      -ms-border-radius: 10px;
      -o-border-radius: 10px;
      text-transform: capitalize;
      border: none;
      outline: none;
      cursor: pointer;
    }

    .table .body .row p {
      font-size: 14px;
      margin: 0px 10px;
      width: 100%;
      text-align: center;
      text-transform: capitalize;
      display: inline-block !important;
      color: var(--colorReverse);
      vertical-align: middle !important;
    }

    .table .body .row .content-row {
      margin: 0px 10px;
      width: 100%;
      display: flex;
      justify-content: space-around;
      align-items: center;
    }

    .table .body .row .content-row button {
      width: fit-content;
      background-color: transparent;
      border: none;
      outline: none;
      cursor: pointer;
    }

    .table .body .row .content-row button:hover {
      background-color: transparent;
    }

    .table .body .row p:has(img) {
      font-size: 14px;
      width: 100%;
      text-align: center;
      text-transform: capitalize;
      color: var(--colorReverse);
      display: flex;
      justify-content: start;
      align-items: center;
    }

    .table .body .row p img {
      margin: 0px 10px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      -moz-border-radius: 50%;
      -ms-border-radius: 50%;
      -o-border-radius: 50%;
      border: 1px solid transparent;
      outline: 1px solid var(--colorReverse);
      object-fit: cover;
    }

    .img-profile {
      margin: 0px 10px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      -moz-border-radius: 50%;
      -ms-border-radius: 50%;
      -o-border-radius: 50%;
      border: 1px solid transparent;
      outline: 1px solid var(--colorReverse);
    }

    .table .body .row .content {
      color: var(--colorParagraph);
    }

    .table .row p[data-state="exit"] {
      padding: 3px 10px;
      font-size: 14px;
      width: 90%;
      text-align: center;
      text-transform: capitalize;
      background-color: #82eb58;
      color: white;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
    }

    .table .row p[data-state="entrance"] {
      padding: 3px 10px;
      font-size: 14px;
      width: 90%;
      text-align: center;
      text-transform: capitalize;
      background-color: #eb5858;
      color: white;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
    }

    .table .row p[data-state="login"] {
      padding: 3px 8px;
      background-color: rgba(192, 98, 247, 0.904);
      color: #441f5a;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="PRO"] {
      padding: 3px 8px;
      background-color: rgba(255, 154, 107, 0.904);
      color: #5a1f1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="paid"] {
      padding: 3px 8px;
      background-color: rgba(174, 255, 107, 0.904);
      color: #2b5a1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="easy"] {
      padding: 3px 8px;
      background-color: rgba(174, 255, 107, 0.904);
      color: #2b5a1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="middle"] {
      padding: 3px 8px;
      background-color: rgba(107, 134, 255, 0.904);
      color: #281f5a;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="Expenses"] {
      padding: 3px 8px;
      background-color: rgba(255, 83, 60, 0.904);
      color: #5a1f1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="Revenues"] {
      padding: 3px 8px;
      background-color: rgba(127, 255, 76, 0.904);
      color: #245a1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="request"] {
      padding: 3px 8px;
      background-color: rgba(255, 76, 76, 0.904);
      color: #5a1f1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="sold"] {
      padding: 3px 8px;
      background-color: rgba(127, 255, 76, 0.904);
      color: #245a1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="acceptance"] {
      padding: 3px 8px;
      background-color: rgba(76, 255, 130, 0.904);
      color: #245a1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="acceptance"] {
      padding: 3px 8px;
      background-color: rgba(76, 255, 130, 0.904);
      color: #245a1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="reject"] {
      padding: 3px 8px;
      background-color: rgba(255, 76, 76, 0.904);
      color: #5a1f1f;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .table .row p[data-state="import"] {
      padding: 3px 8px;
      background-color: rgba(153, 153, 153, 0.904);
      color: #d4d4d4;
      border-radius: 50px;
      -webkit-border-radius: 50px;
      -moz-border-radius: 50px;
      -ms-border-radius: 50px;
      -o-border-radius: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    main h1 {
      color: white
    }
  </style>
</head>
<body>
  <div class="main-header-lineage" style="text-align:center; font-size:0;">
    <div style="display:inline-block; vertical-align:top; width:40%; margin:1%; font-size:14px;" @if ($expenses["state"] == 1) class="lineage" @else class="lineage descending" @endif >
      <div class="header">
        <h1>Expenses</h1>
      </div>
      <main>
        <div class="content">
          <h1>{{$expenses["total"]}} EGP</h1>
          <p>You achieved %{{$expenses["lineage"]}} of total income this year.</p>
        </div>
        <div class="stock-index">
          <p>{{$expenses["lineage"]}} %</p>
        </div>
      </main>
    </div>
    <div style="display:inline-block; vertical-align:top; width:40%; margin:1%; font-size:14px;" @if ($revenues["state"] == 1) class="lineage" @else class="lineage descending" @endif >
      <div class="header">
        <h1>Revenues</h1>
      </div>
      <main>
        <div class="content">
          <h1>{{$revenues["total"]}} EGP</h1>
          <p>You achieved %{{$revenues["lineage"]}} of total income this year.</p>
        </div>
        <div class="stock-index">
          <p>{{$revenues["lineage"]}} %</p>
        </div>
      </main>
    </div>
  </div>
  <div class="main-header-lineage" style="text-align:center; font-size:0;">
    <div style="display:inline-block; vertical-align:top; width:40%; margin:1%; font-size:14px;" @if ($expenses["state"] == 1) class="lineage" @else class="lineage descending" @endif >
      <div class="header">
        <h1>Supplements</h1>
      </div>
      <main>
        <div class="content">
          <h1>{{$supplement}} EGP</h1>
          <p>You achieved %{{round(($supplement / $total) * 100, 2)}} of total income this year.</p>
        </div>
        <div class="stock-index">
          <p>{{round(($supplement / $total) * 100, 2)}} %</p>
        </div>
      </main>
    </div>
    <div style="display:inline-block; vertical-align:top; width:40%; margin:1%; font-size:14px;" @if ($revenues["state"] == 1) class="lineage" @else class="lineage descending" @endif >
      <div class="header">
        <h1>Subscriptions</h1>
      </div>
      <main>
        <div class="content">
          <h1>{{$system}} EGP</h1>
          <p>You achieved %{{round(($system / $total) * 100, 2)}} of total income this year.</p>
        </div>
        <div class="stock-index">
          <p>{{round(($system / $total) * 100, 2)}} %</p>
        </div>
      </main>
    </div>
  </div>
  <main>
    <h1>History</h1>
    <div class="table">
      <div class="header" style="width:97%; text-align:center; background-color:#ECEA4A; font-size:0;">
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Name</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Code</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Price</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Status</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Attachment</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000;">Date</h4>
      </div>
      <div class="body">
        @if ($histories)
          @foreach ($histories as $history)
            <div class="row" style="width:100%; text-align:center; font-size:0;">
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{ $history->code }}</p>
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;" data-state="{{ $history->state }}">{{ $history->state }}</p>
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{ $history->amount ?? '--' }}</p>
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{ $history->attachment ?? '--' }}</p>
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{ $history->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </main>
  <main>
    <h1>Income Statement</h1>
    <div class="table">
      <div class="header" style="width:97%; text-align:center; background-color:#ECEA4A; font-size:0;">
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Name</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Code</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Status</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Price</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000;">Date</h4>
      </div>
      <div class="body">
        @if ($incomeStatement)
          @foreach ($incomeStatement as $item)
            <div class="row" style="width:100%; text-align:center; font-size:0;">
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->name}}</p>
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->code}}</p>
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;" data-state="{{$item->type}}">{{$item->type}}</p>
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->amount}}</p>
              <p style="display:inline-block; width:20%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->created_at}}</p>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </main>
  <main>
    <h1>Imports</h1>
    <div class="table">
      <div class="header" style="width:97%; text-align:center; background-color:#ECEA4A; font-size:0;">
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Name</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Code</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Status</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Quantity</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Price</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000;">Date</h4>
      </div>
      <div class="body">
        @if ($imports)
          @foreach ($imports as $item)
            <div class="row" style="width:100%; text-align:center; font-size:0;">
              <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->name}}</p>
              <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->code}}</p>
              <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;" data-state="{{$item->state}}">{{$item->state}}</p>
              <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->quantity}}</p>
              <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->amount}}</p>
              <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->created_at}}</p>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </main>
  <main>
    <h1>Supplements</h1>
    <div class="table">
      <div class="header" style="width:97%; text-align:center; background-color:#ECEA4A; font-size:0;">
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Client Name</h4>
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Supplement Name</h4>
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Price</h4>
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Recorded By</h4>
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000;">Date</h4>
      </div>
      <div class="body">
        @if ($supplements)
          @foreach ($supplements as $item)
            @if ($item->client)
              @foreach ($item->client as $index => $client)
                <div class="row" style="width:100%; text-align:center; font-size:0;">
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{optional($item->client)->fname}} {{optional($item->client)->lname}}</p>
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{optional($item->supplement)->name}}</p>
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->amount}}</p>
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{optional($item->employee)->fname}} {{optional($item->employee)->lname}}</p>
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->created_at}}</p>
                </div>
              @endforeach
            @endif
          @endforeach
        @endif
      </div>
    </div>
  </main>
  <main>
    <h1>Systems</h1>
    <div class="table">
      <div class="header" style="width:97%; text-align:center; background-color:#ECEA4A; font-size:0;">
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Client Name</h4>
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">System Name</h4>
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Price</h4>
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Recorded By</h4>
        <h4 style="display:inline-block; width:19%; margin:0; padding:5px 0; font-size:14px; color:#000000;">Date</h4>
      </div>
      <div class="body">
        @if ($systems)
          @foreach ($systems as $item)
            @if ($item->client)
              @foreach ($item->client as $index => $client)
                <div class="row" style="width:100%; text-align:center; font-size:0;">
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{optional($item->client)->fname}} {{optional($item->client)->lname}}</p>
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{optional($item->system)->name}}</p>
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->amount}}</p>
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{optional($item->employee)->fname}} {{optional($item->employee)->lname}}</p>
                  <p style="display:inline-block; width:18%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->created_at}}</p>
                </div>
              @endforeach
            @endif
          @endforeach
        @endif
      </div>
    </div>
  </main>
  <main>
    <h1>Payment Registry</h1>
    <div class="table">
      <div class="header" style="width:97%; text-align:center; background-color:#ECEA4A; font-size:0;">
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Employee</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Order Name</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Type</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Amount</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000; border-right:1px solid #000000;">Pay Month</h4>
        <h4 style="display:inline-block; width:16%; margin:0; padding:5px 0; font-size:14px; color:#000000;">Date</h4>
      </div>
      <div class="body">
        @if ($paymentRegistry)
          @foreach ($paymentRegistry as $item)
          <div class="row" style="width:100%; text-align:center; font-size:0;">
            <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{optional($item->employee)->fname}} {{optional($item->employee)->lname}}</p>
            <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->order_name}}</p>
            <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->type}}</p>
            <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->amount}}</p>
            <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->paymonth}}</p>
            <p style="display:inline-block; width:15%; margin:0; padding:5px 0; font-size:14px; color:#fff;">{{$item->created_at}}</p>
          </div>
          @endforeach
        @endif
      </div>
    </div>
  </main>
</body>
</html>
