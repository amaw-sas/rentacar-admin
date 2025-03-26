<?php

namespace App\Mail\ReservationClientNotification\Reserved;

use App\Models\Reservation;
use App\Http\Resources\ReservationEmailPreviewResource;

class AlquicarrosReservedReservationClientNotification extends ReservedReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.reserved.alquicarros";
    public $emailFromConfig = "mail.mailers.alquicarros.username";
    public $emailFromName = "Alquicarros";

}
