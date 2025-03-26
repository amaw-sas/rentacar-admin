<?php

namespace App\Mail\ReservationTotalInsuranceNotification;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationTotalInsuranceNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reservation;
    public $markdown = 'mail.total_insurance_notification.total-insurance-notification';

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;

        $toEmail = config('localiza.reservationEmailAddress');

        $this->to($toEmail);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificación de reserva con seguro total',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: $this->markdown,
            with: [
                'reserve_code'  => $this->reservation->reserve_code,
            ]
        );
    }
}
