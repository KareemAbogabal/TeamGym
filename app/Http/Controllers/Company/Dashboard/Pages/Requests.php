<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Front\Client;
use App\Models\Front\SettingClient;
use App\Models\Back\Employee;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Payment;
use App\Models\Back\PaymentRegistry;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\Imports;
use App\Models\Back\Snacks;
use App\Models\Back\History;
use App\Models\Back\Lineage;
use App\Models\Back\CustomerRequests;
use App\Traits\GetLineage;
use Carbon\Carbon;

class Requests extends Controller {
  use GetLineage;
  public function index(Request $request) {
    $requestsPayment = RequestsPayment::with(['client', 'employee'])->get();
    $customerRequests = CustomerRequests::all();
    $supplements = $this->get(Lineage::class, "supplement", false);
    return view('Company.Dashboard.Pages.requests', compact("requestsPayment", "supplements", "customerRequests"));
  }
  public function addPayments(Request $request) {
    $request->validate([
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
      'code_client' => ['required', 'string'],
      'code_supplements' => ['nullable', 'string'],
      'code_systems' => ['nullable', 'string'],
      'code_snacks' => ['nullable', 'string'],
      'code_request_payment' => ['required', 'string'],
      'order_name' => ['required', 'string'],
      'amount' => ['required', 'string'],
      'payday' => ['required', 'string'],
      'action' => ['required', 'string'],
    ]);
    $rand = rand(100000, time());
    $action = $request->input('action');
    $employee = Auth::guard('employee')->user();
    $requestsPayment = RequestsPayment::where("code", $request->input("code_request_payment"))->first();
    $client = Client::where("code", $requestsPayment->code_client)->first();
    if ($action == "acceptance") {
      $getSupplement = Supplement::where("code", $request->input("code_supplements"))->first();
      $getSystem = System::where("code", $request->input("code_systems"))->first();
      $getSnacks = Snacks::where("code", $request->input("code_snacks"))->first();
      $month = strtolower(Carbon::now('Africa/Cairo')->format('F'));
      $paymentSystem = Payment::where("code_client", $client->code)->where("type", "system")->first();
      $payment = new Payment();
      $payment->code = $rand;
      $payment->code_client = $request->input("code_client");
      $payment->code_employee = $employee->code;
      if ($getSupplement) {
        $payment->code_supplements = $getSupplement->code;
        $payment->order_name = $getSupplement->name;
        $payment->type = "supplement";
        Lineage::addLineage($getSupplement->code, null, null, null, null, $request->input("order_name"), 1);
        Imports::updateQuantit($getSupplement->code, null);
      } else if ($getSystem) {
        if ($paymentSystem) {
          $paymentSystem->code_systems = $getSystem->code;
          $paymentSystem->order_name = $getSystem->name;
          $client->category = $getSystem->name;
          $paymentSystem->type = "system";
          $paymentSystem->code_request_payment = $request->input("code_request_payment");
          $paymentSystem->amount = $request->input("amount");
          $paymentSystem->paid = 0;
          $paymentSystem->payday = $request->input("payday");
          $paymentSystem->paymonth = $month;
          $paymentSystem->save();
          $requestsPayment->state = "acceptance";
          $requestsPayment->save();
          $client->save();
          Lineage::addLineage(null, $getSystem->code, null, null, null, $request->input("order_name"), 1);
        } else {
          $payment->code_systems = $getSystem->code;
          $payment->order_name = $getSystem->name;
          $client->category = $getSystem->name;
          $payment->type = "system";
          Lineage::addLineage(null, $getSystem->code, null, null, null, $request->input("order_name"), 1);
        };
      } else if ($getSnacks) {
        $payment->code_snacks = $getSnacks->code;
        $payment->order_name = $getSnacks->name;
        $payment->type = "snacks";
        Lineage::addLineage(null, null, null, null, $getSnacks->code, $request->input("order_name"), 1);
        Imports::updateQuantit(null, $getSnacks->code);
      };
      $payment->code_request_payment = $request->input("code_request_payment");
      $payment->amount = $request->input("amount");
      $payment->paid = 0;
      $payment->payday = $request->input("payday");
      $payment->paymonth = $month;
      $payment->save();
      $requestsPayment->state = "acceptance";
      $requestsPayment->save();
      $client->save();
    } else {
      $requestsPayment->state = "reject";
      $requestsPayment->save();
    };
    $history = new History();
    $history->recordHistory("$employee->fname $employee->lname", null, $employee->code, "acceptance", $request->input("amount"), $request->input("order_name"), "$employee->fname $employee->lname");
    return back();
  }
  public function customerRequests(Request $request) {
    $request->validate([
      'id_request' => ['required', 'integer', 'exists:customer_requests,id'],
      'code_order' => ['required', 'string'],
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
      'email' => ['nullable', 'email'],
      'phone' => ['required', 'string'],
      'order_name' => ['required', 'string'],
      'action' => ['required', Rule::in(['acceptance','send','reject'])],
      'paid' => ['nullable','numeric','min:0'],
    ]);
    $randClient = rand(100000, time());
    $rand = rand(100000, time());
    $action = $request->input('action');
    $employee = Auth::guard('employee')->user();
    $client = Client::whereRaw('LOWER(fname) = ?', [mb_strtolower($request->input("fname"))])->whereRaw('LOWER(lname) = ?', [mb_strtolower($request->input("lname"))])->first();
    $customerRequests = CustomerRequests::find($request->input("id_request"));
    $getSupplement = Supplement::where("code", $request->input("code_order"))->first();
    $getSystem = System::where("code", $request->input("code_order"))->first();
    if ($action == "acceptance" && $customerRequests->state == "request") {
      $customerRequests->state = "waiting";
      $customerRequests->save();
    } else if ($action == "send" && $customerRequests->state == "waiting") {
      $payday = Carbon::now()->format('l');
      $requestsPayment = new RequestsPayment();
      if ($customerRequests->type == "system" && $client) {
        $requestsPayment->addRequest($client->code, $request->input("order_name"), $request->input("code_order"), null, $getSystem->amount, $payday, $employee->code);
      } else if ($customerRequests->type == "system" && !$client) {
        if (!$getSystem) {
          return redirect()->back()->with('error', __('messages.system-empty'));
        };
        if (!$employee) {
          return redirect()->back()->with('error', __('messages.employee-empty'));
        };
        $fname = $customerRequests->fname;
        $lname = $customerRequests->lname;
        $phone = $customerRequests->phone;
        if ($customerRequests->email) {
          $email = $customerRequests->email;
        } else {
          $email = $fname . "@gmail.com";
        };
        $password = Str::lower(Str::random(8));
        $rand = rand(100000, time());
        $randSetting = rand(100000, time());
        $randPayment = rand(100000, time());
        $clientNew = new Client();
        $clientNew->code = $rand;
        $clientNew->fname = $fname;
        $clientNew->lname = $lname;
        $clientNew->phone = $phone;
        $clientNew->email = $email;
        $clientNew->password = Hash::make($password);
        $clientNew->category = $getSystem->name;
        $clientNew->save();
        // $clientRequests = CustomerRequests::where("code", Cookie::get('code_request_client'))->get();
        $customerRequests->code_client = $rand;
        $customerRequests->save();
        $settings = new SettingClient();
        $settings->code = $randSetting;
        $settings->code_client = $clientNew->code;
        $settings->class_reminders = 1;
        $settings->payment_date = 1;
        $settings->promotions = 1;
        $settings->save();
        // $requestsPayment = new RequestsPayment();
        // $requestsPayment->addRequest($clientNew->code, "system", $getSystem->code, null, $getSystem->amount, "daily", $employee->code);
        $requestsPayment->addRequest($clientNew->code, $request->input("order_name"), $request->input("code_order"), null, $getSystem->amount, $payday, $employee->code);
        $requestsPayment->state = "acceptance";
        $requestsPayment->save();
        $month = strtolower(Carbon::now('Africa/Cairo')->format('F'));
        $payday = Carbon::now()->format('l');
        if ($getSystem) {
          $payment = new Payment();
          $payment->code = $randPayment;
          $payment->code_client = $clientNew->code;
          $payment->code_employee = $employee->code;
          $payment->code_systems = $getSystem->code;
          $payment->order_name = $getSystem->name;
          // $clientNew->category = $getSystem->name;
          // $clientNew->save();
          Lineage::addLineage(null, $getSystem->code, null, null, null, "system", 1);
          $payment->code_request_payment = $requestsPayment->code;
          $payment->type = "system";
          $payment->amount = $getSystem->amount;
          $payment->paid = 0;
          $payment->payday = $payday;
          $payment->paymonth = $month;
          $payment->save();
        };
        $employeeAdmin = Employee::where("job_role", "admin")->get();
        foreach ($employeeAdmin as $e) {
          date_default_timezone_set("Africa/Cairo");
          $d = date_create();
          $time = date_format($d, "Y-m-j_g-i_A");
          $data = ["userName" => "$e->fname $e->lname", 'name' => "$clientNew->fname $clientNew->lname", 'code' => "$clientNew->code", 'time' => "$time", "phone" => "$clientNew->phone", 'password' => "$password"];
          Mail::send('Mail.pageMail', $data, function ($message) use ($e) {
            $message->embed(public_path('images/header/Team-Gym.png'));
            $message->to($e->email)->subject('Sign up in Team Gym');
          });
        };
        $customerRequests->code_payment = $payment->code;
        $customerRequests->save();
      } else if ($customerRequests->type == "supplement" && ($client || $customerRequests)) {
        $requestsPayment->addRequest($client ? $client->code : $customerRequests->code, $request->input("order_name"), $request->input("code_order"), null, $getSupplement->amount, $payday, $employee->code);
        $month = strtolower(Carbon::now('Africa/Cairo')->format('F'));
        $payment = new Payment();
        $payment->code = $rand;
        $payment->code_client = $client ? $client->code : $customerRequests->code;
        $payment->code_employee = $employee->code;
        if ($getSupplement) {
          $payment->code_supplements = $getSupplement->code;
          $payment->order_name = $getSupplement->name;
          Lineage::addLineage($getSupplement->code, null, null, null, null, $request->input("order_name"), $customerRequests->quantity);
        };
        if ($requestsPayment) {
          $payment->code_request_payment = $requestsPayment->code;
        };
        if ($getSupplement) {
          $payment->type = "supplement";
          $payment->amount = $getSupplement->amount;
          $payment->paid = 0;
          $payment->payday = $payday;
          $payment->paymonth = $month;
          $payment->save();
          $requestsPayment->state = "acceptance";
          $requestsPayment->save();
          $customerRequests->code_payment = $payment->code;
          $customerRequests->save();
          if (($client || $customerRequests) && $request->input("paid") > 0) {
            $paymentAcceptance = Payment::where("code", $customerRequests->code_payment)->first();
            $customerRequests->state = "acceptance";
            $customerRequests->paid = $request->input("paid");
            $paymentAcceptance->paid = $request->input("paid");
            Imports::updateQuantit($customerRequests->code_order, null, $customerRequests->quantity);
            $randPaymentRegistry = rand(100000, time());
            $date = strtolower(Carbon::now('Africa/Cairo')->format('F'));
            $paymentRegistry = new PaymentRegistry();
            $paymentRegistry->code = $randPaymentRegistry;
            $paymentRegistry->order_name = $paymentAcceptance->order_name;
            $paymentRegistry->type = $paymentAcceptance->type;
            $paymentRegistry->amount = $request->input("paid");
            $paymentRegistry->paymonth = $date;
            $paymentRegistry->code_payments = $paymentAcceptance->code;
            $paymentRegistry->code_employee = $employee->code;
            $paymentAcceptance->save();
            $paymentRegistry->save();
            $customerRequests->save();
          };
        };
      };
    } else if ($action == "reject" && $customerRequests->state == "request") {
      $requestsPayment->state = "reject";
      $requestsPayment->save();
    } else {
      return back()->withErrors(['error' => 'Request rejected']);
    };
    $history = new History();
    if ($getSystem) {
      $history->recordHistory("$employee->fname $employee->lname", null, $employee->code, "acceptance", $getSystem->amount, $request->input("order_name"), "$employee->fname $employee->lname");
    } else {
      $history->recordHistory("$employee->fname $employee->lname", null, $employee->code, "acceptance", $getSupplement->amount, $request->input("order_name"), "$employee->fname $employee->lname");
    };
    // $CustomerRequests->delete();
    return back();
  }
}
