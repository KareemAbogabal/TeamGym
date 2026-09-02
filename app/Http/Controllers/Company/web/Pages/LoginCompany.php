<?php

namespace App\Http\Controllers\Company\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requesters\Company\web\Login\LoginCompanyRequest;
use App\Http\Requesters\Company\web\Forget\ForgetCompanyRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Back\Employee;
use App\Models\Back\History;
use App\Services\ResetCodeService;

class LoginCompany extends Controller {
  public function index(Request $request) {
    if (Auth::guard('employee')->check()) {
      return redirect()->route('dashboardCompany');
    }
    return view('Company.web.Pages.loginCompany');
  }

  public function login(LoginCompanyRequest $request) {
    $email = mb_strtolower(trim($request->input('email')));
    $password = (string) $request->input('password');

    $resetEmail = session('company_reset_email');

    // Reset mode: a reset was started for this browser and the emailed code is returned.
    if ($resetEmail) {
      if ($resetEmail !== $email) {
        return back()->withErrors(['credentials' => __('messages.reset-in-progress')])->withInput($request->only('email'));
      }
      $code = (string) $request->input('reset_code', '');
      if (!ResetCodeService::verify(ResetCodeService::TYPE_EMPLOYEE, $resetEmail, $code)) {
        return back()->withErrors(['credentials' => __('messages.reset-invalid')])->withInput($request->only('email'));
      }
      $newPassword = (string) $request->input('new_password', '');
      if (strlen($newPassword) < 8) {
        return back()->withErrors(['new_password' => __('messages.password-min')])->withInput($request->only('email'));
      }
      $employee = Employee::whereRaw('LOWER(email) = ?', [mb_strtolower($resetEmail)])->first();
      if (!$employee) {
        session()->forget('company_reset_email');
        return back()->withErrors(['credentials' => __('messages.reset-in-progress')])->withInput($request->only('email'));
      }
      $employee->password = Hash::make($newPassword);
      $employee->save();
      session()->forget('company_reset_email');
      Log::channel('security')->info('employee password reset completed', ['employee_code' => $employee->code]);
      Auth::guard('employee')->login($employee);
      $request->session()->regenerate();
      $request->session()->regenerateToken();
      return redirect()->route('dashboardCompany');
    }

    $employee = Employee::whereRaw('LOWER(email) = ?', [$email])->first();
    if (!$employee || !Hash::check($password, $employee->password)) {
      Log::channel('security')->warning('employee login failed', ['email' => substr($email, 0, 3) . '***']);
      return back()->withErrors(['credentials' => __('messages.login-invalid')]);
    }
    if (Hash::needsRehash($employee->password)) {
      $employee->password = Hash::make($password);
      $employee->save();
    }

    Auth::guard('employee')->login($employee);
    $request->session()->regenerate();
    $request->session()->regenerateToken();
    $history = new History();
    $history->recordHistory("$employee->fname $employee->lname", null, $employee->code, "login", null, null, "system");
    Log::channel('security')->info('employee login success', ['employee_code' => $employee->code]);
    return redirect()->route('dashboardCompany');
  }

  public function forget(ForgetCompanyRequest $request) {
    $email = mb_strtolower(trim($request->input('email')));
    $employee = Employee::whereRaw('LOWER(email) = ?', [$email])->first();
    // Generic response either way — no account enumeration.
    if ($employee) {
      $code = ResetCodeService::issue(ResetCodeService::TYPE_EMPLOYEE, $email);
      date_default_timezone_set("Africa/Cairo");
      $d = date_create();
      $time = date_format($d, "Y-m-j_g-i_A");
      $data = [
        "userName" => "$employee->fname $employee->lname",
        'name' => "$employee->fname",
        'code' => "$employee->code",
        'time' => "$time",
        "phone" => "$employee->phone",
        'verificationCode' => "$code",
      ];
      try {
        Mail::send('Mail.pageMail', $data, function ($message) use ($email) {
          $message->embed(public_path('images/header/Team-Gym.png'));
          $message->to($email)->subject('Verification Code — Team Gym');
        });
      } catch (\Throwable $e) {
        Log::channel('security')->warning('employee reset mail failed', ['email' => substr($email, 0, 3) . '***']);
      }
    } else {
      Log::channel('security')->warning('employee reset requested for unknown email', ['email' => substr($email, 0, 3) . '***']);
    }
    session(['company_reset_email' => $email]);
    return redirect()->route('loginCompany')->with('status', __('messages.reset-code-sent'));
  }
}