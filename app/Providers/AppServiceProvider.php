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
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Models\Front\ImgInBody;
use App\Models\Back\SettingCompany;
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
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider {
  use Notifications, IncomeStatements;
  public function register(): void {
    //
  }
  public function boot(): void {
    Broadcast::routes();
    require base_path('routes/channels.php');
    Blade::componentNamespace('Nightshade\\Views\\Components', 'components');
    Blade::componentNamespace('Nightshade\\Views\\Components\\Web', 'web');
    Blade::componentNamespace('Nightshade\\Views\\Components\\Company', 'company');
    Gate::define('admin', function ($user) {
      $protected = array_map('strtolower', (array) config('roles.protected', []));
      $role = strtolower(trim((string) ($user->job_role ?? '')));
      return in_array($role, $protected);
    });
    Gate::define('coach', function ($user) {
      $protected = array_map('strtolower', (array) config('roles.coach', []));
      $role = strtolower(trim((string) ($user->job_role ?? '')));
      return in_array($role, $protected);
    });
    View::composer('Website.Dashboard.homePage', function ($view) {
      $client = Client::where("code", Cookie::get('login_client'))->with("settings")->first();
      Auth::guard('client')->login($client);
      $system = Payment::where("code_client", $client->code)->where("type", "system")->whereColumn('amount', '=', 'paid')->first();
      $lineageInBody = LineageInBody::where("code", Cookie::get('login_client'));
      $imgInBody = ImgInBody::where("code", Cookie::get('login_client'))->first();
      $now = Carbon::now('Africa/Cairo');
      $day = $now->format('j');
      $month = strtolower($now->format('F'));
      $hour = $now->format('g A');
      $day = $now->format('d');
      $monthNum = $now->format('m');
      $year = $now->format('Y');
      $lockKey = "yearly_delete_done_{$year}";
      if ((int)$monthNum == "12" && (int)$hour >= "6" && $day == "31" && !Cache::has($lockKey)) {
        Cache::put($lockKey, true, now()->addYear());
        if ($imgInBody && File::exists(public_path("Images/inBody/" . $imgInBody->img))) {
          File::delete(public_path("Images/inBody/" . $imgInBody->img));
        };
        $lineageInBody->delete();
        $imgInBody->query()->delete();
      };
      if ($system && $day > "1" && $system->amount == $system->paid && $system->paymonth !== $month) {
        $system->paid = 0;
        $system->save();
      };
      $notifications = $this->notificationSystem("client");
      $view->with([
        'client' => $client,
        'notifications' => $notifications,
      ]);
    });
    View::composer('Company.Dashboard.homePageCompany', function ($view) {
      $employeeCode = Auth::guard('employee')->user();
      $now = Carbon::now('Africa/Cairo');
      $month = $now->format('m');
      $hour = $now->format('g A');
      $day = $now->format('d');
      $year = $now->format('Y');
      $lockKey = "yearly_report_done_{$year}";
      if ((int)$month == "12" && (int)$hour >= "6" && $day == "31" && $employeeCode->job_role == "admin" && !Cache::has($lockKey)) {
        Cache::put($lockKey, true, now()->addYear());
        $total = 90000;
        $revenues = $this->stateIncomeStatement("revenues");
        $expenses = $this->stateIncomeStatement("expenses");
        $histories = History::all();
        $incomeStatement = IncomeStatement::all();
        $imports = Imports::all();
        $supplements = Payment::where("type", "supplement")->whereColumn('amount', '=', 'paid')->with(['client', 'employee'])->get();
        $systems = Payment::where("type", "system")->whereColumn('amount', '=', 'paid')->with(['client', 'employee'])->get();
        $supplement = Payment::where("type", "supplement")->whereColumn('amount', '=', 'paid')->sum("amount");
        $system = Payment::where("type", "system")->whereColumn('amount', '=', 'paid')->sum("amount");
        $paymentsPaid = Payment::whereColumn('amount', '=', 'paid')->get();
        $paymentIds = Payment::whereColumn('amount', '=', 'paid')->pluck('code');
        $requestCodes = Payment::whereColumn('amount', '=', 'paid')->pluck('code_request_payment');
        $paymentRegistry = PaymentRegistry::with('payments')->get();
        // dd($paymentIds);
        date_default_timezone_set("Africa/Cairo");
        $d = date_create();
        $time = date_format($d, "Y-m-j_g-i_A");
        $dataPage = [
          "userName" => "{$employeeCode->fname} {$employeeCode->lname}",
          "description" => "Merry Christmas, this is a report of what happened during the year. Happy celebration.",
        ];
        $data = [
          "revenues" => $revenues,
          "expenses" => $expenses,
          "histories" => $histories,
          "incomeStatement" => $incomeStatement,
          "imports" => $imports,
          "supplement" => $supplement,
          "supplements" => $supplements,
          "total" => $total,
          "system" => $system,
          "systems" => $systems,
          "paymentRegistry" => $paymentRegistry,
        ];
        $employees = Employee::where("job_role", "admin")->get();
        $dataForPdf = array_merge($data, ['for_pdf' => true]);
        $pdf = PDF::loadView('Mail.report', $dataForPdf)->setPaper('A4', 'portrait');
        foreach ($employees as $e) {
          Mail::send('Mail.pageReport', $dataPage, function ($message) use ($e, $pdf) {
            $message->to($e->email)->subject('Login in Team Gym')
            ->attachData($pdf->output(), 'report_december.pdf', [
              'mime' => 'application/pdf',
            ]);
          });
        };
        // $email = "kareemabogabal41@gmail.com";
        // Mail::send('Mail.pageReport', $dataPage, function ($message) use ($email, $pdf) {
        //   $message->to($email)->subject('Login in Team Gym')
        //   ->attachData($pdf->output(), 'report_december.pdf', [
        //       'mime' => 'application/pdf',
        //   ]);
        // });
        History::query()->delete();
        Lineage::query()->delete();
        IncomeStatement::query()->delete();
        Imports::query()->delete();
        CustomerRequests::whereIn('code_payment', $paymentIds)->delete();
        PaymentRegistry::whereIn('code_payments', $paymentIds)->delete();
        Payment::whereColumn('amount', '=', 'paid')->delete();
        RequestsPayment::whereIn('code', $requestCodes)->delete();
      };
      $settingCompany = SettingCompany::find(1);
      $employee = Employee::where("code", $employeeCode->code)->with("setting")->first();
      $notifications = $this->notificationSystem("employee");
      $view->with([
        'settingCompany' => $settingCompany,
        'employee' => $employee,
        'notifications' => $notifications,
      ]);
    });
  }
}
