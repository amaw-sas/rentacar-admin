<?php

namespace App\Mail\ReservationClientNotification\Failed;

use App\Models\Reservation;
use App\Http\Resources\ReservationEmailPreviewResource;

class AlquicarrosFailedReservationClientNotification extends FailedReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.failed.alquicarros";
    public $emailFromConfig = "mail.mailers.alquicarros.username";
    public $emailFromName = "Alquicarros";

}
