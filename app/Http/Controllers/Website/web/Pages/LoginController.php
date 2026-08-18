<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Front\Client;
use App\Models\Front\SettingClient;
use App\Models\Back\Employee;
use App\Models\Back\Lineage;
use App\Models\Back\System;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Payment;
use App\Models\Back\CustomerRequests;
use App\Traits\Warning;
use App\Traits\GetLineage;
use App\Http\Requesters\Website\web\SignUp\SignUpRequest;
use App\Http\Requesters\Website\web\Forget\ForgetRequest;
use App\Http\Requesters\Website\web\Login\LoginRequest;
use App\Http\Requesters\Website\web\VerifyCode\VerifyCodeRequest;
use App\Http\Requesters\Website\web\ResetPassword\ResetPasswordRequest;
use App\Http\Requesters\Website\web\AddRequestProduct\AddRequestProductRequest;
use App\Http\Requesters\Website\web\AddRequestCustomer\AddRequestCustomerRequest;
use App\Http\Requesters\Website\web\DeleteCustomerRequests\DeleteCustomerRequestsRequest;
use Carbon\Carbon;

class LoginController extends Controller {
  use Warning, GetLineage;
  public function loginPage(Request $request) {
    return view('Website.web.Pages.login');
  }
  public function signUp(SignUpRequest $request) {
    $fnameLower = mb_strtolower($request->input('fname'));
    $lnameLower = mb_strtolower($request->input('lname'));
    $checkClient = Client::whereRaw('LOWER(fname) = ?', "$fnameLower")->whereRaw('LOWER(lname) = ?', "$lnameLower")->where('email', $request->input('email'))->first();
    if ($checkClient) {
      Cookie::queue(Cookie::forever('login_client', $checkClient->code));
      session(['client' => $checkClient]);
      $clientRequests = CustomerRequests::where("code", Cookie::get('code_request_client'))->get();
      foreach ($clientRequests as $r) {
        $r->code_client = $checkClient->code;
        $r->email = $checkClient->email;
        $r->save();
      };
    } else {
      $system = System::where("defult", "true")->first();
      $employee = Employee::find(1);
      if (!$system) {
        notifyError(__('messages.system-empty'));
        return redirect()->back();
      };
      if (!$employee) {
        notifyError(__('messages.employee-empty'));
        return redirect()->back();
      };
      $fname = $request->input('fname');
      $lname = $request->input('lname');
      $phone = $request->input('phone');
      if (!empty($request->input('email'))) {
        $email = $request->input('email');
        $this->warning($request->input('email'), __('messages.email-empty'));
      } else {
        $email = $fname . "@gmail.com";
      };
      $password = $request->input('password');
      $this->warning($request->input('fname'), __('messages.fname-empty'));
      $this->warning($request->input('lname'), __('messages.lname-empty'));
      $this->warning($request->input('phone'), __('messages.phone-empty'));
      $this->warning($request->input('password'), __('messages.password-empty'));
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->warning($request->input('email'), __('messages.email-check'));
      };
      $rand = rand(100000, time());
      $signUp = new Client();
      $signUp->code = $rand;
      $signUp->fname = $fname;
      $signUp->lname = $lname;
      $signUp->phone = $phone;
      $signUp->email = $email;
      $signUp->password = Hash::make($password);
      $signUp->category = $system->name;
      $signUp->save();
      $clientRequests = CustomerRequests::where("code", Cookie::get('code_request_client'))->get();
      foreach ($clientRequests as $r) {
        $r->code_client = $signUp->code;
        $r->save();
      };
      $settings = new SettingClient();
      $settings->code = $rand;
      $settings->code_client = $signUp->code;
      $settings->class_reminders = 1;
      $settings->payment_date = 1;
      $settings->promotions = 1;
      $settings->save();
      $requestsPayment = new RequestsPayment();
      $requestsPayment->addRequest($signUp->code, "system", $system->code, null, $system->amount, "daily", $employee->code);
      $requestsPayment->state = "acceptance";
      $requestsPayment->save();
      $month = strtolower(Carbon::now('Africa/Cairo')->format('F'));
      if ($system) {
        $payment = new Payment();
        $payment->code = $rand;
        $payment->code_client = $signUp->code;
        $payment->code_employee = $employee->code;
        $payment->code_systems = $system->code;
        $payment->order_name = $system->name;
        $signUp->category = $system->name;
        $signUp->save();
        Lineage::addLineage(null, $system->code, null, null, null, "system", 1);
        $payment->code_request_payment = $requestsPayment->code;
        $payment->type = "system";
        $payment->amount = $system->amount;
        $payment->paid = 0;
        $payment->payday = "daily";
        $payment->paymonth = $month;
        $payment->save();
      };
      $client = Client::find($signUp->id);
      Cookie::queue(Cookie::forever('login_client', $rand));
      session(['client' => $client]);
    };
    return redirect()->route('front');
  }
  public function forget(ForgetRequest $request) {
    $email = $request->input('email');
    $client = Client::where('email', $email)->first();
    if (!$client) {
      return redirect()->back()->withErrors(['email' => __('messages.email-not-registered')])->withInput($request->only('email'));
    };
    $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    date_default_timezone_set("Africa/Cairo");
    $d = date_create();
    $time = date_format($d, "Y-m-j_g-i_A");
    $data = ["userName" => "$client->fname $client->lname", 'name' => "$client->fname", 'code' => "$client->code", 'time' => "$time", "phone" => "$client->phone", 'verificationCode' => "$code"];
    Mail::send('Mail.pageMail', $data, function ($message) use ($email) {
      $message->embed(public_path('images/header/Team-Gym.png'));
      $message->to($email)->subject('Verification Code — Team Gym');
    });
    Cookie::queue(Cookie::forever('temporary', $code));
    return redirect()->route('loginPage');
  }
  public function verifyCode(VerifyCodeRequest $request) {
    $temporary = Cookie::get('temporary');
    if (!$temporary) {
      return redirect()->route('loginPage');
    };
    if ($temporary !== $request->input('code')) {
      return back()->withErrors(['code' => __('messages.verification-code-invalid')]);
    };
    Cookie::queue(Cookie::forget('temporary'));
    Cookie::queue(Cookie::forever('verified', $temporary));
    return redirect()->route('loginPage');
  }
  public function resetPassword(ResetPasswordRequest $request) {
    $verified = Cookie::get('verified');
    if (!$verified) {
      return redirect()->route('loginPage');
    };
    $email = $request->input('email');
    $client = Client::where('email', $email)->first();
    if (!$client) {
      return redirect()->back()->withErrors(['email' => __('messages.email-not-registered')])->withInput($request->only('email'));
    };
    $client->password = Hash::make($request->input('password'));
    $client->save();
    Cookie::queue(Cookie::forget('verified'));
    return redirect()->route('loginPage');
  }
  public function login(LoginRequest $request) {
    $client = Client::where('email', $request->input('email'))->first();
    if (!$client) {
      return redirect()->back()->withErrors(['email' => __('messages.email-not-registered')])->withInput($request->only('email'));
    };
    if (!Hash::check($request->input('password'), $client->password)) {
      return redirect()->back()->withErrors(['password' => __('messages.password-incorrect')])->withInput($request->only('email'));
    };
    Cookie::queue(Cookie::forever('login_client', $client->code));
    $clientRequests = CustomerRequests::where("code", Cookie::get('code_request_client'))->get();
    foreach ($clientRequests as $r) {
      $r->code_client = $client->code;
      $r->save();
    };
    return redirect()->route('front');
  }
}