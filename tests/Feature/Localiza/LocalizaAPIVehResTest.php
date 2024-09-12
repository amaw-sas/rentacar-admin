<?php

namespace Tests\Feature\Localiza;

use App\Models\Reservation;
use App\Rentcar\Localiza\VehRes\LocalizaAPIVehRes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocalizaAPIVehResTest extends TestCase
{
    use RefreshDatabase;

    #[Group("localiza_veh_res")]
    #[Group("localiza")]
    #[Test]
    public function get_data_for_confirmed_reservation(){
        Http::preventStrayRequests();

        $xml = (View::make('localiza.tests.responses.vehres.vehres-confirmed-xml'))->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $reservation = Reservation::factory()->make();

        [
            'fullname'          => $name,
            'email'             => $email,
            'phone'             => $phoneNumber,
            'pickup_location'    => $pickupLocation,
            'return_location'    => $returnLocation,
            'category'          => $category,
        ] = $reservation->toArray();
        $referenceToken = fake()->sha256();
        $rateQualifier = fake()->numberBetween(1,9999);
        $pickupDateTime = $reservation->getPickupDateTime();
        $returnDateTime = $reservation->getReturnDateTime();

        $localizaReservation = new LocalizaAPIVehRes(
            name: $name,
            email: $email,
            phoneNumber: $phoneNumber,
            pickupDateTime: $pickupDateTime,
            returnDateTime: $returnDateTime,
            pickupLocation: $pickupLocation,
            returnLocation: $returnLocation,
            category: $category,
            referenceToken: $referenceToken,
            rateQualifier: $rateQualifier
        );

        $data = $localizaReservation->getData();

        $this->assertEquals([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmed',
        ], $data);
    }

}
