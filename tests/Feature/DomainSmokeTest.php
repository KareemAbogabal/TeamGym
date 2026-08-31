<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Coach\CoachProfile;
use App\Models\Coach\CoachAssignment;
use App\Models\Coach\ClientQrCode;
use App\Models\Coach\Membership;
use App\Models\Coach\AttendanceSession;
use App\Models\Coach\WorkoutPlan;
use App\Models\Coach\InbodyMeasurement;
use App\Enums\QrPurpose;
use App\Enums\QrStatus;

class DomainSmokeTest extends TestCase {
  use RefreshDatabase;

  public function test_new_domain_schema_and_models(): void {
    $client = new Client();
    $client->code = 'C001';
    $client->fname = 'John';
    $client->lname = 'Doe';
    $client->email = 'j@x.com';
    $client->phone = '123';
    $client->category = 'gold';
    $client->password = bcrypt('x');
    $client->save();

    $coach = new Employee();
    $coach->code = 'EMP1';
    $coach->fname = 'Coach';
    $coach->lname = 'One';
    $coach->job_role = 'coach';
    $coach->phone = '1';
    $coach->email = 'c@x.com';
    $coach->password = bcrypt('x');
    $coach->save();

    CoachProfile::create([
      'code_employee' => 'EMP1', 'specialization' => 'Strength', 'max_active_clients' => 5, 'is_active' => true,
    ]);
    $this->assertTrue(CoachProfile::where('code_employee', 'EMP1')->first()->hasCapacity());

    $ca = CoachAssignment::create([
      'code_client' => 'C001', 'code_coach' => 'EMP1',
      'requested_by_type' => 'client', 'direction' => 'client_to_coach',
      'status' => 'active', 'requested_at' => now(), 'started_at' => now(),
    ]);
    $this->assertEquals(1, $client->activeCoachAssignments()->count());
    $this->assertEquals(1, $client->activeCoachAssignment()->count());
    $this->assertEquals('EMP1', $client->currentCoach->code_coach);
    $this->assertEquals('EMP1', $client->activeCoach->code);

    $raw = ClientQrCode::generateRawToken();
    $qr = ClientQrCode::create([
      'code_client' => 'C001', 'token_hash' => ClientQrCode::hashToken($raw),
      'purpose' => QrPurpose::ClientLogin->value, 'status' => QrStatus::Active->value,
    ]);
    $this->assertTrue($qr->isValidFor(QrPurpose::ClientLogin));
    $this->assertFalse($qr->isValidFor(QrPurpose::GymCheckin));

    Membership::create(['code_client' => 'C001', 'status' => 'active']);
    $this->assertEquals(1, $client->memberships()->count());

    $session = AttendanceSession::create(['code_client' => 'C001']);
    $session->enter($coach);
    $this->assertEquals('open', $session->status);
    $session->leave($coach);
    $this->assertEquals('closed', $session->status);

    $plan = WorkoutPlan::create(['code_client' => 'C001', 'code_coach' => 'EMP1', 'title' => 'Split']);
    $this->assertEquals('Split', $client->workoutPlans()->first()->title);

    InbodyMeasurement::create(['code_client' => 'C001', 'weight' => 80.5]);
    $this->assertEquals(1, $client->inbodyMeasurements()->count());

    $this->assertTrue(true);
  }
}
