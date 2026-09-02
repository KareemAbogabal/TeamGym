<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Front\ImgInBody;
use App\Models\Back\SettingCompany;
use App\Models\Back\Employee;
use App\Models\Back\Lineage;
use App\Models\Back\IncomeStatement;
use App\Models\Back\History;
use App\Models\Back\Imports;
use App\Models\Back\Payment;
use App\Models\Back\CustomerRequests;
use App\Models\Back\RequestsPayment;
use App\Models\Back\PaymentRegistry;
use App\Traits\IncomeStatements;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

/**
 * End-of-year report generation + archival formerly executed on page render
 * (view composer). Runs once per year off the request path, guarded by a
 * cache lock so repeated runs are idempotent.
 */
class GenerateYearlyCompanyReport extends Command {
  use IncomeStatements;

  protected $signature = 'teamgym:company-yearly-report';
  protected $description = 'Generate and email the end-of-year company report and archive consumed records';

  public function handle(): int {
    $now = Carbon::now('Africa/Cairo');
    $month = $now->format('m');
    $hour = $now->format('g A');
    $day = $now->format('d');
    $year = $now->format('Y');
    $lockKey = "yearly_report_done_{$year}";

    if ($month !== "12" || (int)$hour < 6 || (int)$day !== 31) {
      $this->info('Not end-of-year window. Skipping.');
      return Command::SUCCESS;
    }

    if (Cache::has($lockKey)) {
      $this->info('Report already generated this year.');
      return Command::SUCCESS;
    }

    $this->info('Generating end-of-year report for ' . $year . '...');
    Cache::put($lockKey, true, now()->addYear());

    try {
      $total = 90000;
      $revenues = $this->stateIncomeStatement("revenues");
      $expenses = $this->stateIncomeStatement("expenses");
      $histories = History::all();
      $incomeStatement = IncomeStatement::all();
      $imports = Imports::all();
      $supplements = Payment::where("type", "supplement")->whereColumn('amount', '=', 'paid')->with(['client', 'employee'])->get();
      $systems = Payment::where("type", "system")->whereColumn('amount', '=', 'paid')->with(['client', 'employee'])->get();
      $supplement = Payment::where("type", "supplement")->whereColumn('amount', '=', 'paid')->sum("amount");
      $system = Payment::where("type", "system")->whereColumn('amount', '=', 'paid')->sum("amount");
      $paymentIds = Payment::whereColumn('amount', '=', 'paid')->pluck('code');
      $requestCodes = Payment::whereColumn('amount', '=', 'paid')->pluck('code_request_payment');
      $paymentRegistry = PaymentRegistry::with('payments')->get();

      date_default_timezone_set("Africa/Cairo");
      $d = date_create();
      $time = date_format($d, "Y-m-j_g-i_A");
      $dataPage = [
        "userName" => "Team Gym",
        "description" => "Merry Christmas, this is a report of what happened during the year. Happy celebration.",
      ];
      $data = [
        "revenues" => $revenues,
        "expenses" => $expenses,
        "histories" => $histories,
        "incomeStatement" => $incomeStatement,
        "imports" => $imports,
        "supplement" => $supplement,
        "supplements" => $supplements,
        "total" => $total,
        "system" => $system,
        "systems" => $systems,
        "paymentRegistry" => $paymentRegistry,
      ];
      $employees = Employee::where("job_role", "admin")->orWhere("job_role", "Admin")->get();
      $dataForPdf = array_merge($data, ['for_pdf' => true]);
      $pdf = PDF::loadView('Mail.report', $dataForPdf)->setPaper('A4', 'portrait');
      foreach ($employees as $e) {
        Mail::send('Mail.pageReport', $dataPage, function ($message) use ($e, $pdf) {
          $message->to($e->email)->subject('Team Gym Yearly Report')
            ->attachData($pdf->output(), 'report_december.pdf', ['mime' => 'application/pdf']);
        });
      }

      History::query()->delete();
      Lineage::query()->delete();
      IncomeStatement::query()->delete();
      Imports::query()->delete();
      CustomerRequests::whereIn('code_payment', $paymentIds)->delete();
      PaymentRegistry::whereIn('code_payments', $paymentIds)->delete();
      Payment::whereColumn('amount', '=', 'paid')->delete();
      RequestsPayment::whereIn('code', $requestCodes)->delete();
    } catch (\Throwable $e) {
      Log::error('yearly company report failed: ' . $e->getMessage());
      return Command::FAILURE;
    }

    $this->info('Yearly company report complete.');
    return Command::SUCCESS;
  }
}