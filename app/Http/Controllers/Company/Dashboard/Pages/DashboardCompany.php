<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Back\History;
use App\Models\Back\Lineage;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\Snacks;
use App\Models\Back\Record;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Imports;
use App\Models\Back\Activity;
use App\Models\Back\CustomerRequests;
use App\Models\Back\IncomeStatement;
use App\Models\Back\Payment;
use App\Models\Back\PaymentRegistry;
use App\Models\Back\SettingCompany;
use App\Events\NewRequestCreated;
use App\Traits\Collection;
use App\Traits\GetLineage;
use App\Traits\IncomeStatements;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Pusher\Pusher;
use Carbon\Carbon;

class DashboardCompany extends Controller {
  use Collection, GetLineage, IncomeStatements;
  public function index(Request $request) {
    $user = Auth::guard('employee')->user();
    $systems = $this->collectionOfRatios(IncomeStatement::class, "state", "system");
    $supplements = $this->collectionOfRatios(IncomeStatement::class, "state", "supplement");
    $imports = $this->collectionOfRatios(IncomeStatement::class, "state", "input");
    $expensesArr = $this->getArray(Lineage::class, "Expenses", false);
    $revenuesArr = $this->getArray(Lineage::class, "Revenues", false);
    $revenuesAmount = $this->stateIncomeStatement("revenues");
    $history = History::with(['client', 'employee'])->get();
    $today = Carbon::today();
    $records = Record::where("state", "entrance")->whereDate("created_at", $today)->whereNotIn('code_client', function ($query) use ($today) {
      $query->select('code_client')->from('records')->where('state', 'exit')->whereDate('created_at', $today);
    })->get();
    $recordsCount = Record::where("state", "entrance")->whereDate("created_at", $today)->whereNotIn('code_client', function ($query) use ($today) {
      $query->select('code_client')->from('records')->where('state', 'exit')->whereDate('created_at', $today);
    })->count();
    $historyCount = History::where("state", "login")->count();
    $revenues = $this->collectionOfRatios(IncomeStatement::class, "type", "Revenues");
    $expenses = $this->collectionOfRatios(IncomeStatement::class, "type", "Expenses");
    $supplement = $this->collectionOfRatios(IncomeStatement::class, "state", "supplement");
    $system = $this->collectionOfRatios(IncomeStatement::class, "state", "system");
    $settingCompany = SettingCompany::find(1);
    return view('Company.Dashboard.Pages.dashboard', compact('systems', "supplements", 'imports', 'revenuesArr', 'expensesArr', 'revenuesAmount', 'history', 'historyCount', 'revenues', 'expenses', 'supplement', 'system', 'settingCompany', 'user', 'records', 'recordsCount'));
  }
  public function search(Request $request) {
    $name  = mb_strtolower($request->input('name', ''));
    $fname = mb_strtolower($request->input('fname', ''));
    $lname = mb_strtolower($request->input('lname', ''));
    $result = [];
    $user = Auth::guard('employee')->user();
    $Client = null;
    $Employee = null;
    $History = null;
    $Lineage = null;
    $IncomeStatement = null;
    $Payment = null;
    if (mb_strtolower($user->job_role) == "admin") {
      $Client = Client::whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->first();
      $Employee = Employee::whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->first();
      $History = History::whereHas('client', function($q) use ($fname, $lname) {$q->whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname]);})->orWhereHas('employee', function($q) use ($fname, $lname) {$q->whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname]);})->first();
      $Lineage = Lineage::whereRaw('LOWER(name) = ?', [$name])->first();
      $IncomeStatement = IncomeStatement::whereRaw('LOWER(name) = ?', [$name])->first();
      $Payment = Payment::whereHas('client', function($q) use ($fname, $lname) {$q->whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname]);})->orWhereHas('employee', function($q) use ($fname, $lname) {$q->whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname]);})->first();
    };
    $Supplement = Supplement::whereRaw('LOWER(name) = ?', [$name])->first();
    $System = System::whereRaw('LOWER(name) = ?', [$name])->first();
    $Snacks = Snacks::whereRaw('LOWER(name) = ?', [$name])->first();
    $Record = Record::whereRaw('LOWER(name_client) = ?', [$name])->orWhereRaw('LOWER(name_employee) = ?', [$name])->first();
    $RequestsPayment = RequestsPayment::whereHas('client', function($q) use ($fname, $lname) {$q->whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname]);})->first();
    $Imports = Imports::whereRaw('LOWER(name) = ?', [$name])->first();
    $Activity = Activity::whereRaw('LOWER(name) = ?', [$name])->first();
    $CustomerRequests = CustomerRequests::whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->first();
    if ($name == "report" || $name == "تقرير") {
      $employeeCode = Auth::guard('employee')->user();
      $now = Carbon::now('Africa/Cairo');
      $total = 90000;
      $month = $now->format('m');
      $revenues = $this->stateIncomeStatement("revenues");
      $expenses = $this->stateIncomeStatement("expenses");
      $histories = History::all();
      $incomeStatement = IncomeStatement::all();
      $imports = Imports::all();
      $supplements = Payment::where("type", "supplement")->whereColumn('amount', '=', 'paid')->with(['client', 'employee'])->get();
      $systems = Payment::where("type", "system")->whereColumn('amount', '=', 'paid')->with(['client', 'employee'])->get();
      $supplement = Payment::where("type", "supplement")->whereColumn('amount', '=', 'paid')->sum("amount");
      $system = Payment::where("type", "system")->whereColumn('amount', '=', 'paid')->sum("amount");
      $paymentsPaid = Payment::whereColumn('amount', '=', 'paid')->with(['registries', 'requestsPayment'])->get();
      $paymentRegistry = PaymentRegistry::with('payments')->get();
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
      $filename = "report_{$time}.pdf";
      return $pdf->download($filename);
    };
    $models = compact(
      'Client', 'Employee', 'History', 'Lineage', 'Supplement', 'System', 'Snacks',
      'Record', 'RequestsPayment', 'Imports', 'Activity', 'CustomerRequests',
      'IncomeStatement', 'Payment'
    );
    if (mb_strtolower($user->job_role) == "admin") {
      $routes = [
        'Client' => route('users'),
        'Employee' => route('users'),
        'History' => route('history'),
        'Lineage' => route('analytics'),
        'Supplement' => route('imports'),
        'System' => route('imports'),
        'Snacks' => route('imports'),
        'Record' => route('records'),
        'RequestsPayment' => route('requests'),
        'Imports' => route('imports'),
        'Activity' => route('exercise'),
        'CustomerRequests' => route('requests'),
        'IncomeStatement' => route('analytics'),
        'Payment' => route('analytics'),
      ];
      $pages = [
        'Client' => 'users',
        'Employee' => 'users',
        'History' => 'history',
        'Lineage' => 'analytics',
        'Supplement' => 'imports',
        'System' => 'imports',
        'Snacks' => 'imports',
        'Record' => 'records',
        'RequestsPayment' => 'requests',
        'Imports' => 'imports',
        'Activity' => 'exercise',
        'CustomerRequests' => 'requests',
        'IncomeStatement' => 'analytics',
        'Payment' => 'analytics',
      ];
    } else {
      $routes = [
        'Supplement' => route('imports'),
        'System' => route('imports'),
        'Snacks' => route('imports'),
        'Record' => route('records'),
        'RequestsPayment' => route('requests'),
        'Imports' => route('imports'),
        'Activity' => route('exercise'),
        'CustomerRequests' => route('requests'),
      ];
      $pages = [
        'Supplement' => 'imports',
        'System' => 'imports',
        'Snacks' => 'imports',
        'Record' => 'records',
        'RequestsPayment' => 'requests',
        'Imports' => 'imports',
        'Activity' => 'exercise',
        'CustomerRequests' => 'requests',
      ];
    };
    foreach ($models as $key => $value) {
      if ($value) {
        $result[] = [
          'data' => $value,
          'route' => $routes[$key] ?? null,
          'page' => $pages[$key] ?? null,
        ];
      };
    };
    return response()->json($result);
  }
  public function searchImg(Request $request) {
    $img = $request->input('img');
    if (!$img || !trim($img)) {
      return response()->json([
        'path' => asset('images/header/Team-Gym.png')
      ]);
    }
    $paths = [
      public_path("images/subscribers/$img"),
      public_path("images/employee/$img")
    ];
    foreach ($paths as $path) {
      if (File::exists($path)) {
        return response()->json([
          'path' => asset(str_replace(public_path(), '', $path))
        ]);
      };
    };
    return response()->json([
      'path' => asset('images/header/Team-Gym.png')
    ]);
  }
  public function eventCountRequest(Request $request) {
    $page = $request->input("page");
    $today = Carbon::today();
    $records = Record::whereDate('created_at', $today)->get();
    $requestsPayment = RequestsPayment::whereDate('created_at', $today)->get();
    $customerRequests = CustomerRequests::whereDate('created_at', $today)->get();
    $imports = Imports::where('quantity', 0)->orWhere('quantity', 1)->orWhere('quantity', 2)->get();
    $historys = History::whereDate('created_at', $today)->get();
    if (!in_array('records', $page, true)) {
      foreach ($records as $record) {
        event(new NewRequestCreated(1, Auth::id(), 'records'));
      };
    };
    if (!in_array('requests', $page, true)) {
      foreach ($requestsPayment as $req) {
        event(new NewRequestCreated(1, Auth::id(), 'requests'));
      };
      foreach ($customerRequests as $req) {
        event(new NewRequestCreated(1, Auth::id(), 'requests'));
      };
    };
    if (!in_array('imports', $page, true)) {
      foreach ($imports as $h) {
        event(new NewRequestCreated(1, Auth::id(), 'imports'));
      };
    };
    if (!in_array('historys', $page, true)) {
      foreach ($historys as $h) {
        event(new NewRequestCreated(1, Auth::id(), 'historys'));
      };
    };
    // $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER'), 'useTLS' => true]);
    // $response = $pusher->trigger('requests', 'NewRequestCreated', ['count' => 1]);
    return response()->json(['status' => 'ok']);
  }
}
