<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Coach\CoachProfile;
use App\Models\Coach\CoachAssignment;
use App\Services\CoachService;
use App\Enums\CoachRequestStatus;
use App\Enums\CoachRequestDirection;

class CoachFlowTest extends TestCase {
  use RefreshDatabase;

  private Client $client;
  private Employee $coach;
  private Employee $admin;
  private CoachService $service;

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

    $this->coach = $this->makeEmployee('EMP1', 'coach');
    $this->admin = $this->makeEmployee('ADM1', 'admin');

    CoachProfile::create([
      'code_employee' => 'EMP1',
      'specialization' => 'Strength',
      'max_active_clients' => 1,
      'is_active' => true,
    ]);

    $this->service = app(CoachService::class);
  }

  private function makeEmployee(string $code, string $role): Employee {
    $e = new Employee();
    $e->code = $code;
    $e->fname = 'Name';
    $e->lname = $code;
    $e->job_role = $role;
    $e->phone = '01000000000';
    $e->email = $code . '@x.com';
    $e->password = bcrypt('x');
    $e->save();
    return $e;
  }

  public function test_client_can_request_a_coach(): void {
    $a = $this->service->clientRequestsCoach($this->client, 'EMP1');

    $this->assertEquals(CoachRequestStatus::Pending->value, $a->status);
    $this->assertEquals(CoachRequestDirection::ClientToCoach->value, $a->direction);
    $this->assertEquals('EMP1', $a->code_coach);
  }

  public function test_duplicate_pending_request_is_rejected(): void {
    $this->service->clientRequestsCoach($this->client, 'EMP1');

    $this->expectException(\RuntimeException::class);
    $this->service->clientRequestsCoach($this->client, 'EMP1');
  }

  public function test_approve_activates_and_capacity_counts(): void {
    $a = $this->service->clientRequestsCoach($this->client, 'EMP1');

    $this->service->approve($a, 'ADM1');

    $this->assertEquals(CoachRequestStatus::Active->value, $a->fresh()->status);
    $this->assertEquals(1, CoachProfile::where('code_employee', 'EMP1')->first()->activeClientCount());
  }

  public function test_coach_cannot_exceed_capacity_at_approval(): void {
    $a = $this->service->clientRequestsCoach($this->client, 'EMP1');
    $this->service->approve($a, 'ADM1');

    $second = new Client();
    $second->code = 'C002';
    $second->fname = 'Jane';
    $second->lname = 'Doe';
    $second->email = 'j2@x.com';
    $second->phone = '1234';
    $second->category = 'gold';
    $second->password = bcrypt('x');
    $second->save();

    $b = $this->service->clientRequestsCoach($second, 'EMP1');

    $this->expectException(\RuntimeException::class);
    $this->service->approve($b, 'ADM1');
  }

  public function test_approving_ends_previous_active_and_reuses_client(): void {
    $a = $this->service->clientRequestsCoach($this->client, 'EMP1');
    $this->service->approve($a, 'ADM1');
    $this->assertEquals(CoachRequestStatus::Active->value, $a->fresh()->status);

    // End it, then a new request for the same client should be allowed.
    $this->service->end($a);
    $b = $this->service->clientRequestsCoach($this->client, 'EMP1');
    $this->assertEquals(CoachRequestStatus::Pending->value, $b->status);
  }

  public function test_end_marks_assignment_ended(): void {
    $a = $this->service->clientRequestsCoach($this->client, 'EMP1');
    $this->service->approve($a, 'ADM1');

    $this->service->end($a);

    $this->assertEquals(CoachRequestStatus::Ended->value, $a->fresh()->status);
    $this->assertNotNull($a->fresh()->ended_at);
  }

  public function test_client_can_request_new_coach_while_active_approved_replaces(): void {
    $admin = $this->makeEmployee('ADM2', 'admin');
    $coach2 = $this->makeEmployee('EMP2', 'coach');
    CoachProfile::create([
      'code_employee' => 'EMP2',
      'specialization' => 'Cardio',
      'max_active_clients' => 2,
      'is_active' => true,
    ]);

    $first = $this->service->clientRequestsCoach($this->client, 'EMP1');
    $this->service->approve($first, 'ADM1');
    $this->assertEquals(CoachRequestStatus::Active->value, $first->fresh()->status);

    // A change-coach request is allowed even though the client has an active coach.
    $change = $this->service->clientRequestsCoach($this->client, 'EMP2');
    $this->assertEquals(CoachRequestStatus::Pending->value, $change->status);

    $this->service->approve($change, 'ADM2');

    // Previous active assignment ended, new one active.
    $this->assertEquals(CoachRequestStatus::Ended->value, $first->fresh()->status);
    $this->assertEquals(CoachRequestStatus::Active->value, $change->fresh()->status);
    $this->assertEquals('EMP2', $this->service->activeForClient($this->client->code)->code_coach);
  }

  public function test_coach_can_request_client_already_training_with_another_coach(): void {
    $coach2 = $this->makeEmployee('EMP2', 'coach');
    CoachProfile::create([
      'code_employee' => 'EMP2',
      'specialization' => 'Cardio',
      'max_active_clients' => 2,
      'is_active' => true,
    ]);

    $first = $this->service->clientRequestsCoach($this->client, 'EMP1');
    $this->service->approve($first, 'ADM1');
    $this->assertEquals(CoachRequestStatus::Active->value, $first->fresh()->status);

    // Coach EMP2 requests this already-taken client; should be allowed as change-coach.
    $request = $this->service->coachRequestsClient($coach2, $this->client->code);
    $this->assertEquals(CoachRequestStatus::Pending->value, $request->status);
    $this->assertEquals(CoachRequestDirection::CoachToClient->value, $request->direction);
  }
}
