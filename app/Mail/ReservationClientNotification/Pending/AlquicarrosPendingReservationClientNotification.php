<?php

namespace App\Mail\ReservationClientNotification\Pending;

use App\Models\Reservation;
use App\Http\Resources\ReservationEmailPreviewResource;

class AlquicarrosPendingReservationClientNotification extends PendingReservationClientNotification {

    public $markdown = "mail.reservation_client_notification.pending.alquicarros";
    public $emailFromConfig = "mail.mailers.alquicarros.username";
    public $emailFromName = "Alquicarros";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        parent::__construct($reservation);
        $email = config('mail.mailers.alquicarros.username');
        $this->from($email, "Alquicarros");

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reservationResource = (new ReservationEmailPreviewResource($this->reservation))->toArray(request());
        $reservation = array_merge($reservationResource, ['reserva' => $this->reservation]);

        return $this->markdown('mail.reservation_client_notification.pending.alquicarros', $reservation);
    }
}
