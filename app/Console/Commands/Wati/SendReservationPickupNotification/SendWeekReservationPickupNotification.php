<?php

namespace App\Console\Commands\Wati\SendReservationPickupNotification;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;

class SendWeekReservationPickupNotification extends SendReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wati:send-week-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a week before reservation pickup notification to the user via WhatsApp using Wati API';

    protected function getBaseQuery(): Builder
    {
        $weeklater = now()->addWeek()->format('Y-m-d');
        return Reservation::whereDate('pickup_date', $weeklater);
    }

    protected function getLogPrefix(): string
    {
        return 'Week';
    }

    protected function getTemplateName(): string
    {
        return 'recordatorio_recogida';
    }

    protected function getBaseBroadcastName(): string
    {
        return 'R Semana';
    }

}
