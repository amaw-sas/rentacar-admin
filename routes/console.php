<?php

use App\Console\Commands\CheckPendingReservationStatus;
use App\Console\Commands\SendSameDayMorningReservationPickupNotification;
use App\Console\Commands\SendSameDayLateReservationPickupNotification;
use App\Console\Commands\SendThreeDaysReservationPickupNotification;
use App\Console\Commands\SendWeekReservationPickupNotification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('model:prune')->daily();

Schedule::command(CheckPendingReservationStatus::class)->everyThirtyMinutes();

$wati_endpoint = config('wati.endpoint');
Schedule::timezone('America/Bogota')
    ->group(function() use ($wati_endpoint) {
        Schedule::command(SendSameDayMorningReservationPickupNotification::class)->dailyAt('08:00')->pingBefore($wati_endpoint);
        Schedule::command(SendSameDayLateReservationPickupNotification::class)->dailyAt('20:00')->pingBefore($wati_endpoint);
        Schedule::command(SendThreeDaysReservationPickupNotification::class)->dailyAt('09:00')->pingBefore($wati_endpoint);
        Schedule::command(SendWeekReservationPickupNotification::class)->dailyAt('10:00')->pingBefore($wati_endpoint);
    });
