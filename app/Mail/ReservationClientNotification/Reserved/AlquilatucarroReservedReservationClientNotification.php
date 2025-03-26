<?php

namespace App\Mail\ReservationClientNotification\Reserved;

use App\Http\Resources\ReservationEmailPreviewResource;
use App\Models\Reservation;

class AlquilatucarroReservedReservationClientNotification extends ReservedReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.reserved.alquilatucarro";
    public $emailFromConfig = "mail.mailers.alquilatucarro.username";
    public $emailFromName = "Alquilatucarro";

}
