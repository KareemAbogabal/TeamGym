<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\ClientYearlyMaintenance;
use App\Console\Commands\GenerateYearlyCompanyReport;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Archival / maintenance MUST run off the request path, never on page render.
Schedule::command(ClientYearlyMaintenance::class)->dailyAt('04:00');
Schedule::command(GenerateYearlyCompanyReport::class)->dailyAt('06:00');