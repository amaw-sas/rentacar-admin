<?php

namespace App\Mail\ReservationClientNotification;

use App\Http\Resources\ReservationEmailPreviewResource;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationClientNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reservation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;

        $this->to($this->reservation->email);
        $this->subject("Detalles de reserva");
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

        return $this->markdown('mail.reservation_client_notification.notification', $reservation);
    }
}
