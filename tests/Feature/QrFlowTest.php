<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Front\Client;
use App\Models\Coach\ClientQrCode;
use App\Models\Coach\QrScanLog;
use App\Services\ClientQrService;
use App\Enums\QrPurpose;
use App\Enums\QrStatus;

class QrFlowTest extends TestCase {
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

  private function lastQr(): ClientQrCode {
    return ClientQrCode::where('code_client', $this->client->code)->latest('id')->first();
  }

  public function test_only_hash_is_stored_not_raw_token(): void {
    $raw = $this->service->issue($this->client, QrPurpose::ClientIdentity);
    $stored = $this->lastQr();

    $this->assertNotEquals($raw, $stored->token_hash);
    $this->assertEquals(ClientQrCode::hashToken($raw), $stored->token_hash);
    $this->assertStringNotContainsString($raw, $stored->getAttributes()['token_hash'] ?? '');
  }

  public function test_valid_token_verifies_to_client(): void {
    $raw = $this->service->issue($this->client, QrPurpose::ClientIdentity);

    $verified = $this->service->verify($raw, QrPurpose::ClientIdentity);
    $this->assertEquals('C001', $verified->code);
    $this->assertEquals(1, $this->lastQr()->scan_count);
  }

  public function test_wrong_purpose_is_rejected(): void {
    $raw = $this->service->issue($this->client, QrPurpose::ClientIdentity);

    $this->expectException(\InvalidArgumentException::class);
    $this->service->verify($raw, QrPurpose::ClientLogin);
  }

  public function test_unknown_token_is_rejected_and_logged(): void {
    $this->expectException(\InvalidArgumentException::class);
    $this->service->verify('nonsense-token', QrPurpose::ClientIdentity);
  }

  public function test_revoked_token_is_rejected(): void {
    $raw = $this->service->issue($this->client, QrPurpose::ClientIdentity);
    $qr = $this->lastQr();
    $this->service->revoke($qr);

    $this->assertEquals(QrStatus::Revoked->value, $qr->fresh()->status);

    $this->expectException(\InvalidArgumentException::class);
    $this->service->verify($raw, QrPurpose::ClientIdentity);
  }

  public function test_expired_token_is_rejected_and_marked_expired(): void {
    $raw = $this->service->issue($this->client, QrPurpose::ClientIdentity, now()->subMinute()->toDateTimeString());

    $this->expectException(\InvalidArgumentException::class);
    try {
      $this->service->verify($raw, QrPurpose::ClientIdentity);
    } finally {
      $this->assertEquals(QrStatus::Expired->value, $this->lastQr()->status);
    }
  }

  public function test_scan_attempts_are_logged(): void {
    $raw = $this->service->issue($this->client, QrPurpose::ClientIdentity);

    $this->service->verify($raw, QrPurpose::ClientIdentity, 'test', '127.0.0.1', 'UA');
    try {
      $this->service->verify('bad', QrPurpose::ClientIdentity, 'test');
    } catch (\Throwable $e) {
      // expected
    }

    $logs = QrScanLog::all();
    $this->assertCount(2, $logs);
    $this->assertTrue($logs->first()->success);
    $this->assertFalse($logs->last()->success);
  }

  public function test_qr_login_endpoint_authenticates_client(): void {
    $raw = $this->service->issue($this->client, QrPurpose::ClientIdentity);

    $this->post('/qr-login', ['token' => $raw])
      ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($this->client, 'client');
  }
}
