<?php

namespace App\Http\Controllers\Company\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coach\ClientQrCode;
use App\Models\Coach\AttendanceSession;
use App\Models\Coach\Membership;
use App\Models\Front\Client;
use App\Services\ClientQrService;
use App\Services\AttendanceService;
use App\Enums\QrPurpose;

/**
 * Staff-facing QR / barcode scanner terminal.
 *
 * A client presents their QR identity code or EAN-13 attendance barcode at the
 * gym; reception/coach scans it. QR tokens are verified against a hashed token
 * (purpose-scoped), while EAN-13 barcodes are verified by their value against
 * the attendance purpose. Both attendance entry points route through the same
 * AttendanceService so there is ONE entrance/exit business rule.
 */
class ClientQrScan extends Controller {
  public function __construct(
    private ClientQrService $qrService,
    private AttendanceService $attendance,
  ) {
  }

  /**
   * Render the scanner page.
   */
  public function index(Request $request) {
    return view('Company.Dashboard.Pages.qr_scan');
  }

  /**
   * POST the scanned raw token. Verify and return client info + attendance
   * state so the terminal can confirm a check-in/out.
   */
  public function scan(Request $request) {
    $request->validate([
      'token' => ['required', 'string'],
    ]);

    $source = 'staff_scan';
    try {
      $client = $this->qrService->verify(
        $request->input('token'),
        QrPurpose::ClientIdentity,
        $source,
        $request->ip(),
        $request->userAgent(),
        auth('employee')->user()?->code,
      );

      $openSession = AttendanceSession::where('code_client', $client->code)
        ->where('status', 'open')
        ->latest('entrance_at')
        ->first();

      $activeMembership = Membership::where('code_client', $client->code)
        ->whereIn('status', ['active', 'pending'])
        ->latest('ends_at')
        ->first();

      return response()->json([
        'ok' => true,
        'client' => [
          'code' => $client->code,
          'name' => $client->fname . ' ' . $client->lname,
          'photo' => $client->img,
          'category' => $client->category,
        ],
        'currently_inside' => (bool) $openSession,
        'open_session_id' => $openSession?->id,
        'active_membership' => $activeMembership ? [
          'package' => $activeMembership->package_name,
          'ends_at' => $activeMembership->ends_at?->toDateString(),
          'status' => $activeMembership->status,
        ] : null,
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'ok' => false,
        'message' => $e->getMessage(),
      ], 422);
    }
  }

  /**
   * Check a client in/out using their verified identity token (QR terminal
   * action). Opens or closes an attendance session.
   */
  public function record(Request $request) {
    $request->validate([
      'token' => ['required', 'string'],
    ]);

    try {
      $client = $this->qrService->verify(
        $request->input('token'),
        QrPurpose::ClientIdentity,
        'staff_scan',
        $request->ip(),
        $request->userAgent(),
        auth('employee')->user()?->code,
      );

      $by = auth('employee')->user();
      $result = $this->attendance->toggle($client, $by);

      return response()->json([
        'ok' => true,
        'client' => $client->fname . ' ' . $client->lname,
        'state' => $result['state'],
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
    }
  }

  /**
   * Record check-in/out from a scanned client code (QR encodes the code).
   * The machine scans the QR, the backend reads the customer code and
   * opens/closes the attendance session.
   */
  public function recordByCode(Request $request) {
    $request->validate([
      'code' => ['required', 'string'],
    ]);

    $client = Client::where('code', $request->input('code'))->first();

    if (!$client) {
      return response()->json([
        'ok' => false,
        'message' => __('messages.client-not-found'),
      ], 422);
    }

    $by = auth('employee')->user();
    $result = $this->attendance->toggle($client, $by);

    return response()->json([
      'ok' => true,
      'client' => $client->fname . ' ' . $client->lname,
      'state' => $result['state'],
    ]);
  }

  /**
   * Record check-in/out from a scanned EAN-13 ATTENDANCE barcode.
   *
   * The barcode value is validated (13 digits + EAN-13 check digit), resolved
   * against the client's active ATTENDANCE barcode (never a login token), the
   * client is returned, and AttendanceService toggles entrance/exit. The
   * response is structured so the terminal can display an obvious result.
   */
  public function recordBarcode(Request $request) {
    $request->validate([
      'barcode' => ['required', 'string'],
    ]);

    try {
      $client = $this->qrService->verifyBarcode(
        $request->input('barcode'),
        'barcode_scan',
        $request->ip(),
        $request->userAgent(),
        auth('employee')->user()?->code,
      );

      $by = auth('employee')->user();
      $result = $this->attendance->toggle($client, $by);

      $barcodeRow = $this->qrService->activeAttendanceRow($client);

      return response()->json([
        'ok' => true,
        'client' => [
          'code' => $client->code,
          'name' => $client->fname . ' ' . $client->lname,
        ],
        'barcode' => $barcodeRow?->barcode,
        'state' => $result['state'],
        'at' => $result['at'],
        'message' => $result['state'] === 'entrance'
          ? __('messages.attendance-entrance')
          : __('messages.attendance-exit'),
      ]);
    } catch (\InvalidArgumentException $e) {
      $map = [
        'Invalid EAN-13 barcode.' => __('messages.invalid-ean13'),
        'Unknown attendance barcode.' => __('messages.unknown-attendance-barcode'),
        'Invalid attendance barcode.' => __('messages.invalid-attendance-barcode'),
        'Barcode is not active.' => __('messages.attendance-barcode-not-active'),
        'Barcode has expired.' => __('messages.attendance-barcode-expired'),
      ];
      return response()->json([
        'ok' => false,
        'message' => $map[$e->getMessage()] ?? $e->getMessage(),
      ], 422);
    }
  }
}
