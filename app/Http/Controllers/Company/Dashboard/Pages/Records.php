<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Back\Record;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Supplement;
use App\Models\Back\System;
use App\Models\Back\Snacks;
use App\Models\Back\Payment;
use App\Models\Back\History;
use App\Models\Back\SettingCompany;
use App\Models\Back\Lineage;
use App\Models\Back\Imports;
use App\Events\NewRequestCreated;
use App\Traits\Payments;
use App\Traits\Warning;
use App\Traits\Notifications;
use Pusher\Pusher;
use Carbon\Carbon;

class Records extends Controller {
  use Warning, Payments, Notifications;
  public function index(Request $request) {
    $record = Record::with(['client', 'employee'])->get();
    $clients = Client::with(['payment.registries'])->get();
    $supplements = Supplement::whereHas('imports', function ($q) {
      $q->where('quantity', '>', 0);
    })->with(['imports' => function ($q) {
      $q->where('quantity', '>', 0);
    }])->get();
    $systems = System::whereNot("defult", "true")->get();
    $systemDefult = System::where("defult", "true")->first();
    $snacks = Snacks::with([
      'imports' => function ($q) {
        $q->where('quantity', '!=', 0);
      }
    ])->get();
    $settingCompany = SettingCompany::find(1);
    return view('Company.Dashboard.Pages.records', compact('record', 'supplements', 'systems', 'settingCompany', 'snacks', 'systemDefult', 'clients'));
  }
  public function getPaymentCustomer(Request $request) {
    $data = $request->validate([
      'code' => ['required', 'string'],
    ]);
    $code = $request->input("code");
    $client = Client::where('code', $code)->with(['payment.registries'])->first();
    if (!$client) {
      return response()->json(['check' => 'no'], 200);
    };
    return response()->json($client, 200);
  }
  public function searchClient(Request $request) {
    $data = $request->validate([
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
    ]);
    $fname = mb_strtolower(trim($data['fname']));
    $lname = mb_strtolower(trim($data['lname']));
    $client = Client::whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->first();
    if (!$client) {
      return response()->json(['check' => 'no'], 200);
    };
    return response()->json($client, 200);
  }
  public function record(Request $request) {
    $request->validate([
      'code_client' => ['required', 'string'],
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
    ]);
    // event(new NewRequestCreated(1, Auth::id()));
    $rand = rand(100000, time());
    $employee = Auth::guard('employee')->user();
    $record = new Record();
    $history = new History();
    $code_client = $request->input("code_client");
    $fname = $request->input("fname");
    $lname = $request->input("lname");
    if ($employee) {
      $record->record($code_client, "$fname $lname", "entrance", "entrance", null, $employee->code, "$employee->fname $employee->lname", $employee->phone, $employee->job_role);
      $history->recordHistory("$fname $lname", $code_client, $employee->code, "entrance", null, null, "$employee->fname $employee->lname");
    } else {
      return back()->withErrors(['error' => 'Employee is not present']);
    };
    notifySuccess(__('messages.updated-successfully'));
    return back();
  }
  public function recordExit(Request $request) {
    $request->validate([
      'code' => ['required', 'string'],
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
      'order_name' => ['nullable', 'string'],
      'attachment' => ['nullable', 'string'],
      'amount' => ['nullable', 'string'],
    ]);
    $code = $request->input("code");
    $amount = $request->input("amount");
    $employee = Auth::guard('employee')->user();
    $client = Client::where("code", $code)->first();
    $record = new Record();
    $fname = $request->input("fname");
    $lname = $request->input("lname");
    if ($request->input("order_name") && $request->input("attachment") == null) {
      $attachment = $request->input("order_name");
    } else {
      $attachment = $request->input("attachment");
    };
    $payments = Payment::where("code_client", $code)->where("code_snacks", $attachment)->where(function($q) {
      $q->whereColumn('amount', '>', 'paid')
        ->orWhere('payday', 'daily');
    })->first();
    $snacks = Snacks::where("code", $attachment)->first();
    $nowCairo = Carbon::now('Africa/Cairo');
    $start = $nowCairo->copy()->subHours(3)->setTimezone('UTC');
    $end = $nowCairo->copy()->setTimezone('UTC');
    // $startExit = $nowCairo->copy()->subMinutes(10)->setTimezone('UTC');
    $startExit = $nowCairo->copy()->subHours(20)->setTimezone('UTC');
    $endExit = $nowCairo->copy()->setTimezone('UTC');
    $recordCheckEntrance = Record::where("code_client", $client->code)->where("state", "entrance")->whereBetween('created_at', [$start, $end])->latest('created_at')->first();
    $recordCheckExit = Record::where("code_client", $client->code)->where("state", "exit")->whereBetween('created_at', [$startExit, $endExit])->latest('created_at')->first();
    if ($recordCheckExit) {
      $recordCheckExit->delete();
    };
    if ($snacks) {
      if (!$payments) {
        $requestsPayment = new RequestsPayment();
        $requestsPayment->addRequest($code, "snacks", $attachment, $snacks->code, $snacks->amount, "daily", $employee->code);
        $requestsPayment->state = "acceptance";
        $requestsPayment->save();
        $month = strtolower(Carbon::now('Africa/Cairo')->format('F'));
        if ($snacks) {
          $rand = rand(100000, time());
          $payment = new Payment();
          $payment->code = $rand;
          $payment->code_client = $code;
          $payment->code_employee = $employee->code;
          $payment->code_snacks = $snacks->code;
          $payment->order_name = $snacks->name;
          Lineage::addLineage(null, null, null, null, $snacks->code, "snacks", 1);
          $payment->code_request_payment = $requestsPayment->code;
          $payment->type = "snacks";
          $payment->amount = $snacks->amount;
          $payment->paid = 0;
          $payment->payday = "daily";
          $payment->paymonth = $month;
          $payment->save();
          Imports::updateQuantit(null, $snacks->code);
        };
      };
    };
    if ($request->input("attachment_supplement") != null) {
      $supplement = Payment::where("code", $request->input("attachment_supplement_code"))->first();
      if ($request->input("attachment_system") != null) {
        $systems = System::where("name", $client->category)->first();
      };
      $snacks = Snacks::where("code", $attachment)->first();
      if ($supplement != null && $systems == null && $snacks != null) {
        $amountSnacks = abs($snacks->amount);
        $amountSystem = null;
        $amountSupplement = abs($amount - $amountSnacks);
        $this->registration($code, $request->input("attachment_supplement_code"), $amountSupplement);
        $this->registration($code, $attachment, $amountSnacks);
      } else if ($supplement != null && $systems != null && $snacks != null) {
        $amountSnacks = null;
        $supplementPayment = Payment::where("code", $request->input("attachment_supplement_code"))->first();
        $systemPayment = Payment::where("code_client", $client->code)->where("type", "system")->first();
        $snacksItem = Snacks::where("code", $attachment)->first();
        if (!empty($amount)) {
          $amount = intval($amount);
          $systemDue = 0;
          if ($systemPayment) {
            $systemDue = max(0, intval(abs($systemPayment->amount)) - intval(abs($systemPayment->paid ?? 0)));
          };
          $snacksPrice = $snacksItem ? intval(abs($snacksItem->amount)) : 0;
          $supplementDue = 0;
          if ($supplementPayment) {
            $supplementDue = max(0, intval(abs($supplementPayment->amount)) - intval(abs($supplementPayment->paid ?? 0)));
          };
          $totalNeeded = $systemDue + $snacksPrice + $supplementDue;
          $allocSystem = 0;
          $allocSnacks = 0;
          $allocSupplement = 0;
          if ($amount >= $totalNeeded) {
            $allocSystem = $systemDue;
            $allocSnacks = $snacksPrice;
            $allocSupplement = $supplementDue;
          } else {
            $allocSnacks = min($snacksPrice, $amount);
            $remaining = $amount - $allocSnacks;
            if ($remaining > 0) {
              $half = intdiv($remaining, 2);
              $allocSystem = $half;
              $allocSupplement = $remaining - $half;
            } else {
              $allocSystem = 0;
              $allocSupplement = 0;
            };
          };
          $amountSystem = $allocSystem;
          $amountSnacks = $allocSnacks;
          $amountSupplement = $allocSupplement;
        } else {
          $amountSystem = intval(abs($systems->amount));
          $amountSupplement = null;
          $amountSnacks = $snacks ? intval(abs($snacks->amount)) : null;
        };
        if (!empty($amountSystem) && $amountSystem > 0) {
          $this->registration($code, $systemPayment ? $systemPayment->code : $systems->code, $amountSystem);
          if ($systemPayment) {
            $systemPayment->paid = intval($systemPayment->paid ?? 0) + intval($amountSystem);
            $systemPayment->save();
          };
        };
        if (!empty($amountSnacks) && $amountSnacks > 0) {
          $this->registration($code, $attachment, $amountSnacks);
        };
        if (!empty($amountSupplement) && $amountSupplement > 0) {
          $this->registration($code, $request->input("attachment_supplement_code"), $amountSupplement);
          if ($supplementPayment) {
            $supplementPayment->paid = intval($supplementPayment->paid ?? 0) + intval($amountSupplement);
            $supplementPayment->save();
          };
        };
      } else if ($supplement != null && $systems != null && $snacks == null) {
        $amountSnacks = null;
        if (!empty($amount)) {
          $amount = $amount;
          $systemPrice = $systems->amount;
          if ($amount < $systemPrice) {
            $half = $amount / 2;
            $amountSystem = $half;
            $amountSupplement = $half;
            $diff = $amount - ($amountSystem + $amountSupplement);
            if ($diff != 0) {
              $amountSupplement += $diff;
            };
          } else {
            $amountSystem = abs($systemPrice);
            $amountSupplement = abs($amount - $systemPrice);
          };
        } else {
          $amountSystem = abs($systems->amount);
          $amountSupplement = null;
        };
        if (!empty($amountSupplement) && $amountSupplement > 0) {
          $this->registration($code, $request->input("attachment_supplement_code"), $amountSupplement);
        }
        if (!empty($amountSystem) && $amountSystem > 0) {
          $this->registration($code, $systems->code, $amountSystem);
        };
      } else {
        $amountSnacks = null;
        $amountSystem = null;
        $amountSupplement = abs($amount);
        $this->registration($code, $request->input("attachment_supplement_code"), $amountSupplement);
      };
    } else if ($request->input("attachment_system") != null) {
      $supplement = Payment::where("code", $request->input("attachment_supplement_code"))->first();
      $systems = System::where("name", $client->category)->first();
      $snacks = Snacks::where("code", $attachment)->first();
      if ($supplement != null && $systems == null && $snacks != null) {
        $amountSnacks = abs($snacks->amount);
        $amountSystem = null;
        $amountSupplement = abs($amount - $amountSnacks);
        $this->registration($code, $request->input("attachment_supplement_code"), $amountSupplement);
        $this->registration($code, $attachment, $amountSnacks);
      } else if ($supplement != null && $systems != null && $snacks != null) {
        $amountSnacks = null;
        $supplementPayment = Payment::where("code", $request->input("attachment_supplement_code"))->first();
        $systemPayment = Payment::where("code_client", $client->code)->where("type", "system")->first();
        $snacksItem = Snacks::where("code", $attachment)->first();
        if (!empty($amount)) {
          $amount = intval($amount);
          $systemDue = 0;
          if ($systemPayment) {
            $systemDue = max(0, intval(abs($systemPayment->amount)) - intval(abs($systemPayment->paid ?? 0)));
          };
          $snacksPrice = $snacksItem ? intval(abs($snacksItem->amount)) : 0;
          $supplementDue = 0;
          if ($supplementPayment) {
            $supplementDue = max(0, intval(abs($supplementPayment->amount)) - intval(abs($supplementPayment->paid ?? 0)));
          };
          $totalNeeded = $systemDue + $snacksPrice + $supplementDue;
          $allocSystem = 0;
          $allocSnacks = 0;
          $allocSupplement = 0;
          if ($amount >= $totalNeeded) {
            $allocSystem = $systemDue;
            $allocSnacks = $snacksPrice;
            $allocSupplement = $supplementDue;
          } else {
            $allocSnacks = min($snacksPrice, $amount);
            $remaining = $amount - $allocSnacks;
            if ($remaining > 0) {
              $half = intdiv($remaining, 2);
              $allocSystem = $half;
              $allocSupplement = $remaining - $half;
            } else {
              $allocSystem = 0;
              $allocSupplement = 0;
            };
          };
          $amountSystem = $allocSystem;
          $amountSnacks = $allocSnacks;
          $amountSupplement = $allocSupplement;
        } else {
          $amountSystem = intval(abs($systems->amount));
          $amountSupplement = null;
          $amountSnacks = $snacks ? intval(abs($snacks->amount)) : null;
        };
        if (!empty($amountSystem) && $amountSystem > 0) {
          $this->registration($code, $systemPayment ? $systemPayment->code : $systems->code, $amountSystem);
          if ($systemPayment) {
            $systemPayment->paid = intval($systemPayment->paid ?? 0) + intval($amountSystem);
            $systemPayment->save();
          };
        };
        if (!empty($amountSnacks) && $amountSnacks > 0) {
          $this->registration($code, $attachment, $amountSnacks);
        };
        if (!empty($amountSupplement) && $amountSupplement > 0) {
          $this->registration($code, $request->input("attachment_supplement_code"), $amountSupplement);
          if ($supplementPayment) {
            $supplementPayment->paid = intval($supplementPayment->paid ?? 0) + intval($amountSupplement);
            $supplementPayment->save();
          };
        };
      } else if ($supplement != null && $systems != null && $snacks == null) {
        $amountSnacks = null;
        if (!empty($amount)) {
          $amount = $amount;
          $systemPrice = $systems->amount;
          if ($amount < $systemPrice) {
            $half = $amount / 2;
            $amountSystem = $half;
            $amountSupplement = $half;
            $diff = $amount - ($amountSystem + $amountSupplement);
            if ($diff != 0) {
              $amountSupplement += $diff;
            };
          } else {
            $amountSystem = abs($systemPrice);
            $amountSupplement = abs($amount - $systemPrice);
          };
        } else {
          $amountSystem = abs($systems->amount);
          $amountSupplement = null;
        };
        if (!empty($amountSupplement) && $amountSupplement > 0) {
          $this->registration($code, $request->input("attachment_supplement_code"), $amountSupplement);
        }
        if (!empty($amountSystem) && $amountSystem > 0) {
          $this->registration($code, $systems->code, $amountSystem);
        };
      } else if ($supplement == null && $systems != null && $snacks != null) {
        $amountSnacks = abs($snacks->amount);
        $amountSystem = abs($amount - $amountSnacks);
        $amountSupplement = null;
        $this->registration($code, $attachment, $amountSnacks);
        $this->registration($code, $systems->code, $amountSystem);
      } else {
        $amountSnacks = null;
        if (!empty($request->input("amount"))) {
          $amountSystem = abs($amount);
        } else {
          $amountSystem = abs($systems->amount);
        };
        $amountSupplement = null;
        $this->registration($code, $systems->code, $amountSystem);
      };
    } else {
      $supplement = null;
      $systems = null;
      $snacks = Snacks::where("code", $attachment)->first();
      $amountSnacks = abs($amount);
      $amountSystem = null;
      $amountSupplement = null;
      $this->registration($code, $attachment, $amountSnacks);
    };
    $record->record($code, "$fname $lname", "exit", $amount ? $amount : ($systems && empty($request->input("amount")) ? abs($systems->amount) : "exit"), $attachment ? $attachment : null, $employee->code, "$employee->fname $employee->lname", $employee->phone, $employee->job_role, false);
    $history = new History();
    $history->recordHistory("$fname $lname", $code, $employee->code, "exit", $amount ? $amount : ($systems && empty($request->input("amount")) ? abs($systems->amount) : "exit"), $attachment ? $attachment : null, null, "$employee->fname $employee->lname");
    notifySuccess(__('messages.updated-successfully'));
    return back();
  }
  public function autoRecord(Request $request) {
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $systemDefult = System::where("defult", "true")->first();
    $employee = Employee::find(1);
    if (!$client) {
      return redirect()->route("aboutUs");
    };
    event(new NewRequestCreated(1, Auth::id(), "records"));
    $nowCairo = Carbon::now('Africa/Cairo');
    $lockTimeStart = $nowCairo->copy()->subHours(2)->setTimezone('UTC');
    $lockTimeEnd = $nowCairo->copy()->subMinutes(1)->setTimezone('UTC');
    $start = $nowCairo->copy()->subHours(5)->setTimezone('UTC');
    $end = $nowCairo->copy()->setTimezone('UTC');
    $lockRecordEntrance = Record::where("code_client", $client->code)->where("state", "entrance")->whereBetween('created_at', [$lockTimeStart, $lockTimeEnd])->latest('created_at')->first();
    $recordCheckEntrance = Record::where("code_client", $client->code)->where("state", "entrance")->whereBetween('created_at', [$start, $end])->latest('created_at')->first();
    $recordCheckExit = Record::where("code_client", $client->code)->latest('created_at')->first();
    if ($lockRecordEntrance && $recordCheckExit->state == "exit") {
      return redirect()->route("dashboard")->withErrors(['error' => __('messages.lock-entrance')]);
    };
    if ($recordCheckEntrance && $recordCheckExit->state !== "exit") {
      if ($client->category == $systemDefult->name) {
        $record = new Record();
        $record->record($client->code, "{$client->fname} {$client->lname}", "exit", $systemDefult->amount, $systemDefult->code, $employee->code, "{$employee->fname} {$employee->lname}", $employee->phone, $employee->job_role);
        $history = new History();
        $history->recordHistory("{$client->fname} {$client->lname}", $client->code, $employee->code, "exit", $systemDefult->amount, $systemDefult->code, "$employee->fname $employee->lname");
      } else {
        $record = new Record();
        $record->record($client->code, "{$client->fname} {$client->lname}", "exit", "exit", null, $employee->code, "{$employee->fname} {$employee->lname}", $employee->phone, $employee->job_role);
        $history = new History();
        $history->recordHistory("{$client->fname} {$client->lname}", $client->code, $employee->code, "exit", "exit", null, "$employee->fname $employee->lname");
      };
    } else {
      $record = new Record();
      $record->record($client->code, "{$client->fname} {$client->lname}", "entrance", "entrance", null, $employee->code, "{$employee->fname} {$employee->lname}", $employee->phone, $employee->job_role);
      $history = new History();
      $history->recordHistory("{$client->fname} {$client->lname}", $client->code, $employee->code, "entrance", null, null, "$employee->fname $employee->lname");
    };
    return redirect()->route("schedule");
  }
  public function addRequests(Request $request) {
    $request->validate([
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
      'order_name' => ['required', 'string'],
      'attachment' => ['required', 'string'],
      'amount' => ['required', 'string'],
    ]);
    $fname = mb_strtolower(trim($request->input("fname")));
    $lname = mb_strtolower(trim($request->input("lname")));
    $client = Client::whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->first();
    $employee = Auth::guard('employee')->user();
    $payday = Carbon::now()->format('l');
    $requestsPayment = new RequestsPayment();
    $requestsPayment->addRequest($client->code, $request->input("order_name"), $request->input("attachment"), null, $request->input("amount"), $payday, $employee->code);
    $history = new History();
    $history->recordHistory("$client->fname $client->lname", $client->code, null, "request", null, $request->input("order_name"), "$employee->fname $employee->lname");
    if ($request->has("remember")) {
      $name = "Payment request has been sent";
      $type = "RequestsPayment";
      $description = "Hello, the request has been sent [ {$request->input('order_name')} ] and is awaiting approval. Have a nice day.";
      $this->makeNotification($name, $type, $description, $client->code, null, "iconPayment");
    };
    notifySuccess(__('messages.saved-successfully'));
    return back();
  }
  public function registrationRequestsPayment(Request $request) {
    $data = $request->validate([
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
      'attachment' => ['required', 'string'],
      'amount' => ['required', 'string'],
    ]);
    $employee = Auth::guard('employee')->user();
    $fname = mb_strtolower(trim($data['fname']));
    $lname = mb_strtolower(trim($data['lname']));
    $attachment = trim($request->input('attachment', ''));
    $client = Client::whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->first();
    $query = Payment::where('code_client', $client->code);
    if (ctype_digit($attachment)) {
      $query->where(function($q) use ($attachment) {
        $q->where('code', $attachment)
          ->orWhere('code_supplements', $attachment)
          ->orWhere('code_systems', $attachment)
          ->orWhere('code_snacks', $attachment);
      });
    } else {
      $query->where('type', $attachment);
    };
    $this->registration($client->code, $request->input("attachment"), $request->input("amount"));
    $history = new History();
    $history->recordHistory("$client->fname $client->lname", $client->code, null, "paid", $request->input("amount"), $attachment, "$employee->fname $employee->lname");
    $paymentsClient = $query->first();
    if ($request->has("remember")) {
      $name = "You have a receipt for the payment collected.";
      $type = "Payment";
      $description = "Hello, you have paid [ {$request->input('amount')} ] for product [ {$paymentsClient->name} ] Now you are achieving your goals step by step. Have a nice day.";
      $this->makeNotification($name, $type, $description, $client->code, null, "iconPayment");
    };
    notifySuccess(__('messages.saved-successfully'));
    return back();
  }
  public function getSupplementClient(Request $request) {
    $data = $request->validate([
      'fname' => ['required', 'string'],
      'lname' => ['required', 'string'],
    ]);
    $fname = mb_strtolower(trim($data['fname']));
    $lname = mb_strtolower(trim($data['lname']));
    $client = Client::whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->first();
    if (!$client) {
      return response()->json(['check' => 'no_client'], 200);
    };
    $paymentsClient = Payment::where('code_client', $client->code)->where("type", "supplement")->whereColumn('amount', '>', 'paid')->with(["supplement"])->get();
    if (!$paymentsClient) {
      return response()->json(['check' => 'no_payment'], 200);
    };
    if ($paymentsClient) {
      return response()->json([$paymentsClient], 200);
    };
    return response()->json(['check' => 'no'], 200);
  }
  public function signUp(Request $request) {
    $request->validate([
      "lname" => ["required", "string", "max:7"],
      "fname" => ["required", "string", "max:7"],
      "phone" => ["required", "numeric", "digits:11"],
      "email" => ["required", "email"],
      "password" => ["required", "min:5"],
    ]);
    $system = System::where("defult", "true")->first();
    $fname = $request->input('fname');
    $lname = $request->input('lname');
    $phone = $request->input('phone');
    if ($request->has('email')) {
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
    $employee = Auth::guard('employee')->user();
    $signUp = new Client();
    $signUp->code = $rand;
    $signUp->fname = $fname;
    $signUp->lname = $lname;
    $signUp->phone = $phone;
    $signUp->email = $email;
    $signUp->category = $system->name;
    $signUp->password = bcrypt($password);
    $signUp->save();
    $settings = new SettingClient();
    $settings->code_client = $rand;
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
      $payment->type = $system->name;
      $payment->amount = $system->amount;
      $payment->paid = 0;
      $payment->payday = "daily";
      $payment->paymonth = $month;
      $payment->save();
    };
    date_default_timezone_set("Africa/Cairo");
    $d = date_create();
    $time = date_format($d, "Y-m-j_g-i_A");
    $data = ["userName" => "$signUp->fname $signUp->lname", 'name' => "$signUp->fname", 'code' => "$signUp->code", 'time' => "$time", "phone" => "$signUp->phone", 'password' => "$password"];
    Mail::send('Mail.pageMail', $data, function ($message) use ($email) {
      $message->embed(public_path('images/header/Team-Gym.png'));
      $message->to($email)->subject('Sign up in Team Gym');
    });
    notifySuccess(__('messages.saved-successfully'));
    return back();
  }
}
