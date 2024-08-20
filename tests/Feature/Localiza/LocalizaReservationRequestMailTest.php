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
        $mail->assertSeeInHtml($reservation->formatted_category);
        $mail->assertSeeInHtml($reservation->formatted_pickup_place);
        $mail->assertSeeInHtml($reservation->formatted_return_place);
        $mail->assertSeeInHtml($reservation->formatted_pickup_date);
        $mail->assertSeeInHtml($reservation->formatted_return_date);
        $mail->assertSeeInHtml($reservation->formatted_pickup_hour);
        $mail->assertSeeInHtml($reservation->formatted_return_hour);


        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->phone);
        $mail->assertSeeInText($reservation->email);
        $mail->assertSeeInText($reservation->formatted_category);
        $mail->assertSeeInText($reservation->formatted_pickup_place);
        $mail->assertSeeInText($reservation->formatted_return_place);
        $mail->assertSeeInText($reservation->formatted_pickup_date);
        $mail->assertSeeInText($reservation->formatted_return_date);
        $mail->assertSeeInText($reservation->formatted_pickup_hour);
        $mail->assertSeeInText($reservation->formatted_return_hour);

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
        $mail->assertSeeInHtml($reservation->formatted_category);
        $mail->assertSeeInHtml($reservation->formatted_pickup_place);
        $mail->assertSeeInHtml($reservation->formatted_return_place);
        $mail->assertSeeInHtml($reservation->formatted_pickup_date);
        $mail->assertSeeInHtml($reservation->formatted_return_date);
        $mail->assertSeeInHtml($reservation->formatted_pickup_hour);
        $mail->assertSeeInHtml($reservation->formatted_return_hour);

        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->phone);
        $mail->assertSeeInText($reservation->email);
        $mail->assertSeeInText($reservation->formatted_category);
        $mail->assertSeeInText($reservation->formatted_pickup_place);
        $mail->assertSeeInText($reservation->formatted_return_place);
        $mail->assertSeeInText($reservation->formatted_pickup_date);
        $mail->assertSeeInText($reservation->formatted_return_date);
        $mail->assertSeeInText($reservation->formatted_pickup_hour);
        $mail->assertSeeInText($reservation->formatted_return_hour);

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
        $mail->assertSeeInHtml($reservation->formatted_category);
        $mail->assertSeeInHtml($reservation->formatted_pickup_place);
        $mail->assertSeeInHtml($reservation->formatted_return_place);
        $mail->assertSeeInHtml($reservation->formatted_pickup_date);
        $mail->assertSeeInHtml($reservation->formatted_return_date);
        $mail->assertSeeInHtml($reservation->formatted_pickup_hour);
        $mail->assertSeeInHtml($reservation->formatted_return_hour);

        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->phone);
        $mail->assertSeeInText($reservation->email);
        $mail->assertSeeInText($reservation->formatted_category);
        $mail->assertSeeInText($reservation->formatted_pickup_place);
        $mail->assertSeeInText($reservation->formatted_return_place);
        $mail->assertSeeInText($reservation->formatted_pickup_date);
        $mail->assertSeeInText($reservation->formatted_return_date);
        $mail->assertSeeInText($reservation->formatted_pickup_hour);
        $mail->assertSeeInText($reservation->formatted_return_hour);

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
        $mail->assertSeeInHtml($reservation->formatted_category);
        $mail->assertSeeInHtml($reservation->formatted_pickup_place);
        $mail->assertSeeInHtml($reservation->formatted_return_place);
        $mail->assertSeeInHtml($reservation->formatted_pickup_date);
        $mail->assertSeeInHtml($reservation->formatted_return_date);
        $mail->assertSeeInHtml($reservation->formatted_pickup_hour);
        $mail->assertSeeInHtml($reservation->formatted_return_hour);

        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->phone);
        $mail->assertSeeInText($reservation->email);
        $mail->assertSeeInText($reservation->formatted_category);
        $mail->assertSeeInText($reservation->formatted_pickup_place);
        $mail->assertSeeInText($reservation->formatted_return_place);
        $mail->assertSeeInText($reservation->formatted_pickup_date);
        $mail->assertSeeInText($reservation->formatted_return_date);
        $mail->assertSeeInText($reservation->formatted_pickup_hour);
        $mail->assertSeeInText($reservation->formatted_return_hour);

    }

    #[Group("localiza_reservation_request")]
    #[Group("localiza")]
    #[Test]
    public function render_email_where_theres_total_insurance(): void {
        $reservation = Reservation::factory()->create([
            'total_insurance' => true
        ]);

        $mail = new AlquilatucarroReservationRequest($reservation);
        $mail->assertSeeInHtml("El cliente requiere seguro total");
        $mail->assertSeeInText("El cliente requiere seguro total");

        $mail = new AlquilameReservationRequest($reservation);
        $mail->assertSeeInHtml("El cliente requiere seguro total");
        $mail->assertSeeInText("El cliente requiere seguro total");

        $mail = new AlquicarrosReservationRequest($reservation);
        $mail->assertSeeInHtml("El cliente requiere seguro total");
        $mail->assertSeeInText("El cliente requiere seguro total");
    }

    #[Group("localiza_reservation_request")]
    #[Group("localiza")]
    #[Test]
    public function render_email_where_theres_no_total_insurance(): void {
        $reservation = Reservation::factory()->create([
            'total_insurance' => false
        ]);

        $mail = new AlquilatucarroReservationRequest($reservation);
        $mail->assertDontSeeInHtml("El cliente requiere seguro total");
        $mail->assertDontSeeInText("El cliente requiere seguro total");

        $mail = new AlquilameReservationRequest($reservation);
        $mail->assertDontSeeInHtml("El cliente requiere seguro total");
        $mail->assertDontSeeInText("El cliente requiere seguro total");

        $mail = new AlquicarrosReservationRequest($reservation);
        $mail->assertDontSeeInHtml("El cliente requiere seguro total");
        $mail->assertDontSeeInText("El cliente requiere seguro total");
    }
}
