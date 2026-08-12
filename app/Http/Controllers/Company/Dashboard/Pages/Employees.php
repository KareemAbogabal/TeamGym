<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requesters\Company\Dashboard\AddEmployee\AddEmployeeRequest;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Models\Back\Employee;
use App\Models\Back\SettingEmployee;
use App\Models\Back\SettingCompany;

class Employees extends Controller {
  public function addEmployee(AddEmployeeRequest $request) {
    $rand = rand(100000, time());
    $randSetting = rand(100000, time());
    $employee = new Employee();
    $settingEmployee = new SettingEmployee();
    $employee->code = $rand;
    $employee->fname = $request->input("fname");
    $employee->lname = $request->input("lname");
    $employee->job_role = $request->input("job_role");
    $employee->phone = $request->input("phone");
    if ($request->has("img")) {
      date_default_timezone_set("Africa/Cairo");
      $d = date_create();
      $new_name = date_format($d, "Y-m-j_g-i_A") . "." . $request->img->extension();
      $request->img->move(public_path("images/employee"), $new_name);
      $employee->img = $new_name;
    };
    $employee->email = $request->input("email");
    $employee->password = Hash::make($request->input("password"));
    $employee->documentation = $request->has("documentation") ? "true" : "false";
    $employee->save();
    $settingEmployee->code = $randSetting;
    $settingEmployee->code_employee = $employee->code;
    $settingEmployee->class_reminders = true;
    $settingEmployee->save();
    if ($request->input("job_role") == "Admin") {
      $randSettingAdmin = rand(100000, time());
      $settingCompany = new SettingCompany();
      $settingCompany->code = $randSettingAdmin;
      $settingCompany->code_employee = $employee->code;
      $settingCompany->view_logs_logins = true;
      $settingCompany->subscription_requests = true;
      $settingCompany->add_employees = false;
      $settingCompany->subscription_application_form = true;
      $settingCompany->save();
    };
    return back();
  }
}
