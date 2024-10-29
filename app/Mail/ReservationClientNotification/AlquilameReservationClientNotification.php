<?php

namespace App\Mail\ReservationClientNotification;

use App\Models\Reservation;
use App\Http\Resources\ReservationEmailPreviewResource;

class AlquilameReservationClientNotification extends ReservationClientNotification {

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        parent::__construct($reservation);
        $email = config('mail.mailers.alquilame.username');
        $this->from($email, "Alquilame");

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

        return $this->markdown('mail.reservation_client_notification.alquilame', $reservation);
    }
}
