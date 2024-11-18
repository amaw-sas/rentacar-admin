<?php

namespace App\Mail\ReservationClientNotification\Failed;

use App\Http\Resources\ReservationEmailPreviewResource;
use App\Models\Reservation;

class AlquilatucarroFailedReservationClientNotification extends FailedReservationClientNotification {

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        parent::__construct($reservation);
        $email = config('mail.mailers.alquilatucarro.username');
        $this->from($email, "Alquilatucarro");

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

        return $this->markdown('mail.reservation_client_notification.failed.alquilatucarro', $reservation);
    }
}
