<?php

namespace Tests\Feature\Localiza;

use App\Enums\MonthlyMileage;
use App\Mail\AlquicarrosReservationRequest;
use App\Mail\AlquilameReservationRequest;
use App\Mail\AlquilatucarroReservationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Models\Branch;
use App\Models\Franchise;
use App\Models\Reservation;
use App\Models\Category;
use App\Mail\LocalizaReservationRequest;

class ReservationAPITest extends TestCase
{
    use RefreshDatabase;

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_default_reservation()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("monthly_mileage")]
    #[Test]
    public function store_a_default_reservation_with_monthly_mileage()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['monthly_mileage'] = MonthlyMileage::twoKKms->value;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertEquals($reservation->monthly_mileage, MonthlyMileage::twoKKms->value);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("monthly_mileage")]
    #[Test]
    public function store_a_default_reservation_with_empty_string_monthly_mileage()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['monthly_mileage'] = "";

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertEquals($reservation->monthly_mileage, null);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("monthly_mileage")]
    #[Test]
    public function store_a_default_reservation_with_null_monthly_mileage()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['monthly_mileage'] = null;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertEquals($reservation->monthly_mileage, null);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("total_insurance")]
    #[Test]
    public function store_a_default_reservation_with_total_insurance()
    {
        Mail::fake();

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_insurance'] = true;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertTrue((boolean) $reservation->total_insurance);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("total_insurance")]
    #[Test]
    public function store_a_default_reservation_with_no_total_insurance()
    {
        Mail::fake();

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_insurance'] = false;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertFalse((boolean) $reservation->total_insurance);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("return_fee")]
    #[Test]
    public function store_a_default_reservation_with_return_fee()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['return_fee'] = 100;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertEquals($reservation->return_fee, 100);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("return_fee")]
    #[Test]
    public function store_a_default_reservation_with_null_return_fee()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['return_fee'] = null;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertEquals($reservation->return_fee, 0);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("return_fee")]
    #[Test]
    public function store_a_default_reservation_with_empty_string_return_fee()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['return_fee'] = "";

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertEquals($reservation->return_fee, 0);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("total_price_to_pay")]
    #[Test]
    public function store_a_default_reservation_with_total_price_to_pay()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_price_to_pay'] = 100;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertEquals($reservation->total_price_to_pay, 100);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("total_price_to_pay")]
    #[Test]
    public function store_a_default_reservation_with_total_price_to_pay_null_and_show_error()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_price_to_pay'] = null;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertFound();

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function send_a_mail_to_localiza_car_provider_when_record_a_reservation(): void {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();

        Mail::assertQueued(LocalizaReservationRequest::class);
        Mail::assertQueued(fn(LocalizaReservationRequest $mail) =>
            $mail->reservation->fullname == $reservation->fullname &&
            $mail->reservation->category == $reservation->category &&
            $mail->reservation->franchise == $reservation->franchise
            // $mail->hasTo($localizaReservationEmail)
        );
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function send_a_alquilatucarro_mail_to_localiza_car_provider_when_record_a_reservation(): void {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();

        Mail::assertQueued(AlquilatucarroReservationRequest::class);
        Mail::assertQueued(fn(AlquilatucarroReservationRequest $mail) =>
            $mail->reservation->fullname == $reservation->fullname &&
            $mail->reservation->category == $reservation->category &&
            $mail->reservation->franchise == $reservation->franchise
        );
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function send_a_alquilame_mail_to_localiza_car_provider_when_record_a_reservation(): void {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();

        Mail::assertQueued(AlquilameReservationRequest::class);
        Mail::assertQueued(fn(AlquilameReservationRequest $mail) =>
            $mail->reservation->fullname == $reservation->fullname &&
            $mail->reservation->category == $reservation->category &&
            $mail->reservation->franchise == $reservation->franchise
        );
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function send_a_alquicarros_mail_to_localiza_car_provider_when_record_a_reservation(): void {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquicarros'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();

        Mail::assertQueued(AlquicarrosReservationRequest::class);
        Mail::assertQueued(fn(AlquicarrosReservationRequest $mail) =>
            $mail->reservation->fullname == $reservation->fullname &&
            $mail->reservation->category == $reservation->category &&
            $mail->reservation->franchise == $reservation->franchise
        );
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_reservation_with_a_non_existent_pickup_location_and_fail()
    {
        Mail::fake();

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = "AABOG";
        $reservationData['return_location'] = $returnLocation->code;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertNotFound();

        $reservation = Reservation::first();
        $this->assertNull($reservation);

        Mail::assertNotQueued(LocalizaReservationRequest::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_reservation_with_a_non_existent_return_location_and_fail()
    {
        Mail::fake();

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = "AABOG";

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertNotFound();

        $reservation = Reservation::first();
        $this->assertNull($reservation);

        Mail::assertNotQueued(LocalizaReservationRequest::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_reservation_with_a_non_existent_franchise_and_fail()
    {
        Mail::fake();

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = "test";
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertNotFound();

        $reservation = Reservation::first();
        $this->assertNull($reservation);

        Mail::assertNotQueued(LocalizaReservationRequest::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function real_data_test(): void {
        Mail::fake();

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT',
            'name'  =>  'Bogotá Aeropuerto'
        ]);
        $returnLocation = $pickupLocation;
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'    =>  'C',
            'name'                  =>  'Gama C',
            'category'              =>  'Gama C Económico Mecánico',
        ]);

        $reservationData = [
            'fullname'          =>  'prueba.alquilame@prueba.com',
            'identification_type' =>  'Cedula Ciudadania',
            'identification' =>  '11111111',
            'phone' =>  '+573155555555',
            'email' =>  'prueba.alquilame@prueba.com',
            'category'          =>  'C',
            'pickup_location'   => "AABOT",
            'pickup_date'       => "2024-04-02",
            'pickup_hour'       => "12:00",
            'return_location'   => "AABOT",
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
            'franchise'           => 'alquilame',
        ];

        $response = $this->post(route('reserve.store'), $reservationData);
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function when_the_request_has_total_coverage_send_a_email_with_the_total_price_with_full_coverage()
    {
        Mail::fake();

        $localizaReservationEmail = config('localiza.reservationEmailAddress');

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);
        $category = Category::factory()->create([
            'identification'  =>  'FX'
        ]);

        $reservationData = Reservation::factory()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_insurance'] = 0;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertCreated();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);

    }
}
