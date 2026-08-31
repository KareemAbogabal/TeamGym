<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requesters\Company\Dashboard\UpdateEmployee\UpdateEmployeeRequest;
use App\Http\Requesters\Company\Dashboard\UpdateClient\UpdateClientRequest;
use App\Http\Requesters\Company\Dashboard\DestroyUser\DestroyUserRequest;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\LineageInBody;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Back\RequestsPayment;
use App\Models\Back\Payment;
use App\Models\Back\PaymentRegistry;
use App\Models\Back\Activity;
use App\Models\Back\Record;
use App\Models\Coach\ClientQrCode;
use App\Enums\QrPurpose;
use App\Enums\QrStatus;
use App\Services\ClientQrService;
use App\Traits\GetLineage;

class Users extends Controller {
  use GetLineage;

  public function __construct(private ClientQrService $qrService) {
  }

  public function index(Request $request) {
    $employees = Employee::whereNot("id", 1)->get();
    $months = [
      'january','february','march','april','may','june',
      'july','august','september','october','november','december'
    ];
    $clients = Client::with("lineageInBodies")->get();
    $metrics = [
      'weight','BMI','PBF','SMM','fat_mass','protein', 'water', 'kcal',
      'left_arm_lean','right_arm_lean','left_leg_lean','right_leg_lean',
      'left_arm_fat','right_arm_fat','left_leg_fat','right_leg_fat'
    ];
    $dataLineage = [];
    $dataValues = [];
    foreach ($clients as $c) {
      foreach ($metrics as $metric) {
        $row = LineageInBody::where('code', $c->code)->where('name', $metric)->orderByDesc('created_at')->first();
        $value = 0;
        if ($row) {
          $dataLineage[$c->code]['SMM'] = $this->getArray(LineageInBody::class, "SMM", false);
          $dataLineage[$c->code]['fat_mass'] = $this->getArray(LineageInBody::class, "fat_mass", false);
          foreach (array_reverse($months) as $m) {
            if (!is_null($row->{$m}) && $row->{$m} !== 0 && $row->{$m} !== '0') {
              $value = $row->{$m};
              break;
            };
          };
        } else {
          $currentIndex = array_search(date('Y-m'), $months, true);
          if ($currentIndex === false) {
            $currentIndex = null;
            foreach ($months as $i => $m) {
              if (strpos((string)$m, date('Y')) !== false) { $currentIndex = $i; break; };
            };
          };
          if ($currentIndex === null) $currentIndex = count($months) - 1;
          $smmArr = $fatArr = [];
          for ($i = 0; $i < count($months); $i++) {
            if ($i <= $currentIndex) {
              $smmArr[] = 0;
              $fatArr[] = 0;
            } else {
              $smmArr[] = null;
              $fatArr[] = null;
            };
          };
          $dataLineage[$c->code]['SMM'] = $smmArr;
          $dataLineage[$c->code]['fat_mass'] = $fatArr;
        };
        $dataValues[$c->code][$metric] = $value;
      };
    };
    return view('Company.Dashboard.Pages.users', compact("employees", "clients", "dataLineage", "dataValues"));
  }
  public function getAllDataClient(Request $request) {
    $code = $request->input("code");
    $requestsPayment = RequestsPayment::where("code_client", $code)->get();
    $payment = Payment::where("code_client", $code)->with(["registries"])->get();
    $paymentRegistry = Payment::where("code_client", $code)->with(["registries"])->get();
    $activity = Activity::where("code_client", $code)->get();
    $record = Record::where("code_client", $code)->get();
    $qr = ClientQrCode::where("code_client", $code)
      ->where("purpose", QrPurpose::ClientIdentity->value)
      ->orderByDesc("created_at")
      ->first();
    $barcode = $this->qrService->activeAttendanceRow(Client::where("code", $code)->first());
    $data = [
      "Requests Payment" => $requestsPayment,
      "Payment" => $payment,
      "Payment Registry" => $paymentRegistry,
      "Activity" => $activity,
      "Record" => $record,
      "QR" => $qr ? [
        "status" => $qr->status,
        "created_at" => $qr->created_at?->toDateTimeString(),
        "last_scanned_at" => $qr->last_scanned_at?->toDateTimeString(),
        "scan_count" => $qr->scan_count ?? 0,
        "expires_at" => $qr->expires_at?->toDateTimeString(),
      ] : null,
      "Barcode" => $barcode ? [
        "barcode" => $barcode->barcode,
        "status" => $barcode->status,
        "created_at" => $barcode->created_at?->toDateTimeString(),
        "last_scanned_at" => $barcode->last_scanned_at?->toDateTimeString(),
        "scan_count" => $barcode->scan_count ?? 0,
        "expires_at" => $barcode->expires_at?->toDateTimeString(),
      ] : null,
    ];
    return json_encode($data);
  }

  /**
   * Regenerate a client's EAN-13 attendance barcode (the old active one is
   * revoked first so only ONE active attendance barcode exists per client).
   */
  public function regenerateBarcode(Request $request) {
    $request->validate(['code' => ['required', 'string']]);
    $client = Client::where("code", $request->input("code"))->first();
    if (!$client) {
      return response()->json(['ok' => false, 'message' => __('messages.client-not-found')], 422);
    }
    $row = $this->qrService->regenerateAttendanceBarcode($client, auth('employee')->user()?->code);
    notifySuccess(__('messages.barcode-regenerated'));
    return response()->json([
      'ok' => true,
      'barcode' => $row->barcode,
      'created_at' => $row->created_at?->toDateTimeString(),
    ]);
  }

  /**
   * Revoke a client's active EAN-13 attendance barcode (kept for audit; marked
   * revoked so scanners reject it).
   */
  public function revokeBarcode(Request $request) {
    $request->validate(['code' => ['required', 'string']]);
    $client = Client::where("code", $request->input("code"))->first();
    if (!$client) {
      return response()->json(['ok' => false, 'message' => __('messages.client-not-found')], 422);
    }
    $row = $this->qrService->activeAttendanceRow($client);
    if (!$row) {
      return response()->json(['ok' => false, 'message' => __('messages.no-active-barcode')], 422);
    }
    $row->status = QrStatus::Revoked->value;
    $row->save();
    notifySuccess(__('messages.barcode-revoked'));
    return response()->json(['ok' => true]);
  }

  /**
   * Print a client's active EAN-13 attendance barcode in a minimal,
   * print-friendly layout (labels for scanning the client's gym card).
   */
  public function printBarcode(Request $request, string $code) {
    $client = Client::where("code", $code)->first();
    $row = $client ? $this->qrService->activeAttendanceRow($client) : null;
    if (!$client || !$row) {
      abort(404);
    }
    return view('Company.Dashboard.Pages.print_barcode', ['client' => $client, 'barcode' => $row]);
  }
  public function updateEmployee(UpdateEmployeeRequest $request) {
    $employee = Employee::where("code", $request->input("code-employee"))->first();
    $employee->fname = $request->input("fname-employee");
    $employee->lname = $request->input("lname-employee");
    $employee->email = $request->input("email-employee");
    $employee->phone = $request->input("phone-employee");
    if ($request->filled('password-employee')) {
      $employee->password = bcrypt($request->input('password-employee'));
    };
    $employee->documentation = $request->has("documentation-employee") ? "true" : "false";
    $employee->save();
    notifySuccess(__('messages.updated-successfully'));
    return back();
  }
  public function updateClient(UpdateClientRequest $request) {
    $client = Client::where("code", $request->input("code"))->first();
    $client->fname = $request->input("fname");
    $client->lname = $request->input("lname");
    $client->email = $request->input("email");
    $client->phone = $request->input("phone");
    $client->category = $request->input("category");
    if ($request->filled('password')) {
      $client->password = bcrypt($request->input('password'));
    };
    $client->documentation = $request->has("documentation") ? "true" : "false";
    $client->save();
    notifySuccess(__('messages.updated-successfully'));
    return back();
  }
  public function destroy(DestroyUserRequest $request) {
    $id = $request->input("id");
    $state = $request->input("state");
    if ($state == "employee") {
      $column = Employee::where("id", $id)->whereNot("job_role", "Admin")->first();
    } else {
      $column = Client::where("id", $id)->first();
    };
    $column->delete();
    notifySuccess(__('messages.deleted-successfully'));
    return back();
  }
}
