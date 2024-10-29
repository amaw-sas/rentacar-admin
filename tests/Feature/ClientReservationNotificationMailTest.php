<?php

namespace Tests\Feature;

use App\Enums\MonthlyMileage;
use App\Enums\ReservationStatus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Mail\ReservationClientNotification\AlquilatucarroReservationClientNotification;
use App\Mail\ReservationClientNotification\AlquilameReservationClientNotification;
use App\Mail\ReservationClientNotification\AlquicarrosReservationClientNotification;
use App\Mail\ReservationClientNotification\ReservationClientNotification;
use App\Models\Category;
use App\Models\Franchise;
use App\Models\Reservation;
use App\Models\Branch;

class ClientReservationNotificationMailTest extends TestCase
{
    use RefreshDatabase;

    public Category $category;

    public function setUp(): void {
        parent::setUp();

        $this->category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);
    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function send_a_email_to_client(): void {
        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id
        ]);

        $mail = new ReservationClientNotification($reservation);
        $mail->assertSeeInHtml($reservation->fullname);
        $mail->assertSeeInHtml($reservation->short_identification_type);
        $mail->assertSeeInHtml($reservation->identification);
        $mail->assertSeeInHtml($reservation->formatted_category);
        $mail->assertSeeInHtml($reservation->pickupLocation->name);
        $mail->assertSeeInHtml($reservation->returnLocation->name);
        $mail->assertSeeInHtml($reservation->short_formatted_pickup_date);
        $mail->assertSeeInHtml($reservation->short_formatted_return_date);
        $mail->assertSeeInHtml($reservation->formatted_pickup_hour);
        $mail->assertSeeInHtml($reservation->formatted_return_hour);


        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->short_identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->formatted_category);
        $mail->assertSeeInText($reservation->pickupLocation->name);
        $mail->assertSeeInText($reservation->returnLocation->name);
        $mail->assertSeeInText($reservation->short_formatted_pickup_date);
        $mail->assertSeeInText($reservation->short_formatted_return_date);
        $mail->assertSeeInText($reservation->formatted_pickup_hour);
        $mail->assertSeeInText($reservation->formatted_return_hour);

    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function render_email_from_alquilatucarro_and_check_data(): void {
        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id
        ]);

        $mail = new AlquilatucarroReservationClientNotification($reservation);
        $mail->assertSeeInHtml($reservation->fullname);
        $mail->assertSeeInHtml($reservation->short_identification_type);
        $mail->assertSeeInHtml($reservation->identification);
        $mail->assertSeeInHtml($reservation->formatted_category);
        $mail->assertSeeInHtml($reservation->pickupLocation->name);
        $mail->assertSeeInHtml($reservation->returnLocation->name);
        $mail->assertSeeInHtml($reservation->short_formatted_pickup_date);
        $mail->assertSeeInHtml($reservation->short_formatted_return_date);
        $mail->assertSeeInHtml($reservation->formatted_pickup_hour);
        $mail->assertSeeInHtml($reservation->formatted_return_hour);


        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->short_identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->formatted_category);
        $mail->assertSeeInText($reservation->pickupLocation->name);
        $mail->assertSeeInText($reservation->returnLocation->name);
        $mail->assertSeeInText($reservation->short_formatted_pickup_date);
        $mail->assertSeeInText($reservation->short_formatted_return_date);
        $mail->assertSeeInText($reservation->formatted_pickup_hour);
        $mail->assertSeeInText($reservation->formatted_return_hour);

    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function render_email_from_alquilame_and_check_data(): void {
        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id
        ]);

        $mail = new AlquilameReservationClientNotification($reservation);
        $mail->assertSeeInHtml($reservation->fullname);
        $mail->assertSeeInHtml($reservation->short_identification_type);
        $mail->assertSeeInHtml($reservation->identification);
        $mail->assertSeeInHtml($reservation->formatted_category);
        $mail->assertSeeInHtml($reservation->pickupLocation->name);
        $mail->assertSeeInHtml($reservation->returnLocation->name);
        $mail->assertSeeInHtml($reservation->short_formatted_pickup_date);
        $mail->assertSeeInHtml($reservation->short_formatted_return_date);
        $mail->assertSeeInHtml($reservation->formatted_pickup_hour);
        $mail->assertSeeInHtml($reservation->formatted_return_hour);


        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->short_identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->formatted_category);
        $mail->assertSeeInText($reservation->pickupLocation->name);
        $mail->assertSeeInText($reservation->returnLocation->name);
        $mail->assertSeeInText($reservation->short_formatted_pickup_date);
        $mail->assertSeeInText($reservation->short_formatted_return_date);
        $mail->assertSeeInText($reservation->formatted_pickup_hour);
        $mail->assertSeeInText($reservation->formatted_return_hour);

    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function render_email_from_alquicarros_and_check_data(): void {
        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id
        ]);

        $mail = new AlquicarrosReservationClientNotification($reservation);
        $mail->assertSeeInHtml($reservation->fullname);
        $mail->assertSeeInHtml($reservation->short_identification_type);
        $mail->assertSeeInHtml($reservation->identification);
        $mail->assertSeeInHtml($reservation->formatted_category);
        $mail->assertSeeInHtml($reservation->pickupLocation->name);
        $mail->assertSeeInHtml($reservation->returnLocation->name);
        $mail->assertSeeInHtml($reservation->short_formatted_pickup_date);
        $mail->assertSeeInHtml($reservation->short_formatted_return_date);
        $mail->assertSeeInHtml($reservation->formatted_pickup_hour);
        $mail->assertSeeInHtml($reservation->formatted_return_hour);


        $mail->assertSeeInText($reservation->fullname);
        $mail->assertSeeInText($reservation->short_identification_type);
        $mail->assertSeeInText($reservation->identification);
        $mail->assertSeeInText($reservation->formatted_category);
        $mail->assertSeeInText($reservation->pickupLocation->name);
        $mail->assertSeeInText($reservation->returnLocation->name);
        $mail->assertSeeInText($reservation->short_formatted_pickup_date);
        $mail->assertSeeInText($reservation->short_formatted_return_date);
        $mail->assertSeeInText($reservation->formatted_pickup_hour);
        $mail->assertSeeInText($reservation->formatted_return_hour);

    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function render_email_where_theres_total_insurance(): void {
        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id,
            'total_insurance' => true
        ]);

        $message = "Seguro total";

        $mail = new AlquilatucarroReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquilameReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquicarrosReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);
    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function render_email_where_theres_no_total_insurance(): void {

        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id,
            'total_insurance' => false
        ]);

        $message = "Seguro básico";

        $mail = new AlquilatucarroReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquilameReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquicarrosReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);
    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function render_email_where_theres_monthly_mileage_with_1k_kms(): void {
        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id,
            'selected_days' => 30,
            'monthly_mileage' => MonthlyMileage::oneKKms->value
        ]);

        $message = "Kilometraje: 1k_kms";

        $mail = new AlquilatucarroReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquilameReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquicarrosReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);
    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function render_email_where_theres_monthly_mileage_with_2k_kms(): void {
        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id,
            'selected_days' => 30,
            'monthly_mileage' => MonthlyMileage::twoKKms->value
        ]);

        $message = "Kilometraje: 2k_kms";

        $mail = new AlquilatucarroReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquilameReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquicarrosReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);
    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function render_email_where_theres_monthly_mileage_with_3k_kms(): void {
        $reservation = Reservation::factory()->create([
            'category'  => $this->category->id,
            'selected_days' => 30,
            'monthly_mileage' => MonthlyMileage::threeKKms->value
        ]);

        $message = "Kilometraje: 3k_kms";

        $mail = new AlquilatucarroReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquilameReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);

        $mail = new AlquicarrosReservationClientNotification($reservation);
        $mail->assertSeeInHtml($message);
        $mail->assertSeeInText($message);
    }

    #[Group("client_reservation_notification")]
    #[Test]
    public function testing_email(): void {

        $this->markTestSkipped();

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
            'category'          =>  $this->category->id,
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
            'monthly_mileage' => MonthlyMileage::threeKKms->value
        ]);

        Mail::mailer($franchise->name)
                ->to($reservation->email)
                ->send(new AlquilatucarroReservationClientNotification($reservation));

    }
}
