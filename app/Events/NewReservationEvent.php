<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Reservation;
use App\Enums\ReservationAPIStatus;


class NewReservationEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Reservation $reservation,
        public ReservationAPIStatus $reservationAPIStatus
    )
    {

    }


}
