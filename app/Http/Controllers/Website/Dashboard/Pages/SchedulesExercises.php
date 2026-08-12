<?php

namespace App\Http\Controllers\Website\Dashboard\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use App\Models\Back\Activity;
use App\Models\Front\Client;
use Carbon\Carbon;

class SchedulesExercises extends Controller {
  public function index(Request $request) {
    $now = Carbon::now('Africa/Cairo');
    $day = $now->format('l');
    $clientCode = Cookie::get('login_client');
    $cookieKeyWeek = "last_visit_week_{$clientCode}";
    $cookieKeyDate = "last_visit_date_{$clientCode}";
    $startOfWeek = $now->copy()->startOfWeek(); // Monday 00:00
    $fridayThisWeek = $startOfWeek->copy()->addDays(4)->startOfDay(); // Friday 00:00
    $currentWeek = $now->format('oW');
    $lastVisitWeek = Cookie::get($cookieKeyWeek);
    $lastVisitDate = Cookie::get($cookieKeyDate);
    $doReset = false;
    if ($day === 'Friday') {
      $doReset = true;
    };
    if (!$doReset && $lastVisitWeek !== null && $lastVisitWeek !== $currentWeek) {
      $doReset = true;
    };
    if (!$doReset && is_null($lastVisitWeek) && $now->greaterThanOrEqualTo($fridayThisWeek)) {
      $doReset = true;
    };
    if (!$doReset && $lastVisitWeek === $currentWeek && $lastVisitDate) {
      try {
        $lastVisit = Carbon::parse($lastVisitDate, 'Africa/Cairo');
        if ($lastVisit->lessThan($fridayThisWeek) && $now->greaterThanOrEqualTo($fridayThisWeek)) {
          $doReset = true;
        };
      } catch (\Exception $e) {};
    };
    if ($doReset) {
      $toReset = Activity::where('code_client', $clientCode)->where('state', 'exercise')->get();
      foreach ($toReset as $activity) {
        $activity->statement = "true";
        $activity->visits = 0;
        $activity->save();
      };
      Cookie::queue($cookieKeyWeek, $currentWeek, 60 * 24 * 8);
    };
    Cookie::queue($cookieKeyDate, $now->toDateString(), 60 * 24 * 30);
    Cookie::queue($cookieKeyWeek, $currentWeek, 60 * 24 * 8);
    $activities = Activity::where("code_client", Cookie::get('login_client'))->where("state", "exercise")->with('elements.attachments')->get();
    $exercise = Activity::where("code_client", Cookie::get('login_client'))->where("day", $day)->where("statement", "true")->with('elements.attachments')->get();
    $foods = Activity::where("code_client", Cookie::get('login_client'))->where("state", "foods")->get();
    $start = $now->format('H:i');
    $end = $now->copy()->addHour()->format('H:i');
    $client = Client::where("code", Cookie::get('login_client'))->first();
    return view("Website.Dashboard.Pages.schedule", compact("client", "activities", "start", "end", "day", "exercise", "foods"));
  }
  public function getExercises(Request $request) {
    $activities = Activity::where("code_client", Cookie::get('login_client'))->where("code", $request->input("code"))->where("state", "exercise")->with('elements.attachments')->get();
    $exercise = [];
    foreach ($activities as $activity) {
      foreach ($activity->elements as $element) {
        if ($element->code_activities == $activity->code_attachments) {
          $exercise[] = $element;
        };
      };
    };
    return json_encode($exercise);
  }
  public function insertExerciseDay(Request $request) {
    try {
      $activitie = Activity::where('code', $request->input('code'))->firstOrFail();
      $day = Carbon::now()->format('l');
      $userCode = Cookie::get('login_client');
      $activitie->day = $day;
      $activitie->visits += 1;
      if ($activitie->visits == $activitie->times) {
        $activitie->statement = 'false';
      };
      $activitie->save();
      return response()->json($activitie);
    } catch (\Throwable $e) {
      Log::error('insertExerciseDay failed', [
        'error' => $e->getMessage(),
        'code' => $request->input('code'),
        'ip' => $request->ip(),
      ]);
      return response()->json(['error' => 'Internal Server Error'], 500);
    };
  }
}
