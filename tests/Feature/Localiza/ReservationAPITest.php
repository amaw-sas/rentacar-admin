<?php

namespace Tests\Feature\Localiza;

use App\Enums\MonthlyMileage;
use App\Jobs\SendPendingReservationNotificationJob;
use App\Jobs\SendLocalizaReservationRequestJob;
use App\Mail\ReservationClientNotification\Reserved\ReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Reserved\AlquilatucarroReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Reserved\AlquilameReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Reserved\AlquicarrosReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\FailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquilatucarroFailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquilameFailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquicarrosFailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\PendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquilatucarroPendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquilamePendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquicarrosPendingReservationClientNotification;
use App\Mail\ReservationPendingNotification\AlquilatucarroReservationPendingNotification;
use App\Mail\ReservationPendingNotification\AlquilameReservationPendingNotification;
use App\Mail\ReservationPendingNotification\AlquicarrosReservationPendingNotification;
use App\Mail\ReservationPendingNotification\ReservationPendingNotification;
use App\Mail\ReservationTotalInsuranceNotification\ReservationTotalInsuranceNotification;
use App\Mail\ReservationTotalInsuranceNotification\AlquilatucarroReservationTotalInsuranceNotification;
use App\Mail\ReservationTotalInsuranceNotification\AlquilameReservationTotalInsuranceNotification;
use App\Mail\ReservationTotalInsuranceNotification\AlquicarrosReservationTotalInsuranceNotification;
use App\Mail\ReservationRequest\AlquilatucarroReservationRequest;
use App\Mail\ReservationRequest\AlquilameReservationRequest;
use App\Mail\ReservationRequest\AlquicarrosReservationRequest;
use App\Mail\ReservationRequest\ReservationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Models\Branch;
use App\Models\Franchise;
use App\Models\Reservation;
use App\Models\Category;

class ReservationAPITest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();



        Mail::fake();

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_default_reservation()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_default_reservation_with_referer()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['user'] = 'referer';

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);
        $this->assertEquals($reservation->user, 'referer');

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Group("monthly_mileage")]
    #[Test]
    public function store_a_default_reservation_with_monthly_mileage()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['monthly_mileage'] = MonthlyMileage::twoKKms->value;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['monthly_mileage'] = "";

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['monthly_mileage'] = null;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_insurance'] = true;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_insurance'] = false;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['return_fee'] = 100;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['return_fee'] = null;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['return_fee'] = "";

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_price_to_pay'] = 100;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

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
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
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
    public function store_a_reservation_with_a_non_existent_pickup_location_and_fail()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = "AABOG";
        $reservationData['return_location'] = $returnLocation->code;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertNotFound();

        $reservation = Reservation::first();
        $this->assertNull($reservation);

        Mail::assertNotQueued(ReservedReservationClientNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_reservation_with_a_non_existent_return_location_and_fail()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = "AABOG";

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertNotFound();

        $reservation = Reservation::first();
        $this->assertNull($reservation);

        Mail::assertNotQueued(ReservedReservationClientNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_reservation_with_a_non_existent_franchise_and_fail()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $pickupLocation = Branch::factory()->create([
            'code'  =>  'AABOT'
        ]);
        $returnLocation = Branch::factory()->create([
            'code'  =>  'AAMED'
        ]);
        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = "test";
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertNotFound();

        $reservation = Reservation::first();
        $this->assertNull($reservation);

        Mail::assertNotQueued(ReservedReservationClientNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function real_data_test(): void {

        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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
            'rate_qualifier'           => '1234',
            'reference_token'           => '1234',
        ];

        $response = $this->post(route('reserve.store'), $reservationData);
        $response->assertOk();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function store_a_default_reservation_and_expect_a_reserve_code()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals("AV1BRQW35U", $reservation->reserve_code);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function when_the_request_has_total_coverage_send_a_email_with_the_total_price_with_full_coverage()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['total_insurance'] = 0;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservation->pickup_location, $pickupLocation->id);
        $this->assertEquals($reservation->return_location, $returnLocation->id);
        $this->assertEquals($reservation->franchise, $franchise->id);

    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_monthly_then_send_a_localiza_notification()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);
        Queue::fake();

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['selected_days'] = 30;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reservationStatus' => 'Pendiente',
            'reserveCode' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Queue::assertPushed(SendLocalizaReservationRequestJob::class);
    }



    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_monthly_then_send_a_localiza_notification_by_alquilatucarro_mail()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['selected_days'] = 30;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reservationStatus' => 'Pendiente',
            'reserveCode' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilatucarroReservationRequest::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_monthly_then_send_a_localiza_notification_by_alquilame_mail()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['selected_days'] = 30;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reservationStatus' => 'Pendiente',
            'reserveCode' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilameReservationRequest::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_monthly_then_send_a_localiza_notification_by_alquicarros_mail()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;
        $reservationData['selected_days'] = 30;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reservationStatus' => 'Pendiente',
            'reserveCode' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquicarrosReservationRequest::class);
    }

    #[Group("reservation_api")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_then_send_a_localiza_notification()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);
        Queue::fake();

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Queue::assertPushed(SendPendingReservationNotificationJob::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_then_send_a_pending_notification_to_client_and_localiza()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(PendingReservationClientNotification::class);
        Mail::assertQueued(ReservationPendingNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_then_send_a_pending_notification_to_client_and_localiza_by_alquilatucarro()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilatucarroPendingReservationClientNotification::class);
        Mail::assertQueued(AlquilatucarroReservationPendingNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_then_send_a_pending_notification_to_client_and_localiza_by_alquilame()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilamePendingReservationClientNotification::class);
        Mail::assertQueued(AlquilameReservationPendingNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_then_send_a_pending_notification_to_client_by_alquicarros()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquicarrosPendingReservationClientNotification::class);
        Mail::assertQueued(AlquicarrosReservationPendingNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_then_send_a_reserved_notification_to_client()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(ReservedReservationClientNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_then_send_a_reserved_notification_to_client_by_alquilatucarro()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilatucarroReservedReservationClientNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_then_send_a_reserved_notification_to_client_by_alquilame()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilameReservedReservationClientNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_then_send_a_reserved_notification_to_client_by_alquicarros()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make();
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquicarrosReservedReservationClientNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_and_total_insurance_then_send_a_total_insurance_notification_to_localiza()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 1
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(ReservationTotalInsuranceNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_and_no_total_insurance_then_dont_send_a_total_insurance_notification_to_localiza()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 0
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertNotQueued(ReservationTotalInsuranceNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_and_total_insurance_then_send_a_total_insurance_notification_to_localiza_by_alquilatucarro()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 1
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilatucarroReservationTotalInsuranceNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_and_total_insurance_then_send_a_total_insurance_notification_to_localiza_by_alquilame()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 1
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilameReservationTotalInsuranceNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_reserved_and_total_insurance_then_send_a_total_insurance_notification_to_localiza_by_alquicarros()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 1
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmado',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquicarrosReservationTotalInsuranceNotification::class);
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_and_total_insurance_then_send_a_localiza_notification()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);
        Mail::fake();

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 1
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(function(ReservationPendingNotification $mail) {
            return $mail->assertSeeInHTML('El cliente requiere seguro total');
        });
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_and_no_total_insurance_then_dont_mention_it_when_send_a_localiza_notification()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);
        Mail::fake();

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 0
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(function(ReservationPendingNotification $mail) {
            return $mail->assertDontSeeInHTML('El cliente requiere seguro total');
        });
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_and_total_insurance_then_send_a_localiza_notification_by_alquilatucarro()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);
        Mail::fake();

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 1
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(function(AlquilatucarroReservationPendingNotification $mail) {
            return $mail->assertSeeInHTML('El cliente requiere seguro total');
        });
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_and_total_insurance_then_send_a_localiza_notification_by_alquilame()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);
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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 1
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(function(AlquilameReservationPendingNotification $mail) {
            return $mail->assertSeeInHTML('El cliente requiere seguro total');
        });
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("total_insurance_notification")]
    #[Group("localiza")]
    #[Test]
    public function when_a_reservation_is_pending_and_total_insurance_then_send_a_localiza_notification_by_alquicarros()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);
        Mail::fake();

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

        $reservationData = Reservation::factory()->withReservationRequirements()->make([
            'total_insurance' => 1
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(function(AlquicarrosReservationPendingNotification $mail) {
            return $mail->assertSeeInHTML('El cliente requiere seguro total');
        });
    }

    #[Group("reservation_api")]
    #[Group("client_reservation_notification")]
    #[Group("monthly_reservation")]
    #[Group("localiza")]
    #[Test]
    public function create_a_monthly_reservation()
    {
        Http::preventStrayRequests();
        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();
        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

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

        $reservationData = Reservation::factory()->make([
            'selected_days' => 30,
            'rate_qualifier' => null,
            'reference_token' => null,
        ]);
        $reservationData['franchise'] = $franchise->name;
        $reservationData['pickup_location'] = $pickupLocation->code;
        $reservationData['return_location'] = $returnLocation->code;
        $reservationData['category'] = $category->identification;

        $response = $this->post(route('reserve.store'), $reservationData->toArray());
        $response->assertOk();
        $response->assertJson([
            'reserveCode' => 'Pendiente',
            'reservationStatus' => 'Pendiente',
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        Mail::assertQueued(AlquilatucarroReservationRequest::class);
    }

}
