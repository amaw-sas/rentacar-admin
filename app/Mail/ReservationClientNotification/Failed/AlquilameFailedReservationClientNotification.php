<?php

namespace App\Mail\ReservationClientNotification\Failed;

use App\Models\Reservation;
use App\Http\Resources\ReservationEmailPreviewResource;

class AlquilameFailedReservationClientNotification extends FailedReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.failed.alquilame";
    public $emailFromConfig = "mail.mailers.alquilame.username";
    public $emailFromName = "Alquilame";

}
