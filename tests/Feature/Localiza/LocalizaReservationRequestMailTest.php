<?php

namespace Tests\Feature\Localiza;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Mail\LocalizaReservationRequest;
use App\Mail\AlquilatucarroReservationRequest;
use App\Mail\AlquilameReservationRequest;
use App\Mail\AlquicarrosReservationRequest;
use App\Models\Reservation;

class LocalizaReservationRequestMailTest extends TestCase
{
    use RefreshDatabase;

    #[Group("localiza_reservation_request")]
    #[Group("localiza")]
    #[Test]
    public function send_a_email_to_localiza_reservation_desk(): void {
        $reservation = Reservation::factory()->create();

        $mail = new LocalizaReservationRequest($reservation);
        $mail->assertSeeInHtml($reservation->fullname);
        $mail->assertSeeInHtml($reservation->identification_type);
        $mail->assertSeeInHtml($reservation->identification);
        $mail->assertSeeInHtml($reservation->phone);
        $mail->assertSeeInHtml($reservation->email);
        $mail->assertSeeInHtml($reservation->category);
        $mail->assertSeeInHtml($reservation->formattedPickupPlace());
        $mail->assertSeeInHtml($reservation->formattedReturnPlace());
        $mail->assertSeeInHtml($reservation->formattedPickupDate());
        $mail->assertSeeInHtml($reservation->formattedReturnDate());
        $mail->assertSeeInHtml($reservation->formattedPickupHour());
        $mail->assertSeeInHtml($reservation->formattedReturnHour());


        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->phone);
        $mail->assertSeeInText($reservation->email);
        $mail->assertSeeInText($reservation->category);
        $mail->assertSeeInText($reservation->formattedPickupPlace());
        $mail->assertSeeInText($reservation->formattedReturnPlace());
        $mail->assertSeeInText($reservation->formattedPickupDate());
        $mail->assertSeeInText($reservation->formattedReturnDate());
        $mail->assertSeeInText($reservation->formattedPickupHour());
        $mail->assertSeeInText($reservation->formattedReturnHour());

    }

    #[Group("localiza_reservation_request")]
    #[Group("localiza")]
    #[Test]
    public function render_email_from_alquilatucarro_and_check_data(): void {
        $reservation = Reservation::factory()->create();

        $mail = new AlquilatucarroReservationRequest($reservation);
        $mail->assertSeeInHtml($reservation->fullname);
        $mail->assertSeeInHtml($reservation->identification_type);
        $mail->assertSeeInHtml($reservation->identification);
        $mail->assertSeeInHtml($reservation->phone);
        $mail->assertSeeInHtml($reservation->email);
        $mail->assertSeeInHtml($reservation->category);
        $mail->assertSeeInHtml($reservation->formattedPickupPlace());
        $mail->assertSeeInHtml($reservation->formattedReturnPlace());
        $mail->assertSeeInHtml($reservation->formattedPickupDate());
        $mail->assertSeeInHtml($reservation->formattedReturnDate());
        $mail->assertSeeInHtml($reservation->formattedPickupHour());
        $mail->assertSeeInHtml($reservation->formattedReturnHour());

        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->phone);
        $mail->assertSeeInText($reservation->email);
        $mail->assertSeeInText($reservation->category);
        $mail->assertSeeInText($reservation->formattedPickupPlace());
        $mail->assertSeeInText($reservation->formattedReturnPlace());
        $mail->assertSeeInText($reservation->formattedPickupDate());
        $mail->assertSeeInText($reservation->formattedReturnDate());
        $mail->assertSeeInText($reservation->formattedPickupHour());
        $mail->assertSeeInText($reservation->formattedReturnHour());

    }

    #[Group("localiza_reservation_request")]
    #[Group("localiza")]
    #[Test]
    public function render_email_from_alquilame_and_check_data(): void {
        $reservation = Reservation::factory()->create();

        $mail = new AlquilameReservationRequest($reservation);
        $mail->assertSeeInHtml($reservation->fullname);
        $mail->assertSeeInHtml($reservation->identification_type);
        $mail->assertSeeInHtml($reservation->identification);
        $mail->assertSeeInHtml($reservation->phone);
        $mail->assertSeeInHtml($reservation->email);
        $mail->assertSeeInHtml($reservation->category);
        $mail->assertSeeInHtml($reservation->formattedPickupPlace());
        $mail->assertSeeInHtml($reservation->formattedReturnPlace());
        $mail->assertSeeInHtml($reservation->formattedPickupDate());
        $mail->assertSeeInHtml($reservation->formattedReturnDate());
        $mail->assertSeeInHtml($reservation->formattedPickupHour());
        $mail->assertSeeInHtml($reservation->formattedReturnHour());

        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->phone);
        $mail->assertSeeInText($reservation->email);
        $mail->assertSeeInText($reservation->category);
        $mail->assertSeeInText($reservation->formattedPickupPlace());
        $mail->assertSeeInText($reservation->formattedReturnPlace());
        $mail->assertSeeInText($reservation->formattedPickupDate());
        $mail->assertSeeInText($reservation->formattedReturnDate());
        $mail->assertSeeInText($reservation->formattedPickupHour());
        $mail->assertSeeInText($reservation->formattedReturnHour());

    }

    #[Group("localiza_reservation_request")]
    #[Group("localiza")]
    #[Test]
    public function render_email_from_alquicarros_and_check_data(): void {
        $reservation = Reservation::factory()->create();

        $mail = new AlquicarrosReservationRequest($reservation);
        $mail->assertSeeInHtml($reservation->fullname);
        $mail->assertSeeInHtml($reservation->identification_type);
        $mail->assertSeeInHtml($reservation->identification);
        $mail->assertSeeInHtml($reservation->phone);
        $mail->assertSeeInHtml($reservation->email);
        $mail->assertSeeInHtml($reservation->category);
        $mail->assertSeeInHtml($reservation->formattedPickupPlace());
        $mail->assertSeeInHtml($reservation->formattedReturnPlace());
        $mail->assertSeeInHtml($reservation->formattedPickupDate());
        $mail->assertSeeInHtml($reservation->formattedReturnDate());
        $mail->assertSeeInHtml($reservation->formattedPickupHour());
        $mail->assertSeeInHtml($reservation->formattedReturnHour());

        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->phone);
        $mail->assertSeeInText($reservation->email);
        $mail->assertSeeInText($reservation->category);
        $mail->assertSeeInText($reservation->formattedPickupPlace());
        $mail->assertSeeInText($reservation->formattedReturnPlace());
        $mail->assertSeeInText($reservation->formattedPickupDate());
        $mail->assertSeeInText($reservation->formattedReturnDate());
        $mail->assertSeeInText($reservation->formattedPickupHour());
        $mail->assertSeeInText($reservation->formattedReturnHour());

    }
}
