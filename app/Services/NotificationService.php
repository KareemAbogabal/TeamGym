<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Models\Back\Payment;
use App\Models\Back\Imports;
use App\Models\Back\Employee;
use Carbon\Carbon;

class NotificationService {
  public function cleanStaleNotifications(Client $client): void {
    $this->cleanInBodyNotifications($client);
    $this->cleanPaymentNotifications($client);
    $this->cleanExerciseNotifications($client);
  }

  public function cleanEmployeeNotifications(Employee $employee): void {
    $this->cleanProductNotifications($employee);
    $this->cleanIncomeNotifications($employee);
  }

  private function cleanInBodyNotifications(Client $client): void {
    $month = strtolower(Carbon::now('Africa/Cairo')->format('F'));
    $hasCurrentMonthData = LineageInBody::where('code', $client->code)
      ->whereNotNull($month)
      ->exists();
    if ($hasCurrentMonthData) {
      Notification::where('code_client', $client->code)
        ->where('type', 'InBody')
        ->delete();
    }
  }

  private function cleanPaymentNotifications(Client $client): void {
    $paidPayments = Payment::where('code_client', $client->code)
      ->whereColumn('amount', '=', 'paid')
      ->get();
    foreach ($paidPayments as $payment) {
      $query = Notification::where('code_client', $client->code)
        ->where('type', $payment->type)
        ->where('amount', $payment->amount);
      if ($payment->code_supplements) {
        $query->where('code_supplements', $payment->code_supplements);
      }
      if ($payment->code_systems) {
        $query->where('code_systems', $payment->code_systems);
      }
      $query->delete();
    }
  }

  private function cleanExerciseNotifications(Client $client): void {
    $today = Carbon::now('Africa/Cairo')->format('l');
    $todayLower = strtolower(trim($today));
    $hasWorkoutToday = $client->activities()->whereRaw('LOWER(TRIM(day)) = ?', [$todayLower])->exists();
    if (!$hasWorkoutToday) {
      Notification::where('code_client', $client->code)
        ->where('type', 'exercise')
        ->delete();
    }
  }

  private function cleanProductNotifications(Employee $employee): void {
    $lowStockCodes = Imports::where('quantity', '<', 2)->pluck('code_supplements')->filter()->toArray();
    Notification::where('code_employee', $employee->code)
      ->where('type', 'product')
      ->whereNotIn('code_supplements', $lowStockCodes)
      ->delete();
  }

  private function cleanIncomeNotifications(Employee $employee): void {
    Notification::where('code_employee', $employee->code)
      ->where('type', 'income')
      ->where('created_at', '<', Carbon::now()->startOfDay())
      ->delete();
  }
}