<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

use App\Models\Back\CustomerRequests;
use App\Models\Back\Employee;
use App\Models\Front\Client;
use App\Services\ResetCodeService;

class SecurityRegressionTest extends TestCase {
  use RefreshDatabase;

  public function test_spoofed_login_cookie_does_not_authenticate(): void {
    $this->makeClient(['code' => 987654321, 'email' => 'spoof@test.local']);

    $response = $this->withCookie('login_client', '987654321')->get('/dashboard');

    $this->assertNull(auth('client')->user());
    $response->assertRedirect();
    $this->assertNotEquals(200, $response->getStatusCode());
  }

  private function makeClient(array $attributes = []): Client {
    $client = new Client();
    $client->code = $attributes['code'] ?? random_int(100000, 999999);
    $client->fname = $attributes['fname'] ?? 'Test';
    $client->lname = $attributes['lname'] ?? 'User';
    $client->email = $attributes['email'] ?? (bin2hex(random_bytes(6)) . '@test.local');
    $client->phone = $attributes['phone'] ?? '01000000000';
    $client->category = $attributes['category'] ?? 'default';
    $client->password = Hash::make($attributes['password'] ?? 'secret');
    $client->save();
    return $client;
  }

  public function test_auto_record_route_is_post_only(): void {
    $route = Route::getRoutes()->getByName('autoRecord');
    $this->assertTrue(in_array('POST', $route->methods()));
    $this->assertFalse(in_array('GET', $route->methods()));
    $this->get('/auto-record')->assertStatus(405);
  }

  public function test_auth_rate_limiters_are_registered(): void {
    foreach (['client-login', 'company-login', 'signup', 'qr-login', 'password-reset', 'staff-scan', 'request-create'] as $name) {
      $this->assertNotNull(RateLimiter::limiter($name), "Rate limiter '$name' is not registered");
    }
  }

  public function test_non_admin_cannot_create_an_employee(): void {
    $coach = Employee::create([
      'code' => 1001,
      'fname' => 'Not',
      'lname' => 'Admin',
      'job_role' => 'coach',
      'phone' => '01000000001',
      'email' => 'coach@test.local',
      'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($coach, 'employee')->post('/add-employees', [
      'fname' => 'Escalated',
      'lname' => 'Role',
      'job_role' => 'Admin',
      'phone' => '01000000002',
      'img' => UploadedFile::fake()->create('x.png', 100, 'image/png'),
      'email' => 'escalated@test.local',
      'password' => 'password',
      'documentation' => 'on',
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseMissing('employees', ['email' => 'escalated@test.local']);
  }

  public function test_admin_can_create_employee_and_role_is_normalized(): void {
    $admin = Employee::create([
      'code' => 2002,
      'fname' => 'Real',
      'lname' => 'Admin',
      'job_role' => 'Admin',
      'phone' => '01000000003',
      'email' => 'admin@test.local',
      'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($admin, 'employee')->post('/add-employees', [
      'fname' => 'New',
      'lname' => 'Employee',
      'job_role' => 'Admin',
      'phone' => '01000000004',
      'img' => UploadedFile::fake()->create('photo.png', 100, 'image/png'),
      'email' => 'NEWSTAFF@TEST.LOCAL',
      'password' => 'strong-password',
      'documentation' => 'on',
    ]);

    $this->assertDatabaseHas('employees', [
      'email' => 'newstaff@test.local',
      'job_role' => 'admin',
    ]);
    $response->assertStatus(302);
  }

  public function test_client_cannot_delete_another_clients_request(): void {
    $owner = $this->makeClient([
      'code' => 3001, 'fname' => 'Owner', 'lname' => 'One',
      'email' => 'owner@test.local', 'phone' => '01000000005',
    ]);
    $attacker = $this->makeClient([
      'code' => 3002, 'fname' => 'Other', 'lname' => 'Two',
      'email' => 'other@test.local', 'phone' => '01000000006',
    ]);
    $request = new CustomerRequests();
    $request->code = '4001';
    $request->code_client = $owner->code;
    $request->email = $owner->email;
    $request->code_order = 1;
    $request->fname = $owner->fname;
    $request->lname = $owner->lname;
    $request->phone = $owner->phone;
    $request->type = 'system';
    $request->state = 'pending';
    $request->amount = 100;
    $request->paid = 0;
    $request->save();

    $response = $this->actingAs($attacker, 'client')
      ->post('/delete-request-customer', ['code' => $request->code]);

    $response->assertStatus(403);
    $this->assertDatabaseHas('customer_requests', ['id' => $request->id]);
  }

  public function test_password_reset_is_bound_to_the_session_email(): void {
    $client = $this->makeClient([
      'code' => 5001, 'fname' => 'Reset', 'lname' => 'Target',
      'email' => 'reset@test.local', 'phone' => '01000000007',
      'password' => 'old-password',
    ]);

    Cache::put('passreset.client.reset@test.local', [
      'hash' => Hash::make('123456'),
      'identifier' => 'reset@test.local',
      'expires_at' => now()->addMinutes(10)->timestamp,
      'attempts' => 0,
    ], 600);

    $verify = $this->withSession(['client_reset_email' => 'reset@test.local'])
      ->post('/verify-code', ['code' => '123456']);
    $verify->assertRedirect('/login-page');
    $this->assertTrue(session('client_reset_verified'));

    $this->post('/reset-password', [
      'password' => 'new-strong-password',
      'password_confirmation' => 'new-strong-password',
    ])->assertRedirect('/login-page');

    $this->assertTrue(Hash::check('new-strong-password', $client->fresh()->password));
    $this->assertFalse(Hash::check('old-password', $client->fresh()->password));
  }

  public function test_login_returns_generic_error_for_bad_and_unknown_accounts(): void {
    $this->makeClient([
      'code' => 6001, 'fname' => 'Known', 'lname' => 'User',
      'email' => 'known@test.local', 'phone' => '01000000008',
      'password' => 'right-password',
    ]);

    $wrongPassword = $this->post('/login-in', [
      'email' => 'known@test.local',
      'password' => 'wrong-password',
    ]);
    $unknown = $this->post('/login-in', [
      'email' => 'nobody@test.local',
      'password' => 'whatever',
    ]);

    $wrongPassword->assertSessionHasErrors('credentials');
    $unknown->assertSessionHasErrors('credentials');
    $this->assertNull(auth('client')->user());
  }

  public function test_signup_rejects_short_passwords_and_duplicates(): void {
    $this->post('/sign-up', [
      'fname' => 'Short',
      'lname' => 'Pw',
      'phone' => '01000000009',
      'email' => 'shortpw@test.local',
      'password' => '1234567',
    ])->assertSessionHasErrors('password');

    $this->makeClient([
      'code' => 6002, 'fname' => 'First', 'lname' => 'Member',
      'email' => 'dupe@test.local', 'phone' => '01000000010',
    ]);

    $this->post('/sign-up', [
      'fname' => 'Second',
      'lname' => 'Member',
      'phone' => '01000000011',
      'email' => 'DUPE@test.local',
      'password' => 'strong-password',
    ])->assertSessionHasErrors('email');
  }

  public function test_temporary_company_cookie_alone_cannot_login(): void {
    Employee::create([
      'code' => 7001,
      'fname' => 'Boss',
      'lname' => 'User',
      'job_role' => 'Admin',
      'phone' => '01000000012',
      'email' => 'boss@test.local',
      'password' => Hash::make('password'),
    ]);

    $this->withCookie('temporary_company', 'hijacked')
      ->post('/login', ['email' => 'boss@test.local', 'password' => 'wrong-password'])
      ->assertSessionHasErrors();

    $this->assertGuest('employee');

    $wrongPasswordWithCookie = $this->withCookie('temporary_company', 'hijacked')
      ->post('/login', ['email' => 'boss@test.local', 'password' => 'still-wrong'])
      ->assertSessionHasErrors();
    $this->assertGuest('employee');
    $this->assertNotNull($wrongPasswordWithCookie);
  }

  public function test_reset_code_is_single_use_and_attempt_limited(): void {
    $email = 'otp@test.local';
    $code = ResetCodeService::issue(ResetCodeService::TYPE_CLIENT, $email);

    $this->assertTrue(strlen($code) === 6);
    $this->assertFalse(ResetCodeService::verify(ResetCodeService::TYPE_CLIENT, $email, '000000'));
    $this->assertTrue(ResetCodeService::verify(ResetCodeService::TYPE_CLIENT, $email, $code));

    $replay = ResetCodeService::issue(ResetCodeService::TYPE_CLIENT, $email);
    $this->assertTrue(ResetCodeService::verify(ResetCodeService::TYPE_CLIENT, $email, $replay));
    $this->assertFalse(ResetCodeService::verify(ResetCodeService::TYPE_CLIENT, $email, $replay));

    $second = ResetCodeService::issue(ResetCodeService::TYPE_CLIENT, $email);
    for ($i = 0; $i < ResetCodeService::MAX_ATTEMPTS; $i++) {
      ResetCodeService::verify(ResetCodeService::TYPE_CLIENT, $email, '111111');
    }
    $this->assertFalse(ResetCodeService::verify(ResetCodeService::TYPE_CLIENT, $email, $second));
  }

  public function test_unauthenticated_client_is_sent_to_the_login_page(): void {
    $this->get('/dashboard')->assertRedirect(route('loginPage'));

    $this->makeClient(['code' => 8101, 'email' => 'guest@test.local']);
    $this->withCookie('login_client', '8101')->get('/dashboard')
      ->assertRedirect(route('loginPage'));
  }

  public function test_client_login_issues_remember_me_token_and_dashboard_restores_without_session(): void {
    $client = $this->makeClient([
      'code' => 8201, 'fname' => 'Remember', 'lname' => 'Me',
      'email' => 'remember@test.local', 'phone' => '01000000014',
      'password' => 'strong-password',
    ]);

    $login = $this->post('/login-in', [
      'email' => 'remember@test.local',
      'password' => 'strong-password',
    ]);
    $login->assertRedirect(route('front'));

    $rememberCookie = null;
    foreach ($login->headers->getCookies() as $cookie) {
      if (str_starts_with($cookie->getName(), 'remember_')) {
        $rememberCookie = [$cookie->getName() => $cookie->getValue()];
        break;
      }
    }
    $this->assertNotNull($rememberCookie, 'remember cookie was not issued');
    $this->assertNotNull($client->fresh()->remember_token, 'remember_token was not persisted');

    $this->get('/dashboard')->assertOk();
  }

  public function test_spoofed_login_cookie_mismatch_is_forgotten(): void {
    $client = $this->makeClient([
      'code' => 8001, 'fname' => 'Match', 'lname' => 'Check',
      'email' => 'match@test.local', 'phone' => '01000000013',
    ]);

    $this->actingAs($client, 'client')
      ->withCookie('login_client', '424242')
      ->get('/dashboard')
      ->assertCookieExpired('login_client');
  }
}