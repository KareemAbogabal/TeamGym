<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Back\Employee;
use App\Models\Back\SettingEmployee;
use App\Models\Back\SettingCompany;

class SettingCompanys extends Controller {
  public function index(Request $request) {
    $user = Auth::guard('employee')->user();
    if ($user->job_role == "Admin") {
      $employee = Employee::where("code", $user->code)->with(["setting", "settingAdmin"])->first();
    } else {
      $employee = Employee::where("code", $user->code)->with("setting")->first();
    };
    return view('Company.Dashboard.Pages.settings', compact("employee"));
  }
  public function updateEmployeeProfile(Request $request) {
    $request->validate([
      'fname' => ['required', 'string', 'max:100'],
      'lname' => ['required', 'string', 'max:100'],
      'email' => ['required', 'email', 'max:255'],
      'phone' => ['required', 'string', 'max:20'],
      'password' => ['nullable'],
      'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
      'view_logs_logins' => ['nullable', 'in:on,1,true,false,0'],
      'supplements_requests' => ['nullable', 'in:on,1,true,false,0'],
      'subscription_requests' => ['nullable', 'in:on,1,true,false,0'],
      'add_employees' => ['nullable', 'in:on,1,true,false,0'],
      'subscription_application_form' => ['nullable', 'in:on,1,true,false,0'],
      'class_reminders' => ['nullable', 'in:on,1,true,false,0'],
      'login_alerts' => ['nullable', 'in:on,1,true,false,0'],
      'action' => ['required'],
    ]);
    $user = Auth::guard('employee')->user();
    if (!$user) {
      abort(403, 'Unauthorized');
    };
    $employee = Employee::where('code', $user->code)->firstOrFail();
    if ($request->input('action') !== 'removePhoto') {
      if ($request->hasFile('image')) {
        $oldPath = public_path('images/employee/' . $employee->img);
        if ($employee->img && File::exists($oldPath)) {
          File::delete($oldPath);
        };
        $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images/employee'), $imageName);
        $employee->img = $imageName;
      };
      $employee->fname = $request->input('fname');
      $employee->lname = $request->input('lname');
      $employee->email = $request->input('email');
      $employee->phone = $request->input('phone');
      if ($request->filled('password')) {
        $employee->password = bcrypt($request->input('password'));
      };
      $employee->save();
      $settings = SettingEmployee::where("code_employee", $employee->code)->first();
      $settings->class_reminders = $request->has('class_reminders') ? 1 : 0;
      $settings->login_alerts = $request->has('login_alerts') ? 1 : 0;
      $settings->save();
      if (strtolower($employee->job_role ?? '') === 'admin' || strtolower($employee->job_role ?? '') === 'administrator') {
        $company = SettingCompany::all();
        foreach ($company as $c) {
          $c->view_logs_logins = $request->has('view_logs_logins') ? 1 : 0;
          $c->supplements_requests = $request->has('supplements_requests') ? 1 : 0;
          $c->subscription_requests = $request->has('subscription_requests') ? 1 : 0;
          $c->add_employees = $request->has('add_employees') ? 1 : 0;
          $c->subscription_application_form = $request->has('subscription_application_form') ? 1 : 0;
          $c->save();
        };
      };
    } else {
      $oldPath = public_path('images/employee/' . $employee->img);
      if ($employee->img && File::exists($oldPath)) {
        File::delete($oldPath);
      };
      $employee->img = null;
      $employee->save();
    };
    return back();
  }
}
