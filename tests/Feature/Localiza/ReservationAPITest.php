<?php

namespace Tests\Feature\Localiza;

use App\Mail\AlquicarrosReservationRequest;
use App\Mail\AlquilameReservationRequest;
use App\Mail\AlquilatucarroReservationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Branch;
use App\Models\Franchise;
use App\Models\Reservation;
use App\Models\Category;
use App\Mail\LocalizaReservationRequest;

class ReservationAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group reservation_api
     * @group localiza
     * @test
     */
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

    /**
    * @group reservation_api
    * @group localiza
    * @test
    * */
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

    /**
    * @group reservation_api
    * @group localiza
    * @test
    * */
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

    /**
    * @group reservation_api
    * @group localiza
    * @test
    * */
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

    /**
    * @group reservation_api
    * @group localiza
    * @test
    * */
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

    /**
     * @group reservation_api
     * @group localiza
     * @test
     */
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

    /**
     * @group reservation_api
     * @group localiza
     * @test
     */
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

    /**
     * @group reservation_api
     * @group localiza
     * @test
     */
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

    /**
    * @group reservation_api
    * @group localiza
    * @test
    * */
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
}
