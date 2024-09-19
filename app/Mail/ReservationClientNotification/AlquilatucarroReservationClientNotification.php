<?php

namespace App\Mail\ReservationClientNotification;

use App\Models\Reservation;

class AlquilatucarroReservationClientNotification extends ReservationClientNotification {

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
        return $this->markdown('mail.reservation_client_notification.alquilatucarro', [
            'reserva' => $this->reservation,
        ]);
    }
}
