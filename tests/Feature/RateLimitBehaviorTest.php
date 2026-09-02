<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\Front\Client;

class RateLimitBehaviorTest extends TestCase {
  use RefreshDatabase;

  private function makeClient(array $attributes = []): Client {
    $client = new Client();
    $client->code = $attributes['code'] ?? random_int(100000, 999999);
    $client->fname = $attributes['fname'] ?? 'Rate';
    $client->lname = $attributes['lname'] ?? 'User';
    $client->email = $attributes['email'] ?? (bin2hex(random_bytes(6)) . '@test.local');
    $client->phone = $attributes['phone'] ?? '01000000000';
    $client->category = $attributes['category'] ?? 'default';
    $client->password = Hash::make($attributes['password'] ?? 'secret');
    $client->save();
    return $client;
  }

  public function test_client_login_is_blocked_after_eight_attempts_with_retry_after(): void {
    for ($i = 1; $i <= 8; $i++) {
      $this->post('/login-in', [
        'email' => 'limiter@test.local',
        'password' => 'wrong-' . $i,
      ])->assertStatus(302);
    }

    $blocked = $this->post('/login-in', [
      'email' => 'limiter@test.local',
      'password' => 'wrong-9',
    ]);

    $blocked->assertStatus(429);
    $this->assertNotNull($blocked->headers->get('Retry-After'));
    $this->assertGreaterThan(0, (int) $blocked->headers->get('Retry-After'));
  }

  public function test_client_login_bucket_recovers_after_the_window(): void {
    for ($i = 1; $i <= 9; $i++) {
      $this->post('/login-in', [
        'email' => 'recover@test.local',
        'password' => 'wrong-' . $i,
      ]);
    }

    $this->post('/login-in', [
      'email' => 'recover@test.local',
      'password' => 'wrong-9',
    ])->assertStatus(429);

    Carbon::setTestNow(Carbon::now()->addMinutes(2));
    try {
      $this->post('/login-in', [
        'email' => 'recover@test.local',
        'password' => 'wrong-10',
      ])->assertStatus(302);
    } finally {
      Carbon::setTestNow(null);
    }
  }

  public function test_signup_is_per_ip_limited_to_five_per_hour(): void {
    for ($i = 1; $i <= 5; $i++) {
      $this->post('/sign-up', [
        'fname' => 'Dup',
        'lname' => 'Email',
        'phone' => '010' . str_pad((string) (10000000 + $i), 8, '0', STR_PAD_LEFT),
        'email' => 'same@test.local',
        'password' => 'short',
      ]);
    }

    $this->post('/sign-up', [
      'fname' => 'Dup',
      'lname' => 'Email',
      'phone' => '01000000099',
      'email' => 'same@test.local',
      'password' => 'short',
    ])->assertStatus(429);
  }

  public function test_client_login_cannot_be_bypassed_with_spoofed_forwarded_header(): void {
    for ($i = 1; $i <= 8; $i++) {
      $this->post('/login-in', [
        'email' => 'spoof@test.local',
        'password' => 'wrong-' . $i,
      ])->assertStatus(302);
    }

    $spoofed = $this->withServerVariables(['HTTP_X_FORWARDED_FOR' => '203.0.113.99'])
      ->post('/login-in', [
        'email' => 'spoof@test.local',
        'password' => 'wrong-9',
      ]);

    $spoofed->assertStatus(429);
  }

  public function test_security_headers_are_sent_on_responses(): void {
    $response = $this->get('/login-page');

    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
  }

  public function test_hsts_is_sent_only_over_https_or_when_configured(): void {
    $insecure = $this->get('/login-page');
    $this->assertNull($insecure->headers->get('Strict-Transport-Security'));

    config(['services.security.hsts' => true]);
    $configured = $this->get('/login-page');
    $this->assertNotNull($configured->headers->get('Strict-Transport-Security'));
    $this->assertStringContainsString('max-age=31536000', (string) $configured->headers->get('Strict-Transport-Security'));
  }

  public function test_password_reset_limiter_is_scoped_per_ip_and_email(): void {
    for ($i = 1; $i <= 6; $i++) {
      $this->post('/forget', ['email' => 'account-a@test.local']);
    }
    $this->post('/forget', ['email' => 'account-a@test.local'])->assertStatus(429);

    $other = $this->post('/forget', ['email' => 'account-b@test.local']);
    $other->assertStatus(302);
    $this->assertNotEquals(429, $other->getStatusCode());
  }
}