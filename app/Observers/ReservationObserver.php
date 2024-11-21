<?php

namespace App\Observers;

use App\Enums\ReservationStatus;
use App\Jobs\SendClientReservationNotificationJob;
use App\Models\Reservation;

class ReservationObserver
{
    public array $triggerClientNotificationStatuses = [
        ReservationStatus::Reservado,
        ReservationStatus::Pendiente,
        ReservationStatus::SinDisponibilidad,
    ];

    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        if($reservation->wasChanged('status')){
            if(in_array($reservation->status, $this->triggerClientNotificationStatuses))
                dispatch(new SendClientReservationNotificationJob($reservation));
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "restored" event.
     */
    public function restored(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "force deleted" event.
     */
    public function forceDeleted(Reservation $reservation): void
    {
        //
    }
}
