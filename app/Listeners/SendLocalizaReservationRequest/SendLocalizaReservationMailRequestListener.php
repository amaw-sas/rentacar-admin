<?php

namespace App\Listeners\SendLocalizaReservationRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Events\NewMonthlyReservationEvent;
use App\Mail\ReservationRequest\AlquicarrosReservationRequest;
use App\Mail\ReservationRequest\AlquilameReservationRequest;
use App\Mail\ReservationRequest\AlquilatucarroReservationRequest;
use App\Models\Reservation;

class SendLocalizaReservationMailRequestListener extends SendLocalizaReservationRequestListener
{
    public $franchisesEmails = [
        'alquilatucarro' => AlquilatucarroReservationRequest::class,
        'alquilame' => AlquilameReservationRequest::class,
        'alquicarros' => AlquicarrosReservationRequest::class,
    ];

    public function handle(NewMonthlyReservationEvent $event): void
    {
        $reservation = $event->reservation;

        $franchise = $reservation->franchiseObject->name;

        if(!is_null($franchise)){
            try {
                // send to localiza a notification
                $franchiseEmail = $this->franchisesEmails[$franchise];

                Mail::mailer($franchise)
                ->send(new $franchiseEmail($reservation));

                Log::info("An email was send to localiza board", $reservation->toArray());
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                \Sentry\captureException($th);
            }
        }
        else Log::error("No franchise associated", $reservation->toArray());
    }
}
