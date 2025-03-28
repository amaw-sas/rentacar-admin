<?php

namespace Tests\Feature\Localiza;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Mail\ReservationPendingNotification\AlquilatucarroReservationPendingNotification;
use App\Mail\ReservationPendingNotification\AlquilameReservationPendingNotification;
use App\Mail\ReservationPendingNotification\AlquicarrosReservationPendingNotification;
use App\Mail\ReservationPendingNotification\ReservationPendingNotification;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\Branch;
use App\Models\Franchise;
use App\Models\Category;

class PendingReservationNotificationMailTest extends TestCase
{
    use RefreshDatabase;

    #[Group("pending_reservation_notification")]
    #[Test]
    public function send_a_pending_reservation_notification_email_to_localiza_by_alquilatucarro(): void {
        $reservation = Reservation::factory()->create();

        $mail = new AlquilatucarroReservationPendingNotification($reservation);
        $mail->assertSeeInHtml($reservation->reserve_code);
        $mail->assertSeeInText($reservation->reserve_code);
    }

    #[Group("pending_reservation_notification")]
    #[Test]
    public function send_a_pending_reservation_notification_email_to_localiza_by_alquilame(): void {
        $reservation = Reservation::factory()->create();

        $mail = new AlquilameReservationPendingNotification($reservation);
        $mail->assertSeeInHtml($reservation->reserve_code);
        $mail->assertSeeInText($reservation->reserve_code);
    }

    #[Group("pending_reservation_notification")]
    #[Test]
    public function send_a_pending_reservation_notification_email_to_localiza_by_alquicarros(): void {
        $reservation = Reservation::factory()->create();

        $mail = new AlquicarrosReservationPendingNotification($reservation);
        $mail->assertSeeInHtml($reservation->reserve_code);
        $mail->assertSeeInText($reservation->reserve_code);
    }

    #[Group("pending_reservation_notification")]
    #[Test]
    public function mention_when_a_pending_reservation_has_total_insurance(): void {
        $reservation = Reservation::factory()->create([
            'total_insurance' => true
        ]);

        $total_insurance_message = "El cliente requiere seguro total";

        $mail = new ReservationPendingNotification($reservation);
        $mail->assertSeeInHtml($total_insurance_message);
        $mail->assertSeeInText($total_insurance_message);
    }

    #[Group("pending_reservation_notification")]
    #[Test]
    public function dont_mention_when_a_pending_reservation_has_no_total_insurance(): void {
        $reservation = Reservation::factory()->create([
            'total_insurance' => false
        ]);

        $total_insurance_message = "El cliente requiere seguro total";

        $mail = new ReservationPendingNotification($reservation);
        $mail->assertDontSeeInHTML($total_insurance_message);
        $mail->assertDontSeeInText($total_insurance_message);
    }

    #[Group("pending_reservation_notification")]
    #[Test]
    public function mention_when_a_pending_reservation_has_total_insurance_by_alquilatucarro(): void {
        $reservation = Reservation::factory()->create([
            'total_insurance' => true
        ]);

        $total_insurance_message = "El cliente requiere seguro total";

        $mail = new AlquilatucarroReservationPendingNotification($reservation);
        $mail->assertSeeInHtml($total_insurance_message);
        $mail->assertSeeInText($total_insurance_message);
    }

    #[Group("pending_reservation_notification")]
    #[Test]
    public function mention_when_a_pending_reservation_has_total_insurance_by_alquilame(): void {
        $reservation = Reservation::factory()->create([
            'total_insurance' => true
        ]);

        $total_insurance_message = "El cliente requiere seguro total";

        $mail = new AlquilameReservationPendingNotification($reservation);
        $mail->assertSeeInHtml($total_insurance_message);
        $mail->assertSeeInText($total_insurance_message);
    }

    #[Group("pending_reservation_notification")]
    #[Test]
    public function mention_when_a_pending_reservation_has_total_insurance_by_alquicarros(): void {
        $reservation = Reservation::factory()->create([
            'total_insurance' => true
        ]);

        $total_insurance_message = "El cliente requiere seguro total";

        $mail = new AlquicarrosReservationPendingNotification($reservation);
        $mail->assertSeeInHtml($total_insurance_message);
        $mail->assertSeeInText($total_insurance_message);
    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function testing_email(): void {

        $this->markTestSkipped();

        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT',
            'name'  =>  'Bogotá Aeropuerto',
            'pickup_address' => 'Aeropuerto El Dorado, Piso 1 Puerta 7, Punto de atención para traslado hasta la rentadora de 6:00 am a 10:00 pm, en otro horario llamar al 350-280-6370',
            'return_address' => 'Diagonal  24C, 99-45 - a 5 minutos del Aeropuerto',
            'pickup_map'    => 'https://maps.app.goo.gl/U3Sct9jNM8BrLFR78',
            'return_map'    => 'https://maps.app.goo.gl/JjpsSCHkCrgGYa9P7',
        ]);
        $returnLocation = $pickupLocation;
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'          =>  'prueba.alquilame@prueba.com',
            'identification_type' =>  'Cedula Ciudadania',
            'identification' =>  '11111111',
            'phone' =>  '+573155555555',
            'email' =>  'prueba.alquilame@prueba.com',
            'pickup_location'   => $pickupLocation->id,
            'pickup_date'       => "2024-04-02",
            'pickup_hour'       => "12:00",
            'return_location'   => $returnLocation->id,
            'return_date'       => "2024-04-09",
            'return_hour'       => "12:00",
            'selected_days'     => "7",
            'extra_hours'       => "0",
            'extra_hours_price' => "0",
            'coverage_days'     => "7",
            'coverage_price'    => "203000",
            'tax_fee'           => "93583",
            'iva_fee'           => "195588",
            'total_price'           => "935829",
            'total_price_to_pay'           => "1000000",
            'franchise'           => $franchise->id,
            'status' => ReservationStatus::Reservado->value,
            'reserve_code'  => 'AVC8182',
            'total_insurance'   => false,
            'category'  =>  $category->id,
        ]);

        Mail::mailer($franchise->name)
                ->to($reservation->email)
                ->send(new AlquicarrosReservationPendingNotification($reservation));

    }
}
