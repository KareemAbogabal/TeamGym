<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

use App\Models\Back\Employee;
use App\Models\Front\Client;
use App\Models\Front\Cardio;
use App\Models\Front\LineageInBody;
use App\Models\Front\ImgInBody;

class CertificationRegressionTest extends TestCase {
  use RefreshDatabase;

  private function makeEmployee(array $attributes = []): Employee {
    $employee = new Employee();
    $employee->code = $attributes['code'] ?? random_int(100000, 999999);
    $employee->fname = $attributes['fname'] ?? 'Staff';
    $employee->lname = $attributes['lname'] ?? 'User';
    $employee->job_role = $attributes['job_role'] ?? 'admin';
    $employee->phone = $attributes['phone'] ?? '01000000021';
    $employee->email = $attributes['email'] ?? (bin2hex(random_bytes(6)) . '@test.local');
    $employee->password = Hash::make($attributes['password'] ?? 'secret');
    $employee->save();
    return $employee;
  }

  private function makeClient(array $attributes = []): Client {
    $client = new Client();
    $client->code = $attributes['code'] ?? random_int(100000, 999999);
    $client->fname = $attributes['fname'] ?? 'Test';
    $client->lname = $attributes['lname'] ?? 'User';
    $client->email = $attributes['email'] ?? (bin2hex(random_bytes(6)) . '@test.local');
    $client->phone = $attributes['phone'] ?? '01000000022';
    $client->category = $attributes['category'] ?? 'default';
    $client->password = Hash::make($attributes['password'] ?? 'secret');
    $client->save();
    return $client;
  }

  private function pngUpload(): UploadedFile {
    $realPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    $path = tempnam(sys_get_temp_dir(), 'img');
    file_put_contents($path, $realPng);
    return new UploadedFile($path, 'pro.png', 'image/png', null, true);
  }

  public function test_save_img_rejects_executable_payload(): void {
    $client = $this->makeClient(['code' => 9101, 'email' => 'img@test.local']);

    $response = $this->actingAs($client, 'client')->post('/save-img', [
      'img' => UploadedFile::fake()->create('shell.php', 100, 'application/x-php'),
    ], ['Accept' => 'application/json']);

    $this->assertTrue(in_array($response->getStatusCode(), [422, 302], true));
    $this->assertDatabaseCount('img_in_bodies', 0);
  }

  public function test_save_img_persists_under_session_client(): void {
    $client = $this->makeClient(['code' => 9102, 'email' => 'img2@test.local']);
    $other = $this->makeClient(['code' => 9103, 'email' => 'img3@test.local']);

    $this->actingAs($client, 'client')
      ->post('/save-img', ['img' => $this->pngUpload()], ['Accept' => 'application/json'])
      ->assertOk();

    $row = ImgInBody::first();
    $this->assertNotNull($row);
    $this->assertEquals($client->code, $row->code);
    $this->assertNotEquals($other->code, $row->code);
    $this->assertStringNotContainsString('..', $row->img ?? '');
  }

  public function test_cardio_write_ignores_foreign_code_client(): void {
    $client = $this->makeClient(['code' => 9201, 'email' => 'cardio@test.local']);
    $victim = $this->makeClient(['code' => 9202, 'email' => 'victim@test.local']);

    $this->actingAs($client, 'client')->post('/save-data-cardio', [
      'code_client' => $victim->code,
      'name' => 'treadmill',
      'minutes' => 30,
      'distance' => 2.5,
    ], ['Accept' => 'application/json'])->assertOk();

    $row = Cardio::first();
    $this->assertNotNull($row);
    $this->assertEquals($client->code, $row->code_client);
    $this->assertNotEquals($victim->code, $row->code_client);
  }

  public function test_lineage_write_is_bound_to_session_client(): void {
    $client = $this->makeClient(['code' => 9301, 'email' => 'lineage@test.local']);
    $victim = $this->makeClient(['code' => 9302, 'email' => 'victim2@test.local']);

    $this->actingAs($client, 'client')
      ->post('/lineage', ['name' => 'weight', 'lineage' => 88.5], ['Accept' => 'application/json'])
      ->assertOk();

    $row = LineageInBody::first();
    $this->assertNotNull($row);
    $this->assertEquals($client->code, $row->code);
    $this->assertNotEquals($victim->code, $row->code);
  }

  public function test_non_admin_employee_is_blocked_from_client_destruction(): void {
    $staff = $this->makeEmployee([
      'code' => 9401, 'job_role' => 'reception', 'email' => 'reception@test.local', 'phone' => '01000000023',
    ]);
    $client = $this->makeClient(['code' => 9402, 'email' => 'del@test.local']);

    $this->actingAs($staff, 'employee')
      ->post('/customers/destroy', ['id' => $client->id, 'state' => 'destroy'])
      ->assertStatus(302);

    $this->assertDatabaseHas('clients', ['code' => $client->code]);
  }

  public function test_non_admin_employee_is_blocked_from_client_updates_and_catalog_changes(): void {
    $staff = $this->makeEmployee([
      'code' => 9501, 'job_role' => 'newstaff', 'email' => 'newstaff@test.local', 'phone' => '01000000024',
    ]);
    $client = $this->makeClient(['code' => 9502, 'email' => 'update@test.local']);

    $this->actingAs($staff, 'employee')
      ->post('/customers/update-client', [
        'code' => $client->code, 'fname' => 'Hacked', 'lname' => 'Name',
        'email' => 'new@test.local', 'phone' => '01000000025', 'category' => 'pro',
        'password' => 'attacker-controlled', 'documentation' => 'false',
      ])->assertStatus(302);

    $this->actingAs($staff, 'employee')
      ->post('/customers/regenerate-barcode', ['code' => $client->code])
      ->assertStatus(302);

    $this->actingAs($staff, 'employee')
      ->post('/remove-system', ['code' => '999999'])
      ->assertStatus(302);

    $this->actingAs($staff, 'employee')
      ->post('/destroy-exercises', ['id' => 1, 'state' => 'main'])
      ->assertStatus(302);

    $this->assertDatabaseHas('clients', ['code' => $client->code, 'fname' => 'Test']);
  }

  public function test_admin_can_update_client(): void {
    $admin = $this->makeEmployee([
      'code' => 9601, 'job_role' => 'Admin', 'email' => 'owner@test.local', 'phone' => '01000000026',
    ]);
    $client = $this->makeClient(['code' => 9602, 'email' => 'editme@test.local']);

    $this->actingAs($admin, 'employee')
      ->from('/customers')
      ->post('/customers/update-client', [
        'code' => $client->code, 'fname' => 'Edited', 'lname' => 'Admin',
        'email' => 'edited@test.local', 'phone' => '01000000027', 'category' => 'gold',
        'documentation' => 'true',
      ])->assertStatus(302);

    $this->assertDatabaseHas('clients', ['code' => $client->code, 'fname' => 'Edited']);
  }

  public function test_mail_template_cannot_render_plaintext_password(): void {
    $password = 's3cReT-' . bin2hex(random_bytes(8));

    $fakeMessage = new class {
      public function embed($path) {
        return 'cid:teamgym@test';
      }
    };

    View::share('message', $fakeMessage);
    $html = View::make('Mail.pageMail', [
      'userName' => 'A B', 'name' => 'A B', 'code' => '1',
      'time' => 'now', 'phone' => '01000000028',
      'password' => $password,
    ])->render();

    $this->assertStringNotContainsString($password, $html);
  }

  public function test_non_client_guard_cannot_use_save_img(): void {
    $staff = $this->makeEmployee([
      'code' => 9701, 'job_role' => 'reception', 'email' => 'staffimg@test.local', 'phone' => '01000000029',
    ]);

    $this->actingAs($staff, 'employee')
      ->post('/save-img', ['img' => $this->pngUpload()])
      ->assertRedirect(route('loginPage'));
    $this->assertGuest('client');
  }
}