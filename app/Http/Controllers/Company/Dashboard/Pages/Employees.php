<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requesters\Company\Dashboard\AddEmployee\AddEmployeeRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Back\Employee;
use App\Models\Back\SettingEmployee;
use App\Models\Back\SettingCompany;
use App\Support\Roles;

class Employees extends Controller {
  public function addEmployee(AddEmployeeRequest $request) {
    // Route carries CheckAdmin; double-check the caller is actually admin.
    abort_unless(\Illuminate\Support\Facades\Gate::allows('admin'), 403);

    $role = Roles::normalize($request->input('job_role'));
    // Never trust a submitted role name: only canonical roles are accepted.
    if (!Roles::isAllowed($role)) {
      return back()->withErrors(['job_role' => __('messages.invalid-role')])->withInput();
    }

    $email = mb_strtolower(trim($request->input('email')));
    if (Employee::whereRaw('LOWER(email) = ?', [$email])->exists()) {
      return back()->withErrors(['email' => __('messages.email-exists')])->withInput();
    }

    $rand = random_int(100000, 999999999);
    $randSetting = random_int(100000, 999999999);
    $employee = new Employee();
    $settingEmployee = new SettingEmployee();
    $employee->code = $rand;
    $employee->fname = $request->input("fname");
    $employee->lname = $request->input("lname");
    $employee->job_role = $role;
    $employee->phone = $request->input("phone");
    if ($request->has("img")) {
      date_default_timezone_set("Africa/Cairo");
      $d = date_create();
      $new_name = date_format($d, "Y-m-j_g-i_A") . "-" . random_int(1000, 9999) . "." . $request->img->extension();
      $request->img->move(public_path("images/employee"), $new_name);
      $employee->img = $new_name;
    };
    $employee->email = $email;
    $employee->password = Hash::make($request->input("password"));
    $employee->documentation = $request->has("documentation") ? "true" : "false";
    $employee->save();
    $settingEmployee->code = $randSetting;
    $settingEmployee->code_employee = $employee->code;
    $settingEmployee->class_reminders = true;
    $settingEmployee->save();

    if ($role === Roles::ADMIN) {
      $randSettingAdmin = random_int(100000, 999999999);
      $settingCompany = new SettingCompany();
      $settingCompany->code = $randSettingAdmin;
      $settingCompany->code_employee = $employee->code;
      $settingCompany->view_logs_logins = true;
      $settingCompany->subscription_requests = true;
      $settingCompany->add_employees = false;
      $settingCompany->subscription_application_form = true;
      $settingCompany->save();
    }

    Log::channel('security')->info('employee created', [
      'employee_code' => $employee->code,
      'role' => $role,
      'by' => auth('employee')->user()?->code,
    ]);

    notifySuccess(__('messages.saved-successfully'));
    return back();
  }
}