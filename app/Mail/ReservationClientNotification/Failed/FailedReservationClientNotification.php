<?php

namespace App\Mail\ReservationClientNotification\Failed;

use App\Http\Resources\ReservationEmailPreviewResource;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FailedReservationClientNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reservation;
    public $markdown = 'mail.reservation_client_notification.failed.failed';
    public $emailFromConfig = "";
    public $emailFromName = "Rentacar";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
        $email = config($this->emailFromConfig);

        $this->to($this->reservation->email);
        $this->subject("Reserva Sin Disponibilidad");
        $this->from($email, $this->emailFromName);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reservationResource = (new ReservationEmailPreviewResource($this->reservation))->toArray(request());
        $reservation = array_merge($reservationResource, ['reserva' => $this->reservation]);

        return $this->markdown($this->markdown, $reservation);
    }
}
