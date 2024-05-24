<?php

namespace App\Mail;

use App\Models\Reservation;

class AlquilatucarroReservationRequest extends LocalizaReservationRequest
{

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, bool $total_insurance = false)
    {
        parent::__construct($reservation, $total_insurance);
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
        return $this->markdown('mail.alquilatucarro-reservation-request', [
            'reserva' => $this->reservation,
            'total_insurance' => $this->totalInsurance,
        ]);
    }
}
