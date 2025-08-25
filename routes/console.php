<?php

use App\Console\Commands\CheckPendingReservationStatus;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('model:prune')->daily();

Schedule::command(CheckPendingReservationStatus::class)->everyThirtyMinutes();

// wati commands
// reservation pickup notifications
Schedule::command('wati:send-same-day-late-reservation-pickup-notification')->dailyAt('08:00');
Schedule::command('wati:send-same-day-morning-reservation-pickup-notification')->dailyAt('20:00');
Schedule::command('wati:send-three-days-reservation-pickup-notification')->dailyAt('09:00');
Schedule::command('wati:send-week-reservation-pickup-notification')->dailyAt('10:00');
// post reservation pickup notifications
Schedule::command('wati:send-morning-post-reservation-pickup-notification')->dailyAt('08:00');
Schedule::command('wati:send-late-post-reservation-pickup-notification')->dailyAt('20:00');
