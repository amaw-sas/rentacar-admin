<?php

namespace App\Console\Commands\Ghl\SendReservationPickupNotification;

use App\Enums\ReservationStatus;
use App\Jobs\SendGhlReminderNotificationJob;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Send GHL WhatsApp reminder for same-day pickups (7:01-11:00 window).
 *
 * Overrides handle() to use datetime-based filtering for precise timing.
 */
class SendSameDayReservationPickupNotification extends SendReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghl:send-same-day-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send GHL WhatsApp reminder for same-day pickups (7:01-11:00 window)';

    /**
     * Get the base query for reservations.
     * Not used directly since handle() is overridden.
     */
    protected function getBaseQuery(): Builder
    {
        return Reservation::query();
    }

    /**
     * Get the log prefix for this command.
     */
    protected function getLogPrefix(): string
    {
        return 'GHL Same-Day Pickup Reminder';
    }

    /**
     * Get the reminder type for message selection.
     */
    protected function getReminderType(): string
    {
        return SendGhlReminderNotificationJob::TYPE_SAME_DAY;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $logPrefix = $this->getLogPrefix();
        Log::info("{$logPrefix}: Starting");

        $today = now()->format('Y-m-d');
        $startTime = now()->setTime(7, 1, 0)->format('Y-m-d H:i:s');
        $endTime = now()->setTime(11, 0, 0)->format('Y-m-d H:i:s');

        $reservations = $this->buildDateTimeQuery($today, $startTime, $endTime)
            ->where('status', ReservationStatus::Reservado)
            ->whereNotNull('reserve_code')
            ->whereNotNull('phone')
            ->get();

        $count = $reservations->count();
        Log::info("{$logPrefix}: Found {$count} reservations");

        if ($count === 0) {
            $this->info("{$logPrefix}: No reservations to notify");
            return;
        }

        $dispatched = 0;
        foreach ($reservations as $reservation) {
            SendGhlReminderNotificationJob::dispatch(
                $reservation,
                $this->getReminderType(),
                $logPrefix
            );
            $dispatched++;
        }

        Log::info("{$logPrefix}: Dispatched {$dispatched} notification jobs");
        $this->info("{$logPrefix}: Dispatched {$dispatched} notification jobs");
    }

    /**
     * Build query with datetime filtering.
     */
    protected function buildDateTimeQuery(string $date, string $startTime, string $endTime): Builder
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: Use datetime() function
            return Reservation::whereDate('pickup_date', $date)
                ->whereRaw(
                    "datetime(pickup_date || ' ' || pickup_hour) BETWEEN ? AND ?",
                    [$startTime, $endTime]
                );
        }

        // MySQL: Use STR_TO_DATE
        return Reservation::whereDate('pickup_date', $date)
            ->whereRaw(
                "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i:%s') BETWEEN ? AND ?",
                [$startTime, $endTime]
            );
    }
}
