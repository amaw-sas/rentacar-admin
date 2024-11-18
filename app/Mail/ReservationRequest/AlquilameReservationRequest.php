<?php

namespace App\Mail\ReservationRequest;

use App\Models\Reservation;

class AlquilameReservationRequest extends LocalizaReservationRequest
{

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
        return $this->markdown('mail.reservation_request.alquilame', [
            'reserva' => $this->reservation,
        ]);
    }
}
