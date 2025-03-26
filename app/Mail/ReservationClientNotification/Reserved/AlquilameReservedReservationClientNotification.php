<?php

namespace App\Mail\ReservationClientNotification\Reserved;

use App\Models\Reservation;
use App\Http\Resources\ReservationEmailPreviewResource;

class AlquilameReservedReservationClientNotification extends ReservedReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.reserved.alquilame";
    public $emailFromConfig = "mail.mailers.alquilame.username";
    public $emailFromName = "Alquilame";

}
