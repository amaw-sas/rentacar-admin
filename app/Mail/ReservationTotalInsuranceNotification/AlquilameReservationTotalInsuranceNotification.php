<?php

namespace App\Mail\ReservationTotalInsuranceNotification;

use App\Models\Reservation;
use Illuminate\Mail\Mailables\Content;

class AlquilameReservationTotalInsuranceNotification extends ReservationTotalInsuranceNotification
{
    public $markdown = 'mail.total_insurance_notification.alquilame';

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        parent::__construct($reservation);
        $email = config('mail.mailers.alquilame.username');
        $this->from($email, "Alquilame");
    }

}
