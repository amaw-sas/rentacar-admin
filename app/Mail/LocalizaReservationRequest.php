<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\models\Reservation;

class LocalizaReservationRequest extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reservation;
    public $totalInsurance;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, bool $total_insurance = false)
    {
        $this->reservation = $reservation;
        $this->totalInsurance = $total_insurance;

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
        return $this->markdown('mail.localiza-reservation-request', [
            'reserva' => $this->reservation,
            'total_insurance' => $this->totalInsurance,
        ]);
    }
}
