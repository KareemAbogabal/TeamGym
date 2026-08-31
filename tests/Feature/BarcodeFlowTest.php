<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Front\Client;
use App\Models\Coach\ClientQrCode;
use App\Models\Coach\AttendanceSession;
use App\Services\ClientQrService;
use App\Services\AttendanceService;
use App\Enums\QrPurpose;
use App\Enums\QrStatus;
use App\Support\Ean13;

class BarcodeFlowTest extends TestCase {
  use RefreshDatabase;

  private Client $client;
  private ClientQrService $service;

  protected function setUp(): void {
    parent::setUp();

    $this->client = new Client();
    $this->client->code = 'C001';
    $this->client->fname = 'John';
    $this->client->lname = 'Doe';
    $this->client->email = 'j@x.com';
    $this->client->phone = '123';
    $this->client->category = 'gold';
    $this->client->password = bcrypt('x');
    $this->client->save();

    $this->service = app(ClientQrService::class);
  }

  private function lastBarcode(): ?ClientQrCode {
    return ClientQrCode::where('code_client', $this->client->code)
      ->where('purpose', QrPurpose::Attendance->value)
      ->latest('id')->first();
  }

  private function activeCount(): int {
    return ClientQrCode::where('code_client', $this->client->code)
      ->where('purpose', QrPurpose::Attendance->value)
      ->where('status', QrStatus::Active->value)
      ->count();
  }

  public function test_ean13_helper_generates_valid_barcodes(): void {
    for ($i = 0; $i < 50; $i++) {
      $b = Ean13::generate();
      $this->assertMatchesRegularExpression('/^\d{13}$/', $b);
      $this->assertTrue(Ean13::isValid($b));
    }
  }

  public function test_ensure_attendance_barcode_creates_valid_ean13_row(): void {
    $row = $this->service->ensureAttendanceBarcode($this->client);

    $this->assertEquals(QrPurpose::Attendance->value, $row->purpose);
    $this->assertEquals(QrStatus::Active->value, $row->status);
    $this->assertTrue(Ean13::isValid($row->barcode));
    $this->assertNull($row->token_hash);
    // exactly one active attendance barcode
    $this->assertEquals(1, ClientQrCode::where('code_client', $this->client->code)
      ->where('purpose', QrPurpose::Attendance->value)
      ->where('status', QrStatus::Active->value)
      ->count());
  }

  public function test_ensure_attendance_barcode_is_idempotent(): void {
    $a = $this->service->ensureAttendanceBarcode($this->client);
    $b = $this->service->ensureAttendanceBarcode($this->client);

    $this->assertEquals($a->id, $b->id);
    $this->assertEquals(1, $this->activeCount());
  }

  public function test_verify_barcode_resolves_client_and_counts_scan(): void {
    $row = $this->service->ensureAttendanceBarcode($this->client);

    $resolved = $this->service->verifyBarcode($row->barcode, 'test', '127.0.0.1', 'UA', 'E1');

    $this->assertEquals('C001', $resolved->code);
    $this->assertEquals(1, $row->fresh()->scan_count);
  }

  public function test_verify_rejects_non_ean13(): void {
    $this->service->ensureAttendanceBarcode($this->client);

    $this->expectException(\InvalidArgumentException::class);
    $this->service->verifyBarcode('1234567890123');
  }

  public function test_verify_rejects_revoked_barcode(): void {
    $row = $this->service->ensureAttendanceBarcode($this->client);
    $row->status = QrStatus::Revoked->value;
    $row->save();

    $this->expectException(\InvalidArgumentException::class);
    $this->service->verifyBarcode($row->barcode);
  }

  public function test_regenerate_revokes_old_and_creates_new_unique(): void {
    $old = $this->service->ensureAttendanceBarcode($this->client);
    $new = $this->service->regenerateAttendanceBarcode($this->client);

    $this->assertNotEquals($old->id, $new->id);
    $this->assertNotEquals($old->barcode, $new->barcode);
    $this->assertEquals(QrStatus::Revoked->value, $old->fresh()->status);
    $this->assertEquals(QrStatus::Active->value, $new->status);
    $this->assertEquals(1, $this->activeCount());
  }

  public function test_attendance_toggle_opens_then_closes_session(): void {
    $attendance = app(AttendanceService::class);

    $in = $attendance->toggle($this->client);
    $this->assertEquals('entrance', $in['state']);

    $out = $attendance->toggle($this->client);
    $this->assertEquals('exit', $out['state']);

    $this->assertEquals(0, AttendanceSession::where('code_client', $this->client->code)
      ->where('status', 'open')->count());
    $this->assertEquals(1, AttendanceSession::where('code_client', $this->client->code)->count());
    $this->assertEquals('closed', $out['session']->fresh()->status);
  }
}
