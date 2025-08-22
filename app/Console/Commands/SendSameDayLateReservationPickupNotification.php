<?php

namespace App\Console\Commands;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;

class SendSameDayLateReservationPickupNotification extends SendReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wati:send-same-day-late-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a same day late (02:00 to 14:00 tomorrow reservations) reservation pickup notification to the user via WhatsApp using Wati API';

    protected function getBaseQuery(): Builder
    {
        $tomorrow = now()->addDay()->format('Y-m-d');
        $startHour = "02:00";
        $endHour = "14:00";

        $initDatetime = $tomorrow . ' ' . $startHour;
        $endDatetime = $tomorrow . ' ' . $endHour;

        return Reservation::whereRaw(
            "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i') BETWEEN ? AND ?",
            [$initDatetime, $endDatetime]
        );
    }

    protected function getLogPrefix(): string
    {
        return 'Same Day Late';
    }

    protected function getTemplateName(): string
    {
        return 'recordatorio_recogida_mismo_dia';
    }

    protected function getBaseBroadcastName(): string
    {
        return 'Notificación de Recogida de Vehículo Mismo Día Tarde';
    }

}
