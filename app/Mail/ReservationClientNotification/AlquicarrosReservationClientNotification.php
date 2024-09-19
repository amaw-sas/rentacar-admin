<?php

namespace App\Mail\ReservationClientNotification;

use App\Models\Reservation;

class AlquicarrosReservationClientNotification extends ReservationClientNotification {

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
        return $this->markdown('mail.reservation_client_notification.alquicarros', [
            'reserva' => $this->reservation,
        ]);
    }
}
