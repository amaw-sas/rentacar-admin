<?php

namespace App\Mail\ReservationRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\models\Reservation;

class LocalizaReservationRequest extends Mailable implements ShouldQueue
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

        $toEmail = $this->reservation->email; // client email
        $bccEmail = config("localiza.reservationEmailAddress"); // car provider email

        $this->to($toEmail);
        $this->bcc($bccEmail);
        $this->subject("Solicitud de reserva en proceso");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.reservation_request.localiza', [
            'reserva' => $this->reservation,
        ]);
    }
}
