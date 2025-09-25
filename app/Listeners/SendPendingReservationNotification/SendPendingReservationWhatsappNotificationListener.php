<?php

namespace App\Listeners\SendPendingReservationNotification;

use App\Events\SendClientReservationNotificationEvent;

class SendPendingReservationWhatsappNotificationListener  extends SendPendingReservationNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(SendClientReservationNotificationEvent $event): void
    {
        //
    }
}
