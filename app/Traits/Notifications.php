<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Notification;
use App\Models\Front\Client;
use App\Models\Back\Activity;
use App\Models\Back\Employee;
use App\Models\Back\Payment;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\Imports;
use App\Models\Front\LineageInBody;
use App\Traits\IncomeStatements;
use Carbon\Carbon;

trait Notifications {
  use IncomeStatements;
  private function getIconSvg(string $iconName = null): ?string {
    $icons = [
      'iconPayment' => '
        <svg width="56" height="60" viewBox="0 0 48 32" xmlns="http://www.w3.org/2000/svg" fill="none" preserveAspectRatio="xMidYMid meet">
          <rect x="2" y="2" width="44" height="28" rx="4" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="4" y="6" width="40" height="4" fill="var(--colorSVG1)"/>
          <rect x="6" y="14" width="6" height="6" rx="1" fill="var(--colorSVG1)"/>
          <rect x="16" y="16" width="4" height="2" fill="var(--colorSVG1)" />
          <rect x="22" y="16" width="4" height="2" fill="var(--colorSVG1)" />
          <rect x="28" y="16" width="4" height="2" fill="var(--colorSVG1)" />
          <rect x="34" y="16" width="4" height="2" fill="var(--colorSVG1)" />
        </svg>
      ',
      'iconSupplement' => '
        <svg width="60" height="40" viewBox="0 0 64 64" fill="none">
          <rect x="18" y="8" width="28" height="5" rx="4" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="8" y="18" width="48" height="40" rx="8" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <text x="32" y="43" text-anchor="middle" fill="var(--colorSVG1)" font-family="Arial, sans-serif" font-size="14" font-weight="bold">WHAY</text>
        </svg>
      ',
      'iconExercise' => '
        <svg width="100" height="60" viewBox="0 0 64 32" xmlns="http://www.w3.org/2000/svg" fill="none">
          <rect x="0"  y="8"  width="6" height="16" rx="1" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
          <rect x="8"  y="4"  width="6" height="24" rx="1" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
          <rect x="16" y="2"  width="6" height="28" rx="1" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
          <rect x="24" y="14" width="16" height="4" rx="2" fill="var(--colorSVG1)"/>
          <rect x="40" y="2"  width="6" height="28" rx="1" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
          <rect x="48" y="4"  width="6" height="24" rx="1" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
          <rect x="56" y="8"  width="6" height="16" rx="1" fill="var(--colorSVG1)" stroke="var(--colorSVG2)" stroke-width="1"/>
        </svg>
      ',
      'iconInBody' => '
        <svg width="40" height="40" viewBox="0 0 64 64" fill="none">
          <rect x="11" y="8" width="38" height="48" rx="4" ry="4" fill="var(--colorSVG2)" stroke="var(--colorSVG1)" stroke-width="2"/>
          <rect x="17" y="16" width="20" height="4" rx="2" ry="2" fill="var(--colorSVG1)"/>
          <rect x="17" y="26" width="20" height="4" rx="2" ry="2" fill="var(--colorSVG1)"/>
          <rect x="17" y="36" width="20" height="4" rx="2" ry="2" fill="var(--colorSVG1)"/>
          <rect x="17" y="46" width="20" height="4" rx="2" ry="2" fill="var(--colorSVG1)"/>
          <circle cx="43" cy="18" r="2" fill="var(--colorSVG1)"/>
          <circle cx="43" cy="28" r="2" fill="var(--colorSVG1)"/>
          <circle cx="43" cy="38" r="2" fill="var(--colorSVG1)"/>
          <circle cx="43" cy="48" r="2" fill="var(--colorSVG1)"/>
        </svg>
      ',
      'iconLogin' => '
        <svg width="36" height="40" viewBox="0 5 48 30" fill="none" preserveAspectRatio="xMidYMid meet">
          <path d="M8 4 H26 M8 4 V34 H26" stroke="var(--colorSVG1)" stroke-width="2" fill="none" stroke-linejoin="round"/>
          <path d="M42 20 H22 M30 14 L22 20 L30 26" stroke="var(--colorSVG2)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
      ',
      'iconIncome' => '
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 48" width="64" height="48" fill="none" stroke="var(--colorSVG1)" stroke-width="2" stroke-linejoin="round" stroke-linecap="round">
          <rect x="4" y="6" width="56" height="36" rx="8" fill="none"/>
          <rect x="10" y="12" width="45" height="6" rx="3" fill="var(--colorSVG1)" stroke="none"/>
          <polygon points="12,24 53,24 49,32" fill="var(--colorSVG1)" stroke="var(--colorSVG1)"/>
        </svg>
      ',
    ];
    return $iconName ? ($icons[$iconName] ?? null) : null;
  }
  public function notificationSystem($state) {
    $authClient = Auth::guard('client')->user();
    $clientCode = $authClient ? $authClient->code : Cookie::get('login_client');
    $client = $clientCode ? Client::where('code', $clientCode)->with([
      'payment' => function ($q) {
        $q->whereColumn('amount', '>', 'paid');
      },
      'activities',
      'settings'
    ])->first() : null;
    $employeeCode = Auth::guard('employee')->user();
    if ($employeeCode) {
      $employee = Employee::where("code", $employeeCode->code)->with("setting")->first();
    };
    Notification::where('created_at', '<', Carbon::now()->subDay())->delete();
    $today = Carbon::now()->format('l');
    $month = Carbon::now('Africa/Cairo')->format('F');
    $rand = rand(100000, time());
    if ($client) {
      $paymentList = $client->payment->values();
      $supplementCodes = $paymentList->pluck('code_supplements')->filter()->unique();
      $systemCodes = $paymentList->pluck('code_systems')->filter()->unique();
      $supplementsMap = Supplement::whereIn('code', $supplementCodes)->get()->keyBy('code');
      $systemsMap = System::whereIn('code', $systemCodes)->get()->keyBy('code');

      foreach ($paymentList as $p) {
        if ($p->payday == $today) {
          $getSupplement = $supplementsMap->get($p->code_supplements);
          $getSystem = $systemsMap->get($p->code_systems);
          $notification = new Notification();
          $notification->code = rand(100000, time());
          $notification->code_client = $client->code;
          if ($getSupplement) {
            $notification->code_supplements = $getSupplement->code;
            $notification->icon = $this->getIconSvg("iconSupplement");
            $notification->name = __('messages.supplement_bill_title');
            $notification->type = "supplement";
            $notification->description = __('messages.supplement_bill_ready', [
              'amount' => $p->amount,
              'residual' => ($p->amount - $p->paid)
            ]);
          } else {
            if ($getSystem) {
              $notification->code_systems = $getSystem->code;
            };
            $notification->icon = $this->getIconSvg("iconPayment");
            $notification->name = __('messages.bill_title');
            $notification->type = "system";
            $notification->description = __('messages.bill_ready', [
              'amount' => $p->amount,
              'residual' => ($p->amount - $p->paid)
            ]);
          };
          $notification->amount = $p->amount;
          $notification->residual = $p->amount - $p->paid;
          $existsQuery = Notification::where('code_client', $notification->code_client)
            ->where('type', $notification->type)
            ->where('name', $notification->name)
            ->where('description', $notification->description)
            ->where('amount', $notification->amount)
            ->where('residual', $notification->residual);
          if (!empty($notification->code_supplements)) {
            $existsQuery->where('code_supplements', $notification->code_supplements);
          };
          if (!empty($notification->code_systems)) {
            $existsQuery->where('code_systems', $notification->code_systems);
          };
          if (!$existsQuery->exists()) {
            $notification->save();
          };
        };
      };
      foreach ($client->activities as $a) {
        if (trim(strtolower($a->day)) === trim(strtolower($today))) {
          $notification = new Notification();
          $notification->code = rand(100000, time());
          $notification->code_client = $client->code;
          $notification->name = __('messages.you_have_workout_title');
          $notification->type = "exercise";
          $notification->description = __('messages.you_have_workout');
          $notification->icon = $this->getIconSvg("iconExercise");
          $existsQuery = Notification::where('code_client', $notification->code_client)
            ->where('type', $notification->type)
            ->where('name', $notification->name)
            ->where('description', $notification->description);
          if (!$existsQuery->exists()) {
            $notification->save();
          };
        };
      };
      $hasCurrentMonthInBody = LineageInBody::where('code', $client->code)
        ->whereNotNull($month)
        ->exists();
      if (!$hasCurrentMonthInBody) {
        $notification = new Notification();
        $notification->code = rand(100000, time());
        $notification->code_client = $client->code;
        $notification->name = __('messages.inbody_title');
        $notification->type = "InBody";
        $notification->description = __('messages.inbody_followup');
        $notification->icon = $this->getIconSvg("iconInBody");
        $existsQuery = Notification::where('code_client', $notification->code_client)
          ->where('type', $notification->type)
          ->where('name', $notification->name)
          ->where('description', $notification->description);
        if (!$existsQuery->exists()) {
          $notification->save();
        };
      };
    };
    if ($employeeCode) {
      $imports = Imports::with("employee")->get();
      foreach ($imports as $item) {
        if ($item->quantity < 2) {
          $notification = new Notification();
          $notification->code = rand(100000, time());
          $notification->code_employee = $employee->code;
          $notification->name = __('messages.product_alert_title');
          $notification->type = "product";
          $notification->description = __('messages.product_alert', ['name' => $item->name]);
          $notification->icon = $this->getIconSvg("iconSupplement");;
          $existsQuery = Notification::where('code_employee', $notification->code_employee)
            ->where('type', $notification->type)
            ->where('name', $notification->name)
            ->where('description', $notification->description);
          if (!$existsQuery->exists()) {
            $notification->save();
          };
        };
      };
      $revenues = $this->stateIncomeStatement("revenues");
      foreach ($imports as $item) {
        if ($revenues["state"] == 1) {
          $notification = new Notification();
          $notification->code = rand(100000, time());
          $notification->code_employee = $employee->code;
          $notification->name = __('messages.income_alert_title');
          $notification->type = "income";
          $notification->description = __('messages.income_alert_increase', [
            'total' => $revenues['total']
          ]);
          $notification->icon = $this->getIconSvg("iconIncome");
          $existsQuery = Notification::where('code_employee', $notification->code_employee)
            ->where('type', $notification->type)
            ->where('name', $notification->name)
            ->where('description', $notification->description);
          if (!$existsQuery->exists()) {
            $notification->save();
          };
        };
      };
      $expenses = $this->stateIncomeStatement("expenses");
      foreach ($imports as $item) {
        $notification = new Notification();
        $notification->code = rand(100000, time());
        $notification->code_employee = $employee->code;
        $notification->name = __('messages.income_alert_title');
        $notification->type = "income";
        $total = $expenses['total'] - $revenues['total'];
        if ($expenses["state"] == 0 && $total >= 0) {
          $notification->description = __('messages.expenses_down', ['total' => ($expenses['total'] - $revenues['total'])]);
        } else {
          $notification->description = __('messages.expenses_up', ['total' => ($expenses['total'] - $revenues['total'])]);
        };
        $notification->icon = $this->getIconSvg("iconIncome");
        $existsQuery = Notification::where('code_employee', $notification->code_employee)
          ->where('type', $notification->type)
          ->where('name', $notification->name)
          ->where('description', $notification->description);
        if (!$existsQuery->exists()) {
          $notification->save();
        };
      };
    };
    if ($state == "client") {
      if (!$client || !$client->settings) {
        return collect();
      }
      if ($client->settings->payment_date == true) {
        $notifications = Notification::where('code_client', $client->code)->get();
        if ($notifications == null) return null;
      } else if ($client->settings->payment_date == false) {
        $notifications = Notification::where('code_client', $client->code)->whereNot("type", "supplement")->whereNot("type", "system")->get();
        if ($notifications == null) return null;
      };
    } else {
      $notifications = collect();
      if ($employee && $employee->setting && $employee->setting->class_reminders == true) {
        $notifications = Notification::where('code_employee', $employeeCode->code)->get();
      };
    };
    return collect($notifications);
  }
  public function makeNotification($name, $type, $description, $codeClient = null, $codeEmployee = null, $icon) {
    $client = $codeClient ? Client::where("code", $codeClient)->with(["payment", "activities", "settings"])->first() : null;
    $employee = $codeEmployee ? Employee::where("code", $codeEmployee)->with("setting")->first() : null;
    Notification::where('created_at', '<', Carbon::now()->subDay())->delete();
    $notification = new Notification();
    $notification->code = rand(100000, time());
    $notification->code_client = $codeClient;
    $notification->code_employee = $codeEmployee;
    $notification->name = $name;
    $notification->type = $type;
    $notification->description = $description;
    $notification->icon = $this->getIconSvg($icon);
    if ($codeClient || $codeEmployee) {
        $existsQuery = Notification::where(function($q) use ($codeEmployee, $codeClient) {
          if ($codeEmployee) {
            $q->where('code_employee', $codeEmployee);
          };
          if ($codeClient) {
            if ($codeEmployee) {
              $q->orWhere('code_client', $codeClient);
            } else {
              $q->where('code_client', $codeClient);
            };
          };
        })->where('type', $type)->where('name', $name)->where('description', $description);
    } else {
      $existsQuery = Notification::where('type', $type)->where('name', $name)->where('description', $description);
    };
    if (!$existsQuery->exists()) {
      $notification->save();
    };
  }
}
