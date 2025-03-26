<?php

namespace App\Mail\ReservationClientNotification\Pending;

use App\Models\Reservation;
use App\Http\Resources\ReservationEmailPreviewResource;

class AlquilamePendingReservationClientNotification extends PendingReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.pending.alquilame";
    public $emailFromConfig = "mail.mailers.alquilame.username";
    public $emailFromName = "Alquilame";

}
