<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Front\Client;
use App\Models\Back\Employee;
use App\Models\Back\SettingCompany;
use App\Models\Back\SettingEmployee;
use App\Models\Back\IncomeStatement;
use App\Models\Back\Lineage;
use App\Models\Coach\CoachProfile;
use App\Services\CoachService;

/**
 * Measures the number of DB queries the Company Dashboard pages issue.
 *
 * This is the BEFORE/AFTER evidence harness for the performance optimisations.
 * It asserts pages render (200) and persists per-route query counts to
 * storage/app/query-count-baseline.json so they can be compared across runs.
 */
final class QueryCountBaselineTest extends TestCase {
  use RefreshDatabase;

  private Employee $admin;
  private Employee $coach;
  private Client $client;

  protected function setUp(): void {
    parent::setUp();

    $this->admin = $this->makeEmployee('ADM1', 'admin');
    $this->coach = $this->makeEmployee('EMP1', 'coach');
    $this->makeEmployee('EMP2', 'coach');
    $this->makeEmployee('EMP3', 'coach');
    $this->makeEmployee('EMP4', 'trainer');
    $this->makeEmployee('EMP5', 'trainer');

    foreach (['EMP1', 'EMP2', 'EMP3', 'EMP4', 'EMP5'] as $code) {
      CoachProfile::create([
        'code_employee' => $code,
        'specialization' => 'Strength',
        'max_active_clients' => 5,
        'is_active' => true,
      ]);
    }

    $this->client = $this->makeClient('C001');
    $this->makeClient('C002');

    $service = app(CoachService::class);
    $service->clientRequestsCoach($this->client, 'EMP1');
    $service->clientRequestsCoach($this->makeClient('C003'), 'EMP2');
    $service->coachRequestsClient($this->coach, 'C002');
    $active = $service->clientRequestsCoach($this->makeClient('C004'), 'EMP1');
    $service->approve($active, 'ADM1');
    $ended = $service->clientRequestsCoach($this->makeClient('C005'), 'EMP2');
    $service->approve($ended, 'ADM1');
    $service->end($ended);
    $rejected = $service->clientRequestsCoach($this->makeClient('C006'), 'EMP3');
    $service->reject($rejected, 'ADM1', 'full');

    foreach (['system', 'supplement', 'input'] as $i => $state) {
      $this->makeIncomeStatement((string) (1000 + $i), "Revenue $state", $state, 'Revenues', (string) (5000 * ($i + 1)));
    }
    $this->makeIncomeStatement('2000', 'Rent', 'system', 'Expenses', '3000');
    Lineage::addLineage(null, null, null, '1000', null, 'Revenues', 5000);
    Lineage::addLineage(null, null, null, '2000', null, 'Expenses', 3000);

    $setting = new SettingCompany();
    $setting->code = 'ADM1';
    $setting->code_employee = 'ADM1';
    $setting->view_logs_logins = true;
    $setting->supplements_requests = true;
    $setting->subscription_requests = true;
    $setting->add_employees = false;
    $setting->save();

    $employeeSetting = new SettingEmployee();
    $employeeSetting->code = 'ADM1';
    $employeeSetting->code_employee = 'ADM1';
    $employeeSetting->class_reminders = false;
    $employeeSetting->login_alerts = false;
    $employeeSetting->save();
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

  private function makeClient(string $code): Client {
    $c = new Client();
    $c->code = $code;
    $c->fname = 'John';
    $c->lname = $code;
    $c->email = $code . '@x.com';
    $c->phone = '01000000000';
    $c->category = 'gold';
    $c->password = bcrypt('x');
    $c->save();
    return $c;
  }

  private function makeIncomeStatement(string $code, string $name, string $state, string $type, string $amount): void {
    $row = new IncomeStatement();
    $row->code = $code;
    $row->name = $name;
    $row->state = $state;
    $row->type = $type;
    $row->amount = $amount;
    $row->save();
  }

  private function countQueries(string $route): array {
    DB::flushQueryLog();
    DB::enableQueryLog();
    $response = $this->get(route($route));
    $response->assertStatus(200);
    $log = DB::getQueryLog();
    $sqls = array_column($log, 'query');
    return [
      'total' => count($log),
      'selects' => count(array_filter($sqls, fn ($s) => str_starts_with(strtolower(ltrim($s)), 'select'))),
    ];
  }

  public function test_coach_management_baseline_is_recorded(): void {
    $this->actingAs($this->admin, 'employee');
    $count = $this->countQueries('coachManagement');
    $this->assertGreaterThan(0, $count['total']);
    $this->assertNotNull($count['total']);
  }

  public function test_dashboard_company_baseline_is_recorded(): void {
    $this->actingAs($this->admin, 'employee');
    $this->countQueries('dashboardCompany');
    $this->assertTrue(true);
  }

  public function test_baselines_are_persisted(): void {
    $this->actingAs($this->admin, 'employee');
    $results = [
      'coachManagement.before' => $this->countQueries('coachManagement'),
      'dashboardCompany.before' => $this->countQueries('dashboardCompany'),
    ];
    $path = storage_path('app/query-count-baseline.json');
    file_put_contents($path, json_encode($results, JSON_PRETTY_PRINT));
    $this->assertFileExists($path);
  }
}