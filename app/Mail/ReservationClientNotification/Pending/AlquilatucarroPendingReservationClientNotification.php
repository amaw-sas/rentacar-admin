<?php

namespace App\Mail\ReservationClientNotification\Pending;

use App\Http\Resources\ReservationEmailPreviewResource;
use App\Models\Reservation;

class AlquilatucarroPendingReservationClientNotification extends PendingReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.pending.alquilatucarro";
    public $emailFromConfig = "mail.mailers.alquilatucarro.username";
    public $emailFromName = "Alquilatucarro";

}
