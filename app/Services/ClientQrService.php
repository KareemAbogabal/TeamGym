<?php

namespace App\Services;

use App\Models\Coach\ClientQrCode;
use App\Models\Coach\QrScanLog;
use App\Models\Front\Client;
use App\Enums\QrPurpose;
use App\Enums\QrStatus;
use App\Support\Ean13;

/**
 * Issues, verifies and revokes client QR identity tokens.
 *
 * Security model:
 *  - Only the raw token ever leaves the server (encoded in the QR image).
 *  - The DB stores only a SHA-256 hash of the token, so a leaked table
 *    cannot be used to forge valid QR codes.
 *  - Each token is scoped to an explicit purpose; an endpoint never
 *    accepts a token issued for a different purpose.
 *  - Expired / revoked tokens are rejected and every scan is logged.
 */
class ClientQrService {
  /**
   * Issue a new active token for a client + purpose.
   * Returns the raw token (for the QR image) — the DB keeps only its hash.
   */
  public function issue(Client $client, QrPurpose $purpose, ?string $expiresAt = null, ?string $createdBy = null): string {
    $raw = ClientQrCode::generateRawToken();

    ClientQrCode::create([
      'code_client' => $client->code,
      'token_hash' => ClientQrCode::hashToken($raw),
      'token_version' => 1,
      'purpose' => $purpose->value,
      'status' => QrStatus::Active->value,
      'expires_at' => $expiresAt,
      'created_by' => $createdBy,
    ]);

    return $raw;
  }

  /**
   * Ensure a client has an identity QR record, issuing a fresh active one
   * when none exists yet. Used on client creation and for backfilling
   * existing clients so that every client has a scannable QR.
   */
  public function ensureForClient(Client $client, ?string $createdBy = null): ClientQrCode {
    $existing = ClientQrCode::where('code_client', $client->code)
      ->where('purpose', QrPurpose::ClientIdentity->value)
      ->orderByDesc('created_at')
      ->first();

    if ($existing && $existing->status === QrStatus::Active->value) {
      return $existing;
    }

    $this->issue($client, QrPurpose::ClientIdentity, null, $createdBy ?? $client->code);

    return ClientQrCode::where('code_client', $client->code)
      ->where('purpose', QrPurpose::ClientIdentity->value)
      ->orderByDesc('created_at')
      ->first();
  }

  /**
   * Verify a raw token against a given purpose. On success returns the
   * client, otherwise throws a descriptive InvalidArgumentException.
   * Every attempt is logged (success or failure).
   */
  public function verify(string $rawToken, QrPurpose $purpose, ?string $source = null, ?string $ip = null, ?string $userAgent = null, ?string $authenticatedUser = null): Client {
    $qr = ClientQrCode::where('token_hash', ClientQrCode::hashToken($rawToken))->first();

    if (!$qr) {
      $this->log(null, null, false, 'unknown_token', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('Invalid or unknown QR code.');
    }

    if ($qr->purpose !== $purpose->value) {
      $this->log($qr->id, $qr->code_client, false, 'wrong_purpose', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('This QR code is not valid for this action.');
    }

    if ($qr->status !== QrStatus::Active->value) {
      $this->log($qr->id, $qr->code_client, false, 'not_active', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('This QR code is no longer active.');
    }

    if ($qr->expires_at !== null && $qr->expires_at->isPast()) {
      $qr->status = QrStatus::Expired->value;
      $qr->save();
      $this->log($qr->id, $qr->code_client, false, 'expired', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('This QR code has expired.');
    }

    $qr->scan_count = ($qr->scan_count ?? 0) + 1;
    $qr->last_scanned_at = now();
    $qr->save();

    $client = $qr->client;
    $this->log($qr->id, $client->code, true, 'ok', $source, $ip, $userAgent, $authenticatedUser);

    return $client;
  }

  /**
   * Revoke an active token. Throws if unknown.
   */
  public function revoke(ClientQrCode $qr): ClientQrCode {
    if ($qr->status !== QrStatus::Active->value) {
      throw new \RuntimeException('Only active QR codes can be revoked.');
    }
    $qr->status = QrStatus::Revoked->value;
    $qr->revoked_at = now();
    $qr->save();
    return $qr;
  }

  /**
   * Ensure a client has exactly one active ATTENDANCE barcode (EAN-13).
   * Issued once on client creation and on backfill. Returns the active row.
   *
   * The EAN-13 value is a public membership-style identifier for gym
   * attendance only. It is stored in plaintext (unlike login QR tokens,
   * which are stored as hashes) because it must be machine-readable and is
   * NOT a credential.
   */
  public function ensureAttendanceBarcode(Client $client, ?string $createdBy = null): ClientQrCode {
    $existing = $this->activeAttendanceRow($client);
    if ($existing) {
      return $existing;
    }

    $barcode = $this->uniqueBarcode();
    $row = ClientQrCode::create([
      'code_client' => $client->code,
      'barcode' => $barcode,
      'token_hash' => null,
      'token_version' => 1,
      'purpose' => QrPurpose::Attendance->value,
      'status' => QrStatus::Active->value,
      'created_by' => $createdBy ?? $client->code,
    ]);

    return $row;
  }

  /**
   * Regenerate a client's attendance barcode: revoke the current active
   * barcode and issue a fresh active one with a new unique EAN-13 value.
   * The old barcode stops working immediately.
   */
  public function regenerateAttendanceBarcode(Client $client, ?string $createdBy = null): ClientQrCode {
    $existing = $this->activeAttendanceRow($client);
    if ($existing) {
      $existing->status = QrStatus::Revoked->value;
      $existing->revoked_at = now();
      $existing->save();
    }

    $barcode = $this->uniqueBarcode();
    return ClientQrCode::create([
      'code_client' => $client->code,
      'barcode' => $barcode,
      'token_hash' => null,
      'token_version' => 1,
      'purpose' => QrPurpose::Attendance->value,
      'status' => QrStatus::Active->value,
      'created_by' => $createdBy ?? $client->code,
    ]);
  }

  /**
   * Verify a scanned EAN-13 attendance barcode and resolve the client.
   * Validates format + check digit, confirms the row is the active ATTENDANCE
   * purpose (never a login token), increments scan stats and logs the event.
   */
  public function verifyBarcode(string $barcode, ?string $source = null, ?string $ip = null, ?string $userAgent = null, ?string $authenticatedUser = null): Client {
    $barcode = trim($barcode);

    if (!Ean13::isValid($barcode)) {
      $this->log(null, null, false, 'invalid_ean13', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('Invalid EAN-13 barcode.');
    }

    $row = ClientQrCode::where('barcode', $barcode)->first();

    if (!$row) {
      $this->log(null, null, false, 'unknown_barcode', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('Unknown attendance barcode.');
    }

    if ($row->purpose !== QrPurpose::Attendance->value) {
      $this->log($row->id, $row->code_client, false, 'wrong_purpose', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('Invalid attendance barcode.');
    }

    if ($row->status !== QrStatus::Active->value) {
      $this->log($row->id, $row->code_client, false, 'not_active', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('Barcode is not active.');
    }

    if ($row->expires_at !== null && $row->expires_at->isPast()) {
      $row->status = QrStatus::Expired->value;
      $row->save();
      $this->log($row->id, $row->code_client, false, 'expired', $source, $ip, $userAgent, $authenticatedUser);
      throw new \InvalidArgumentException('Barcode has expired.');
    }

    $row->scan_count = ($row->scan_count ?? 0) + 1;
    $row->last_scanned_at = now();
    $row->save();

    $client = $row->client;
    $this->log($row->id, $client->code, true, 'ok', $source, $ip, $userAgent, $authenticatedUser);

    return $client;
  }

  /** Latest active ATTENDANCE barcode row for a client, if any. */
  public function activeAttendanceRow(Client $client): ?ClientQrCode {
    return ClientQrCode::where('code_client', $client->code)
      ->where('purpose', QrPurpose::Attendance->value)
      ->where('status', QrStatus::Active->value)
      ->orderByDesc('id')
      ->first();
  }

  protected function uniqueBarcode(): string {
    do {
      $barcode = Ean13::generate();
    } while (ClientQrCode::where('barcode', $barcode)->exists());
    return $barcode;
  }

  protected function log(?int $qrId, ?string $codeClient, bool $success, string $reason, ?string $source, ?string $ip, ?string $userAgent, ?string $authenticatedUser): void {
    QrScanLog::create([
      'qr_code_id' => $qrId,
      'code_client' => $codeClient,
      'scanned_at' => now(),
      'source' => $source,
      'ip' => $ip,
      'user_agent' => $userAgent,
      'authenticated_user' => $authenticatedUser,
      'success' => $success,
      'reason' => $reason,
    ]);
  }
}
