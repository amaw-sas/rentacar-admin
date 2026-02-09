<?php

namespace App\Console\Commands\Ghl\SendReservationPickupNotification;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;

/**
 * Send GHL WhatsApp reminder for reservations with pickup in 3 days.
 */
class SendThreeDaysReservationPickupNotification extends SendReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghl:send-three-days-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send GHL WhatsApp reminder for reservations with pickup in 3 days';

    /**
     * Get the base query for reservations.
     */
    protected function getBaseQuery(): Builder
    {
        return Reservation::whereDate('pickup_date', now()->addDays(3)->format('Y-m-d'));
    }

    /**
     * Get the log prefix for this command.
     */
    protected function getLogPrefix(): string
    {
        return 'GHL 3-Day Pickup Reminder';
    }
}
