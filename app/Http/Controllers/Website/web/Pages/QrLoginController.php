<?php

namespace App\Http\Controllers\Website\web\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Front\Client;
use App\Services\ClientQrService;
use App\Enums\QrPurpose;

/**
 * Secure QR-based client login.
 *
 * The client (or staff) presents a raw identity token; the QrService verifies
 * it against the client_login purpose, then the client is authenticated into
 * the session-backed client guard. The legacy login_client cookie is also set
 * for backward compatibility with the rest of the app.
 */
class QrLoginController extends Controller {
  public function __construct(private ClientQrService $qrService) {
  }

  public function login(Request $request) {
    $token = $request->input('token');
    if (!$token) {
      return redirect()->route('loginPage');
    }

    try {
      $client = $this->qrService->verify(
        $token,
        QrPurpose::ClientIdentity,
        'qr_login',
        $request->ip(),
        $request->userAgent(),
        null,
      );
    } catch (\InvalidArgumentException $e) {
      return redirect()->route('loginPage')->withErrors(['qr' => $e->getMessage()]);
    }

    Auth::guard('client')->login($client);
    Cookie::queue(Cookie::forever('login_client', $client->code));

    return redirect()->route('dashboard');
  }
}
