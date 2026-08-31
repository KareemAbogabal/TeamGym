<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use App\Models\Front\LineageInBody;
use App\Models\Back\Activity;
use App\Models\Front\Client;
use App\Models\Back\Payment;
use App\Models\Back\Record;
use App\Models\Front\Supplement;
use App\Models\Back\Employee;
use App\Models\Coach\CoachProfile;
use App\Models\Coach\CoachAssignment;
use App\Enums\CoachRequestStatus;
use App\Models\Back\RequestsPayment;
use Carbon\Carbon;

class Dashboard extends Controller {
  public function lineage($item) {
    $lineage;
    $date = strtolower(Carbon::now('Africa/Cairo')->format('F'));
    $months = [
      'january', 'february', 'march', 'april', 'may', 'june',
      'july', 'august', 'september', 'october', 'november', 'december'
    ];
    foreach ($months as $m) {
      if ($m == $date) {
        $lineage = $item->{$m} ?? 0;
        break;
      };
    };
    return $lineage;
  }
  public function lineageMonths($item) {
    $lineage = [];
    $date = strtolower(Carbon::now('Africa/Cairo')->format('F'));
    $months = [
      'january','february','march','april','may','june',
      'july','august','september','october','november','december'
    ];
    foreach ($months as $m) {
      if ($m === $date) {
        $lineage[] = $item->{$m} ?? 0;
      } else {
        $lineage[] = null;
      };
    };
    return $lineage;
  }
  public static function analyzeLineageCollection(Collection $rows): array {
    $months = [
      'january','february','march','april','may','june',
      'july','august','september','october','november','december'
    ];
    $getMetricValue = function (Collection $rows, string $metricName, string $month) {
      $metricName = strtolower($metricName);
      $sum = 0.0;
      foreach ($rows as $r) {
        if (isset($r->name) && strtolower($r->name) === $metricName) {
          $val = isset($r->{$month}) ? (float) $r->{$month} : 0.0;
          $sum += $val;
        };
      };
      return $sum;
    };
    $currentIndex = Carbon::now()->month - 1;
    $monthsToAnalyze = array_slice($months, 0, $currentIndex + 1);
    $dominantPercents = [];
    foreach ($monthsToAnalyze as $m) {
      $muscleSum = 0.0;
      $fatSum = 0.0;
      $muscleSum += $getMetricValue($rows, 'SMM', $m);
      $muscleSum += $getMetricValue($rows, 'left_arm_lean', $m);
      $muscleSum += $getMetricValue($rows, 'right_arm_lean', $m);
      $muscleSum += $getMetricValue($rows, 'left_leg_lean', $m);
      $muscleSum += $getMetricValue($rows, 'right_leg_lean', $m);
      $fatSum += $getMetricValue($rows, 'fat_mass', $m);
      $fatSum += $getMetricValue($rows, 'left_arm_fat', $m);
      $fatSum += $getMetricValue($rows, 'right_arm_fat', $m);
      $fatSum += $getMetricValue($rows, 'left_leg_fat', $m);
      $fatSum += $getMetricValue($rows, 'right_leg_fat', $m);
      $denom = $muscleSum + $fatSum;
      if ($denom > 0) {
        $musclePercent = ($muscleSum / $denom) * 100;
        $fatPercent = ($fatSum / $denom) * 100;
        if ($muscleSum > $fatSum) {
          $dominantPercents[] = round($musclePercent, 2);
        } else {
          $dominantPercents[] = round($fatPercent, 2);
        };
      } else {
        $dominantPercents[] = 0.0;
      };
    };
    return $dominantPercents;
  }
  public function index(Request $request) {
    $water = $this->lineage(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "water")->first());
    $fat = $this->lineage(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "fat_mass")->first());
    $smm = $this->lineage(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "SMM")->first());
    $waterM = $this->lineageMonths(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "water")->first());
    $proteinM = $this->lineageMonths(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "protein")->first());
    $fatM = $this->lineageMonths(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "fat_mass")->first());
    $weight = $this->lineage(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "weight")->first());
    $bmi = $this->lineage(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "BMI")->first());
    $pbf = $this->lineage(LineageInBody::where("code", Cookie::get('login_client'))->where("name", "PBF")->first());
    $rows = LineageInBody::where('code', Cookie::get('login_client'))->get();
    $analysis = $this->analyzeLineageCollection($rows);
    $day = Carbon::now()->format('l');
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $exercise = Activity::where("code_client", Cookie::get('login_client'))->where("day", $day)->where("statement", "true")->with('elements.attachments')->get();

    $coaches = Employee::where('job_role', 'coach')
      ->orWhere('job_role', 'trainer')
      ->get()
      ->map(function ($coach) {
        $profile = CoachProfile::where('code_employee', $coach->code)->first();
        return [
          'employee' => $coach,
          'profile' => $profile,
        ];
      });
    $pendingCoach = $client ? CoachAssignment::where('code_client', $client->code)
      ->where('status', CoachRequestStatus::Pending->value)
      ->with(['coach'])
      ->latest('requested_at')
      ->first() : null;
    $activeCoach = $client ? CoachAssignment::where('code_client', $client->code)
      ->where('status', CoachRequestStatus::Active->value)
      ->with(['coach'])
      ->latest('started_at')
      ->first() : null;

    return view('Website.Dashboard.Pages.dashboard', compact("client", "water", "fat", "smm", "weight", "bmi", "pbf", "waterM", "proteinM", "fatM", "analysis", "exercise", "coaches", "pendingCoach", "activeCoach"));
  }
  public function search(Request $request) {
    $name  = mb_strtolower($request->input('name', ''));
    $fname = mb_strtolower($request->input('fname', ''));
    $lname = mb_strtolower($request->input('lname', ''));
    $result = [];
    $client = Client::where("code", Cookie::get('login_client'))->first();
    $Client = Client::whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->where("code", $client->code)->first();
    $Lineage = LineageInBody::whereRaw('LOWER(name) = ?', [$name])->where("code", $client->code)->first();
    $Payment = Payment::whereHas('client', function($q) use ($fname, $lname, $client) {$q->whereRaw('LOWER(fname) = ?', [$fname])->whereRaw('LOWER(lname) = ?', [$lname])->where("code_client", $client->code);})->first();
    $Supplement = Payment::whereRaw('LOWER(order_name) = ?', [$name])->where("code_client", $client->code)->first();
    $System = Payment::whereRaw('LOWER(order_name) = ?', [$name])->where("code_client", $client->code)->first();
    $Record = Record::whereRaw('LOWER(name_client) = ?', [$name])->orWhereRaw('LOWER(name_employee) = ?', [$name])->where("code_client", $client->code)->first();
    $Activity = Activity::whereRaw('LOWER(name) = ?', [$name])->where("code_client", $client->code)->first();
    $models = compact(
      'Client', 'Lineage', 'Supplement', 'System',
      'Record', 'Activity', 'Payment'
    );
    $routes = [
      'Client' => route('plans'),
      'Lineage' => route('health'),
      'Supplement' => route('plans'),
      'System' => route('plans'),
      'Snacks' => route('plans'),
      'Record' => route('plans'),
      'Activity' => route('schedule'),
      'Payment' => route('supplementStore'),
    ];
    $pages = [
      'Client' => 'plans',
      'Lineage' => 'health',
      'Supplement' => 'plans',
      'System' => 'plans',
      'Snacks' => 'plans',
      'Record' => 'plans',
      'Activity' => 'schedule',
      'Payment' => 'supplementStore',
    ];
    foreach ($models as $key => $value) {
      if ($value) {
        $result[] = [
          'data' => $value,
          'route' => $routes[$key] ?? null,
          'page' => $pages[$key] ?? null,
        ];
      };
    };
    return response()->json($result);
  }
  public function searchImg(Request $request) {
    $img = $request->input('img');
    if (!$img || !trim($img)) {
      return response()->json([
        'path' => asset('images/header/Team-Gym.png')
      ]);
    }
    $paths = [
      public_path("images/subscribers/$img"),
    ];
    foreach ($paths as $path) {
      if (File::exists($path)) {
        return response()->json([
          'path' => asset(str_replace(public_path(), '', $path))
        ]);
      };
    };
    return response()->json([
      'path' => asset('images/header/Team-Gym.png')
    ]);
  }
}
