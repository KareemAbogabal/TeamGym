<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Models\Front\Client;
use App\Models\Front\LineageInBody;
use App\Models\Front\ImgInBody;
use App\Models\Back\Payment;
use Carbon\Carbon;

/**
 * Yearly + monthly InBody / subscription maintenance formerly executed on
 * page render (view composer). Now runs off the request path so that opening
 * a page can never trigger destructive archival for any client.
 */
class ClientYearlyMaintenance extends Command {
  protected $signature = 'teamgym:client-yearly-maintenance';
  protected $description = 'Archive previous-year InBody data and roll overdue monthly system payments';

  public function handle(): int {
    $now = Carbon::now('Africa/Cairo');
    $day = (int) $now->format('d');
    $month = mb_strtolower($now->format('F'));
    $year = (int) $now->format('Y');

    $monthsArr = [
      'january','february','march','april','may','june',
      'july','august','september','october','november','december'
    ];
    $metricsArr = [
      'weight', 'BMI', 'PBF', 'SMM', 'KCAL', 'water', 'fat_mass', 'protein',
      'left_arm_lean', 'right_arm_lean', 'left_leg_lean', 'right_leg_lean',
      'left_arm_fat', 'right_arm_fat', 'left_leg_fat', 'right_leg_fat'
    ];

    $clients = Client::all();
    foreach ($clients as $client) {
      try {
        $lastYearInbody = (int) ($client->year_inbody ?? $year);
        if ($lastYearInbody < $year) {
          $lineageInBody = LineageInBody::where("code", $client->code);
          $imgInBody = ImgInBody::where("code", $client->code)->first();
          $rows = (clone $lineageInBody)->get()->keyBy('name');
          $archive = [];
          foreach ($metricsArr as $metric) {
            $row = $rows->get($metric);
            foreach ($monthsArr as $m) {
              $archive[$metric][$m] = $row ? ($row->{$m} ?? null) : null;
            }
          }
          $imgPath = null;
          if ($imgInBody && File::exists(public_path("Images/inBody/" . $imgInBody->img))) {
            $imgPath = public_path("Images/inBody/" . $imgInBody->img);
          }
          try {
            Mail::send('Mail.yearlyInbody', [
              'client' => $client,
              'archive' => $archive,
              'year' => $lastYearInbody,
            ], function ($message) use ($client, $imgPath, $lastYearInbody) {
              $message->to($client->email)->subject('Your Team Gym InBody Yearly Report — ' . $lastYearInbody);
              if ($imgPath) {
                $message->attach($imgPath, ['as' => 'inbody_' . $lastYearInbody . '.jpg']);
              }
            });
          } catch (\Throwable $e) {
            Log::error('yearly inbody archive mail failed: ' . $e->getMessage());
          }
          $lineageInBody->delete();
          if ($imgInBody) {
            if (File::exists(public_path("Images/inBody/" . $imgInBody->img))) {
              File::delete(public_path("Images/inBody/" . $imgInBody->img));
            }
            $imgInBody->delete();
          }
          $client->year_inbody = $year;
          $client->save();
        }

        $system = Payment::where("code_client", $client->code)
          ->where("type", "system")
          ->whereColumn('amount', '=', 'paid')
          ->first();
        if ($system && $day > 1 && $system->amount == $system->paid && $system->paymonth !== $month) {
          $system->paid = 0;
          $system->save();
        }
      } catch (\Throwable $e) {
        Log::error('client yearly maintenance failed for ' . $client->code . ': ' . $e->getMessage());
      }
    }

    $this->info('Client yearly maintenance complete.');
    return Command::SUCCESS;
  }
}