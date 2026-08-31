<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Front\Client;
use App\Models\Coach\ClientQrCode;
use App\Services\ClientQrService;
use App\Enums\QrPurpose;

class ClientQr extends Controller {
  public function __construct(private ClientQrService $qrService) {
  }

  /**
   * Client portal page: shows the client's active QR identity code and lets
   * them rotate (reissue) it.
   */
  public function index(Request $request) {
    $client = Auth::guard('client')->user();
    if (!$client) {
      return redirect()->route('front');
    }

    $active = ClientQrCode::where('code_client', $client->code)
      ->where('purpose', QrPurpose::ClientIdentity->value)
      ->where('status', 'active')
      ->latest('created_at')
      ->first();

    if (!$active) {
      $raw = $this->qrService->issue($client, QrPurpose::ClientIdentity, null, $client->code);
      session(['qr_raw_token' => $raw]);
    } else {
      $raw = session('qr_raw_token');
    }

    return view('Website.Dashboard.Pages.client_qr', compact('client', 'active', 'raw'));
  }

  /**
   * Rotate the identity token (revoke the previous one, issue a new one).
   */
  public function rotate(Request $request) {
    $client = Auth::guard('client')->user();
    if (!$client) {
      return redirect()->route('front');
    }

    $current = ClientQrCode::where('code_client', $client->code)
      ->where('purpose', QrPurpose::ClientIdentity->value)
      ->where('status', 'active')
      ->latest('created_at')
      ->get();

    foreach ($current as $qr) {
      try {
        $this->qrService->revoke($qr);
      } catch (\Throwable $e) {
        // ignore already-inactive rows
      }
    }

    $raw = $this->qrService->issue($client, QrPurpose::ClientIdentity, null, $client->code);
    session(['qr_raw_token' => $raw]);

    notifySuccess(__('messages.qr-rotated'));
    return back();
  }
}
