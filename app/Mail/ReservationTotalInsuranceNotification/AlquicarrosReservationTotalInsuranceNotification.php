<?php

namespace App\Mail\ReservationTotalInsuranceNotification;

use App\Models\Reservation;
use Illuminate\Mail\Mailables\Content;

class AlquicarrosReservationTotalInsuranceNotification extends ReservationTotalInsuranceNotification
{
    public $markdown = 'mail.total_insurance_notification.alquicarros';

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        parent::__construct($reservation);
        $email = config('mail.mailers.alquicarros.username');
        $this->from($email, "Alquicarros");
    }
}
