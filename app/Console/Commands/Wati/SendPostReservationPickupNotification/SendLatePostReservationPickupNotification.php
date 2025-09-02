<?php

namespace App\Console\Commands\Wati\SendPostReservationPickupNotification;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;

class SendLatePostReservationPickupNotification extends SendPostReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wati:send-late-post-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a late (05:01 to 17:00 reservations) post reservation pickup notification to the user via WhatsApp using Wati API';

    protected function getBaseQuery(): Builder
    {
        $today = now()->format('Y-m-d');
        $startHour = "05:01";
        $endHour = "17:00";

        $initDatetime = $today . ' ' . $startHour;
        $endDatetime = $today . ' ' . $endHour;

        return Reservation::whereRaw(
            "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i') BETWEEN ? AND ?",
            [$initDatetime, $endDatetime]
        );
    }

    protected function getLogPrefix(): string
    {
        return 'Late';
    }

    protected function getTemplateName(): string
    {
        return 'post_recordatorio_recogida';
    }

    protected function getBaseBroadcastName(): string
    {
        return 'PR Tarde';
    }

}
