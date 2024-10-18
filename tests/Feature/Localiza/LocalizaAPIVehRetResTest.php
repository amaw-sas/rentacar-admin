<?php

namespace Tests\Feature\Localiza;

use App\Models\Reservation;
use App\Rentcar\Localiza\VehRetRes\LocalizaAPIVehRetRes;
use App\Rentcar\Localiza\Exceptions\VehRes\ReservationCancelledException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocalizaAPIVehRetResTest extends TestCase
{
    use RefreshDatabase;

    private $defaultPayload;

    public function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        $reservation = Reservation::factory()->make();

        [
            'reserve_code'          => $reserveCode,
        ] = $reservation->toArray();

        $this->defaultPayload = [
            "reserve_code" => $reserveCode,
        ];
    }

    #[Group("localiza_veh_ret_res")]
    #[Group("localiza")]
    #[Test]
    public function get_data_for_confirmed_reservation(){

        $xml = view('localiza.tests.responses.vehretres.vehretres-confirmed-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $localizaReservation = new LocalizaAPIVehRetRes(
            $this->defaultPayload
        );

        $data = $localizaReservation->getData();

        $this->assertEquals([
            'reservationStatus' => 'Confirmed',
        ], $data);
    }

    #[Group("localiza_veh_ret_res")]
    #[Group("localiza")]
    #[Test]
    public function get_data_for_pending_reservation(){

        $xml = view('localiza.tests.responses.vehretres.vehretres-pending-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $localizaReservation = new LocalizaAPIVehRetRes(
            $this->defaultPayload
        );

        $data = $localizaReservation->getData();

        $this->assertEquals([
            'reservationStatus' => 'Pending',
        ], $data);
    }

    #[Group("localiza_veh_ret_res")]
    #[Group("localiza")]
    #[Test]
    public function get_data_for_cancelled_reservation(){

        $this->expectException(ReservationCancelledException::class);

        $xml = view('localiza.tests.responses.vehretres.vehretres-cancelled-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $localizaReservation = new LocalizaAPIVehRetRes(
            $this->defaultPayload
        );

        $localizaReservation->getData();

        $this->fail('expected ReservationCancelledException');

    }

    #[Group("localiza_veh_ret_res")]
    #[Group("localiza")]
    #[Test]
    public function get_data_for_failed_reservation(){

        $xml = view('localiza.tests.responses.vehretres.vehretres-failed-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $localizaReservation = new LocalizaAPIVehRetRes(
            $this->defaultPayload
        );

        $data = $localizaReservation->getData();

        $this->assertEquals([
            'reservationStatus' => 'Failed',
        ], $data);
    }

    #[Group("localiza_veh_ret_res")]
    #[Group("localiza")]
    #[Test]
    public function get_data_for_expired_reservation(){

        $xml = view('localiza.tests.responses.vehretres.vehretres-expired-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $localizaReservation = new LocalizaAPIVehRetRes(
            $this->defaultPayload
        );

        $data = $localizaReservation->getData();

        $this->assertEquals([
            'reservationStatus' => 'Expired',
        ], $data);
    }

}
