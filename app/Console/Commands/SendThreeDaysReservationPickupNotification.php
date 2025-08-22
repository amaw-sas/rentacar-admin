<?php

namespace App\Console\Commands;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;

class SendThreeDaysReservationPickupNotification extends SendReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wati:send-three-days-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a three days before reservation pickup notification to the user via WhatsApp using Wati API';

    protected function getBaseQuery(): Builder
    {
        $threedayslater = now()->addDays(3)->format('Y-m-d');

        return Reservation::whereDate('pickup_date', $threedayslater);
    }

    protected function getLogPrefix(): string
    {
        return 'Three Days';
    }

    protected function getTemplateName(): string
    {
        return 'recordatorio_recogida';
    }

    protected function getBaseBroadcastName(): string
    {
        return 'Notificación de Recogida de Vehículo Mismo Día Tarde';
    }

}
