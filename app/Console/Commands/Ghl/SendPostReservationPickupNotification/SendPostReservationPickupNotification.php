<?php

namespace App\Console\Commands\Ghl\SendPostReservationPickupNotification;

use App\Enums\ReservationStatus;
use App\Jobs\SendGhlReminderNotificationJob;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Base class for GHL post-pickup notification commands.
 *
 * Uses Template Method pattern - subclasses implement:
 * - getBaseQuery(): Define the reservation query with datetime filtering
 * - getLogPrefix(): Define the log prefix
 */
abstract class SendPostReservationPickupNotification extends Command
{
    /**
     * Get the base query for reservations.
     */
    abstract protected function getBaseQuery(): Builder;

    /**
     * Get the log prefix for this command.
     */
    abstract protected function getLogPrefix(): string;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $logPrefix = $this->getLogPrefix();
        Log::info("{$logPrefix}: Starting");

        $reservations = $this->getBaseQuery()
            ->where('status', ReservationStatus::Reservado)
            ->whereNotNull('reserve_code')
            ->where('reserve_code', '!=', '')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
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
                SendGhlReminderNotificationJob::TYPE_POST_PICKUP,
                $logPrefix
            );
            $dispatched++;
        }

        Log::info("{$logPrefix}: Dispatched {$dispatched} notification jobs");
        $this->info("{$logPrefix}: Dispatched {$dispatched} notification jobs");
    }
}
