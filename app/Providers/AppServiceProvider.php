<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Models\Front\ImgInBody;use App\Models\Back\SettingCompany;
use App\Models\Back\Employee;
use App\Models\Back\Lineage;
use App\Models\Back\IncomeStatement;
use App\Models\Back\History;
use App\Models\Back\Imports;
use App\Models\Back\Payment;
use App\Models\Back\CustomerRequests;
use App\Models\Back\RequestsPayment;
use App\Models\Back\PaymentRegistry;
use App\Traits\Notifications;
use Illuminate\Support\Facades\Broadcast;
use App\Traits\IncomeStatements;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider {
  use Notifications, IncomeStatements;
  public function register(): void {
    $this->app->singleton(NotificationService::class);
  }
  public function boot(): void {
    Broadcast::routes();
    require base_path('routes/channels.php');
    Blade::componentNamespace('Nightshade\\Views\\Components', 'components');
    Blade::componentNamespace('Nightshade\\Views\\Components\\Web', 'web');
    Blade::componentNamespace('Nightshade\\Views\\Components\\Company', 'company');

    $this->defineRateLimiters();

    Gate::define('admin', function ($user) {
      $role = \App\Support\Roles::normalize($user->job_role ?? '');
      return $role === \App\Support\Roles::ADMIN;
    });
    Gate::define('coach', function ($user) {
      $role = \App\Support\Roles::normalize($user->job_role ?? '');
      return in_array($role, [\App\Support\Roles::ADMIN, \App\Support\Roles::COACH], true);
    });
    Gate::define('client', function ($user) {
      return $user instanceof Client;
    });

    View::composer('Website.Dashboard.homePage', function ($view) {
      // Identity comes from the authenticated session, never from a cookie.
      $client = Auth::guard('client')->user();
      if (!$client) {
        return;
      }
      $cookieCode = Cookie::get('login_client');
      if ($cookieCode !== null && $cookieCode !== $client->code) {
        Cookie::queue(Cookie::forget('login_client'));
      }
      $this->app->make(NotificationService::class)->cleanStaleNotifications($client);
      $notifications = $this->notificationSystem("client");
      $view->with([
        'client' => $client,
        'notifications' => $notifications,
      ]);
    });

    View::composer('Company.Dashboard.homePageCompany', function ($view) {
      $employeeCode = Auth::guard('employee')->user();
      if (!$employeeCode) {
        return;
      }
      $settingCompany = SettingCompany::find(1);
      $employee = Employee::where("code", $employeeCode->code)->with("setting")->first();
      if (!$employee) {
        return;
      }
      $this->app->make(NotificationService::class)->cleanEmployeeNotifications($employee);
      $notifications = $this->notificationSystem("employee");
      $view->with([
        'settingCompany' => $settingCompany,
        'employee' => $employee,
        'notifications' => $notifications,
      ]);
    });
  }

  private function defineRateLimiters(): void {
    RateLimiter::for('client-login', function ($request) {
      return Limit::perMinute(8)->by($request->ip() . '|' . mb_strtolower(trim((string) $request->input('email', ''))));
    });
    RateLimiter::for('company-login', function ($request) {
      return Limit::perMinute(8)->by($request->ip() . '|' . mb_strtolower(trim((string) $request->input('email', ''))));
    });
    RateLimiter::for('signup', function ($request) {
      return Limit::perHour(5)->by($request->ip());
    });
    RateLimiter::for('qr-login', function ($request) {
      return Limit::perMinute(10)->by($request->ip());
    });
    RateLimiter::for('password-reset', function ($request) {
      return Limit::perMinute(5)->by($request->ip() . '|' . mb_strtolower(trim((string) $request->input('email', ''))));
    });
    RateLimiter::for('staff-scan', function ($request) {
      return Limit::perMinute(30)->by($request->ip() . '|' . auth('employee')->id());
    });
    RateLimiter::for('request-create', function ($request) {
      return Limit::perMinute(12)->by($request->ip());
    });
  }
}