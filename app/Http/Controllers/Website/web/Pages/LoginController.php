<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
use App\Services\ResetCodeService;
use App\Http\Requesters\Website\web\SignUp\SignUpRequest;
use App\Http\Requesters\Website\web\Forget\ForgetRequest;
use App\Http\Requesters\Website\web\Login\LoginRequest;
use App\Http\Requesters\Website\web\VerifyCode\VerifyCodeRequest;
use App\Http\Requesters\Website\web\ResetPassword\ResetPasswordRequest;
use Carbon\Carbon;

class LoginController extends Controller {
  use Warning, GetLineage;

  public function loginPage(Request $request) {
    return view('Website.web.Pages.login');
  }

  public function signUp(SignUpRequest $request) {
    $fnameLower = mb_strtolower(trim($request->input('fname')));
    $lnameLower = mb_strtolower(trim($request->input('lname')));
    $email = mb_strtolower(trim($request->input('email', '')));
    $email = $email !== '' ? $email : ($request->input('fname') . "@gmail.com");

    $exists = Client::whereRaw('LOWER(email) = ?', [$email])->exists();
    if ($exists) {
      return redirect()->route('loginPage')->withErrors(['email' => __('messages.already-registered')]);
    }

    $system = System::where("defult", "true")->first();
    $employee = Employee::find(1);
    if (!$system || !$employee) {
      notifyError(__('messages.system-empty'));
      return redirect()->back();
    }

    $fname = $request->input('fname');
    $lname = $request->input('lname');
    $phone = $request->input('phone');
    $password = $request->input('password');
    $this->warning($fname, __('messages.fname-empty'));
    $this->warning($lname, __('messages.lname-empty'));
    $this->warning($phone, __('messages.phone-empty'));
    $this->warning($password, __('messages.password-empty'));

    $rand = random_int(100000, 999999999);
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
    }

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
    }

    $client = Client::find($signUp->id);
    Auth::guard('client')->login($client, true);
    $request->session()->regenerate();
    $request->session()->regenerateToken();
    Cookie::queue(Cookie::forever('login_client', $rand));
    session(['client' => $client]);

    return redirect()->route('front');
  }

  public function forget(ForgetRequest $request) {
    $email = mb_strtolower(trim($request->input('email')));
    $client = Client::whereRaw('LOWER(email) = ?', [$email])->first();
    // Same generic response whether or not the account exists (no enumeration).
    if ($client) {
      $code = ResetCodeService::issue(ResetCodeService::TYPE_CLIENT, $email);
      date_default_timezone_set("Africa/Cairo");
      $d = date_create();
      $time = date_format($d, "Y-m-j_g-i_A");
      $data = [
        "userName" => "$client->fname $client->lname",
        'name' => "$client->fname",
        'code' => "$client->code",
        'time' => "$time",
        "phone" => "$client->phone",
        'verificationCode' => "$code",
      ];
      try {
        Mail::send('Mail.pageMail', $data, function ($message) use ($email) {
          $message->embed(public_path('images/header/Team-Gym.png'));
          $message->to($email)->subject('Verification Code — Team Gym');
        });
      } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::channel('security')->warning('client reset mail failed', ['email' => substr($email, 0, 3) . '***']);
      }
    } else {
      \Illuminate\Support\Facades\Log::channel('security')->warning('client reset requested for unknown email', ['email' => substr($email, 0, 3) . '***']);
    }
    session(['client_reset_email' => $email]);
    return redirect()->route('loginPage')->with('status', __('messages.reset-code-sent'));
  }

  public function verifyCode(VerifyCodeRequest $request) {
    $email = session('client_reset_email');
    if (!$email) {
      return redirect()->route('loginPage');
    }
    if (!ResetCodeService::verify(ResetCodeService::TYPE_CLIENT, $email, $request->input('code'))) {
      return back()->withErrors(['code' => __('messages.reset-invalid')]);
    }
    session(['client_reset_verified' => true]);
    return redirect()->route('loginPage');
  }

  public function resetPassword(ResetPasswordRequest $request) {
    $email = session('client_reset_email');
    if (!$email || !session('client_reset_verified')) {
      return redirect()->route('loginPage')->withErrors(['error' => __('messages.reset-in-progress')]);
    }
    $client = Client::whereRaw('LOWER(email) = ?', [mb_strtolower($email)])->first();
    if (!$client) {
      $request->session()->forget(['client_reset_email', 'client_reset_verified']);
      return redirect()->route('loginPage')->withErrors(['error' => __('messages.reset-in-progress')]);
    }
    $client->password = Hash::make($request->input('password'));
    $client->save();
    $request->session()->forget(['client_reset_email', 'client_reset_verified']);
    \Illuminate\Support\Facades\Log::channel('security')->info('client password reset completed', ['client_code' => $client->code]);
    return redirect()->route('loginPage')->with('status', __('messages.saved-successfully'));
  }

  public function login(LoginRequest $request) {
    $email = mb_strtolower(trim($request->input('email')));
    $client = Client::whereRaw('LOWER(email) = ?', [$email])->first();
    $password = (string) $request->input('password');

    // One generic failure response — avoids user enumeration.
    if (!$client || !Hash::check($password, $client->password)) {
      \Illuminate\Support\Facades\Log::channel('security')->warning('client login failed', ['email' => substr($email, 0, 3) . '***']);
      return redirect()->back()->withErrors(['credentials' => __('messages.login-invalid')]);
    }
    if (Hash::needsRehash($client->password)) {
      $client->password = Hash::make($password);
      $client->save();
    }

    Auth::guard('client')->login($client, true);
    $request->session()->regenerate();
    $request->session()->regenerateToken();
    Cookie::queue(Cookie::forever('login_client', $client->code));
    session(['client' => $client]);

    $clientRequests = CustomerRequests::where("code", Cookie::get('code_request_client'))->get();
    foreach ($clientRequests as $r) {
      $r->code_client = $client->code;
      $r->email = $client->email;
      $r->save();
    }
    \Illuminate\Support\Facades\Log::channel('security')->info('client login success', ['client_code' => $client->code]);

    return redirect()->route('front');
  }
}