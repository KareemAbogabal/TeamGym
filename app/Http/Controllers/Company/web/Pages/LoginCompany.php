<?php

namespace App\Http\Controllers\Company\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requesters\Company\web\Login\LoginCompanyRequest;
use App\Http\Requesters\Company\web\Forget\ForgetCompanyRequest;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Back\Employee;
use App\Models\Back\History;

class LoginCompany extends Controller {
  public function index(Request $request) {
    return view('Company.web.Pages.loginCompany');
  }
  public function login(LoginCompanyRequest $request) {
    $fname = $request->input("fname");
    $email = $request->input("email");
    $password = $request->input("password");
    $employee = Employee::where("email", $email)->first();
    if (Cookie::get('temporary_company')) {
      $request->validate([
        'new_password' => ['required'],
      ]);
      if (Cookie::get('temporary_company') != $request->input('password')) {
        return back()->withErrors(['credentials' => 'كلمة المرور المرسلة غير صحيحة']);
      };
      $password = Hash::make($request->input('new_password'));
      $employee->password = $password;
      $employee->save();
      Cookie::queue(Cookie::forget('temporary_company'));
    };
    if (!$employee) {
      return back()->withErrors(['credentials' => 'بيانات الدخول غير صحيحة']);
    };
    if ($employee->fname !== trim($fname)) {
      return back()->withErrors(['credentials' => 'الاسم الاول غير صحيح']);
    };
    if (!Hash::check($request->input('password'), $employee->password) && !Cookie::get('temporary_company')) {
      return redirect()->back()->withErrors(['password' => 'كلمة المرور غير صحيحة.'])->withInput($request->only('email'));
    };
    Auth::guard('employee')->login($employee);
    $request->session()->regenerate();
    $history = new History();
    $history->recordHistory("$employee->fname $employee->lname", null, $employee->code, "login", null, null, $fname);
    return redirect()->route("dashboardCompany");
  }
  public function forget(ForgetCompanyRequest $request) {
    $email = $request->input('email');
    $employee = Employee::where('email', $email)->first();
    $password = Str::random(10);
    date_default_timezone_set("Africa/Cairo");
    $d = date_create();
    $time = date_format($d, "Y-m-j_g-i_A");
    if ($employee) {
      $data = ["userName" => "$employee->fname $employee->lname", 'name' => "$employee->fname", 'code' => "$employee->code", 'time' => "$time", "phone" => "$employee->phone", 'password' => "$password"];
      Mail::send('Mail.pageMail', $data, function ($message) use ($email) {
        $message->embed(public_path('images/header/Team-Gym.png'));
        $message->to($email)->subject('Login in Team Gym');
      });
    };
    Cookie::queue(Cookie::forever('temporary_company', $password));
    if (!$employee) {
      return redirect()->back()->withErrors(['email' => 'هذا البريد الإلكتروني غير مسجل.'])->withInput($request->only('email'));
    };
    return redirect()->route('loginCompany');
  }
}
