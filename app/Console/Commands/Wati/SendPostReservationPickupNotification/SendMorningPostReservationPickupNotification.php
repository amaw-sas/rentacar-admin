<?php

namespace App\Console\Commands\Wati\SendPostReservationPickupNotification;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;

class SendMorningPostReservationPickupNotification extends SendPostReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wati:send-morning-post-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a morning (17:01 yesterday to 05:00 today reservations) post reservation pickup notification to the user via WhatsApp using Wati API';

    protected function getBaseQuery(): Builder
    {
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');
        $startHour = "17:01";
        $endHour = "05:00";

        $initDatetime = $yesterday . ' ' . $startHour;
        $endDatetime = $today . ' ' . $endHour;

        return Reservation::whereRaw(
            "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i') BETWEEN ? AND ?",
            [$initDatetime, $endDatetime]
        );
    }

    protected function getLogPrefix(): string
    {
        return 'Morning';
    }

    protected function getTemplateName(): string
    {
        return 'post_recordatorio_recogida';
    }

    protected function getBaseBroadcastName(): string
    {
        return 'PR Mañana';
    }

}
