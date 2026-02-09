<?php

namespace App\Console\Commands\Ghl\SendPostReservationPickupNotification;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Send GHL WhatsApp post-pickup message for late pickups.
 *
 * Targets reservations picked up between today 05:01 and today 17:00.
 */
class SendLatePostReservationPickupNotification extends SendPostReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghl:send-late-post-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send GHL WhatsApp post-pickup message for pickups between today 05:01 and 17:00';

    /**
     * Get the base query for reservations.
     */
    protected function getBaseQuery(): Builder
    {
        $startTime = now()->setTime(5, 1, 0)->format('Y-m-d H:i:s');
        $endTime = now()->setTime(17, 0, 0)->format('Y-m-d H:i:s');

        return $this->buildDateTimeQuery($startTime, $endTime);
    }

    /**
     * Get the log prefix for this command.
     */
    protected function getLogPrefix(): string
    {
        return 'GHL Late Post-Pickup Reminder';
    }

    /**
     * Build query with datetime filtering.
     */
    protected function buildDateTimeQuery(string $startTime, string $endTime): Builder
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            return Reservation::whereRaw(
                "datetime(pickup_date || ' ' || pickup_hour) BETWEEN ? AND ?",
                [$startTime, $endTime]
            );
        }

        return Reservation::whereRaw(
            "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i:%s') BETWEEN ? AND ?",
            [$startTime, $endTime]
        );
    }
}
