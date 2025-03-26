<?php

namespace App\Mail\ReservationClientNotification\Failed;

use App\Http\Resources\ReservationEmailPreviewResource;
use App\Models\Reservation;

class AlquilatucarroFailedReservationClientNotification extends FailedReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.failed.alquilatucarro";
    public $emailFromConfig = "mail.mailers.alquilatucarro.username";
    public $emailFromName = "Alquilatucarro";

}
