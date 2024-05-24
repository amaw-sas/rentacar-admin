<?php

namespace App\Mail;

use App\Models\Reservation;

class AlquicarrosReservationRequest extends LocalizaReservationRequest
{

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, bool $total_insurance = false)
    {
        parent::__construct($reservation, $total_insurance);
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
        return $this->markdown('mail.alquicarros-reservation-request', [
            'reserva' => $this->reservation,
            'total_insurance' => $this->totalInsurance,
        ]);
    }
}
