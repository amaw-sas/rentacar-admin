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

use App\Models\Reservation;
use App\Enums\ReservationStatus;
use App\Mail\ReservationClientNotification\Reserved\AlquilatucarroReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Reserved\AlquilameReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Reserved\AlquicarrosReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquilatucarroPendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquilamePendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquicarrosPendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquilatucarroFailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquilameFailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquicarrosFailedReservationClientNotification;


class SendClientReservationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $franchisesEmails = [
        'alquilatucarro' => [
            ReservationStatus::Reservado->value  =>  AlquilatucarroReservedReservationClientNotification::class,
            ReservationStatus::Pendiente->value  =>  AlquilatucarroPendingReservationClientNotification::class,
            ReservationStatus::SinDisponibilidad->value  =>  AlquilatucarroFailedReservationClientNotification::class,
        ],
        'alquilame' => [
            ReservationStatus::Reservado->value  =>  AlquilameReservedReservationClientNotification::class,
            ReservationStatus::Pendiente->value  =>  AlquilamePendingReservationClientNotification::class,
            ReservationStatus::SinDisponibilidad->value  =>  AlquilameFailedReservationClientNotification::class,
        ],
        'alquicarros' => [
            ReservationStatus::Reservado->value  =>  AlquicarrosReservedReservationClientNotification::class,
            ReservationStatus::Pendiente->value  =>  AlquicarrosPendingReservationClientNotification::class,
            ReservationStatus::SinDisponibilidad->value  =>  AlquicarrosFailedReservationClientNotification::class,
        ],
    ];

    public $reservation;

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
                $reservationStatus = ReservationStatus::tryFrom($this->reservation->status);

                // send to client a notification
                $franchiseEmail = $this->franchisesEmails[$franchise][$reservationStatus->value];

                Mail::mailer($franchise)
                ->to($this->reservation->email)
                ->send(new $franchiseEmail($this->reservation));

                Log::info("An email was send to {$this->reservation->email}", $this->reservation->toArray());
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                \Sentry\captureException($th);
            }
        }
        else Log::error("No franchise associated", $this->reservation->toArray());
    }
}
