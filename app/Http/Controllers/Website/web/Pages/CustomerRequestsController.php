<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\RequestsPayment;
use App\Models\Back\History;
use App\Models\Back\CustomerRequests;
use App\Models\Back\SettingCompany;
use App\Traits\Warning;
use App\Traits\GetLineage;
use App\Events\NewRequestCreated;
use Carbon\Carbon;
use App\Http\Requesters\Website\web\AddRequestProduct\AddRequestProductRequest;
use App\Http\Requesters\Website\web\AddRequestCustomer\AddRequestCustomerRequest;
use App\Http\Requesters\Website\web\DeleteCustomerRequests\DeleteCustomerRequestsRequest;

class CustomerRequestsController extends Controller {
  use Warning, GetLineage;
  public function addRequestProduct(AddRequestProductRequest $request) {
    $employee = Employee::find(1);
    $settingCompany = SettingCompany::find(1);
    if ($settingCompany->supplements_requests != true) {
      return redirect()->back()->withErrors(['error' => __('messages.request-not-allowed')]);
    };
    $client = Client::where("code", Cookie::get('login_client'))->first();
    if (!$client) {
      return redirect()->back()->withErrors(['error' => __('messages.not-registered')]);
    };
    $payday = Carbon::now()->format('l');
    event(new NewRequestCreated(1, Auth::id(), 'requests'));
    $requestsPayment = new RequestsPayment();
    $requestsPayment->addRequest($client->code, $request->input("order_name"), $request->input("code"), null, $request->input("amount"), $payday, $employee->code);
    $history = new History();
    $history->recordHistory("$client->fname $client->lname", $client->code, null, "request", null, $request->input("order_name"), "system");
    return back();
  }
  public function addRequestCustomer(AddRequestCustomerRequest $request) {
    $settingCompany = SettingCompany::find(1);
    if ($settingCompany->subscription_application_form != true) {
      return redirect()->back()->withErrors(['error' => __('messages.request-not-allowed')]);
    };
    $codes = $request->input('code', []);
    $types = $request->input('type', []);
    $order_names = $request->input('order_name', []);
    $quantity = $request->input('quantity', []);
    $fname = $request->input('fname');
    $lname = $request->input('lname');
    $phone = $request->input('phone');
    $email = $request->input('email');
    foreach ($codes as $index => $c) {
      $currentType = isset($types[$index]) ? $types[$index] : null;
      $currentOrderName = isset($order_names[$index]) ? trim($order_names[$index]) : '';
      $currentQuantity = isset($quantity[$index]) ? trim($quantity[$index]) : NULL;
      if ($currentOrderName === '') {
        continue;
      };
      $randRequest = rand(100000, time());
      event(new NewRequestCreated(1, Auth::id(), 'requests'));
      $checkSupplement = Supplement::where("name", $currentOrderName)->first();
      $checkSystem = System::where("name", $currentOrderName)->first();
      $client = Client::where("code", Cookie::get('login_client'))->first();
      $payday = Carbon::now()->format('l');
      $customerRequests = new CustomerRequests();
      if ($client) {
        $customerRequests->code = $randRequest;
        $customerRequests->code_client = $client->code;
      } else if (Cookie::has('code_request_client')) {
        $customerRequests->code = Cookie::get('code_request_client');
      } else {
        Cookie::queue(Cookie::forever('code_request_client', $randRequest));
        $customerRequests->code = $randRequest;
      };
      $customerRequests->code_order = $c;
      $customerRequests->fname = $fname;
      $customerRequests->lname = $lname;
      if ($email) {
        $customerRequests->email = $email;
      };
      $customerRequests->quantity = $currentQuantity;
      $customerRequests->phone = $phone;
      $customerRequests->type = $currentType;
      $customerRequests->state = "request";
      if ($checkSupplement) {
        $customerRequests->supplement = $currentOrderName;
        $customerRequests->amount = $checkSupplement->amount;
      } elseif ($checkSystem) {
        $customerRequests->system = $currentOrderName;
        $customerRequests->amount = $checkSystem->amount;
      };
      $customerRequests->paid = 0;
      $customerRequests->save();
    };
    return back();
  }
  public function deleteCustomerRequests(DeleteCustomerRequestsRequest $request) {
    $code = $request->input("code");
    $customerRequests = CustomerRequests::where("code", $code)->first();

    if (!$customerRequests) {
      return back()->withErrors(['error' => __('messages.request-not-found')]);
    }

    // Ownership is enforced server-side: the current authenticated client or
    // the session-bound anonymous basket must own the request being deleted.
    $client = Auth::guard('client')->user();
    $ownsRequest = $client && $customerRequests->code_client === $client->code;
    $ownsBasket = !$client && Cookie::get('code_request_client') === $code;
    if (!$ownsRequest && !$ownsBasket) {
      abort(403);
    }

    $customerRequests->delete();
    Cookie::queue(Cookie::forget('code_request_client'));
    return back();
  }
}
