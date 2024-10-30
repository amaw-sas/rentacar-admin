<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Mail\ReservationRequest\AlquicarrosReservationRequest;
use App\Mail\ReservationRequest\AlquilameReservationRequest;
use App\Mail\ReservationRequest\AlquilatucarroReservationRequest;
use App\Models\Reservation;

class SendLocalizaReservationRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $franchisesEmails = [
        'alquilatucarro' => AlquilatucarroReservationRequest::class,
        'alquilame' => AlquilameReservationRequest::class,
        'alquicarros' => AlquicarrosReservationRequest::class,
    ];

    public Reservation $reservation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $franchise = $this->reservation->franchiseObject->name;

        if(!is_null($franchise)){
            try {
                // send to localiza a notification
                $franchiseEmail = $this->franchisesEmails[$franchise];

                Mail::mailer($franchise)
                ->send(new $franchiseEmail($this->reservation));

                Log::info("An email was send to localiza board", $this->reservation->toArray());
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                \Sentry\captureException($th);
            }
        }
        else Log::error("No franchise associated", $this->reservation->toArray());
    }
}
