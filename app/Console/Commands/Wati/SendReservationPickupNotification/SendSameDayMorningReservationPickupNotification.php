<?php

namespace App\Console\Commands\Wati\SendReservationPickupNotification;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;

class SendSameDayMorningReservationPickupNotification extends SendReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wati:send-same-day-morning-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a same day morning (14:00 same day to 02:00 tomorrow reservations) reservation pickup notification to the user via WhatsApp using Wati API';

    protected function getBaseQuery(): Builder
    {
        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        $startHour = "14:00";
        $endHour = "02:00";

        $initDatetime = $today . ' ' . $startHour;
        $endDatetime = $tomorrow . ' ' . $endHour;

        return Reservation::whereRaw(
            "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i') BETWEEN ? AND ?",
            [$initDatetime, $endDatetime]
        );

    }

    protected function getLogPrefix(): string
    {
        return 'Same Day Morning';
    }

    protected function getTemplateName(): string
    {
        return 'recordatorio_recogida_mismo_dia';
    }

    protected function getBaseBroadcastName(): string
    {
        return 'R Mañana';
    }

}
