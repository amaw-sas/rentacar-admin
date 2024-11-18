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
use SimpleXMLElement;
use Tests\TestCase;

class LocalizaAPIVehResTest extends TestCase
{
    use RefreshDatabase;

    private $defaultPayload;
    protected $namespace = "http://www.opentravel.org/OTA/2003/05";

    public function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

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
        $rateQualifier = (string) fake()->numberBetween(1,9999);
        $pickupDateTime = $reservation->getPickupDateTime();
        $returnDateTime = $reservation->getReturnDateTime();

        $this->defaultPayload = [
            "fullname" => $name,
            "email" => $email,
            "phone" => $phoneNumber,
            "pickup_datetime" => $pickupDateTime,
            "return_datetime" => $returnDateTime,
            "pickup_location" => $pickupLocation,
            "return_location" => $returnLocation,
            "category" => $category,
            "reference_token" => $referenceToken,
            "rate_qualifier" => $rateQualifier
        ];
    }

    #[Group("localiza_veh_res")]
    #[Group("localiza")]
    #[Test]
    public function get_data_for_confirmed_reservation(){

        $xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $localizaReservation = new LocalizaAPIVehRes(
            $this->defaultPayload
        );

        $data = $localizaReservation->getData();

        $this->assertEquals([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Confirmed',
        ], $data);
    }

    #[Group("localiza_veh_res")]
    #[Group("localiza")]
    #[Test]
    public function get_data_for_pending_reservation(){

        $xml = view('localiza.tests.responses.vehres.vehres-pending-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $localizaReservation = new LocalizaAPIVehRes(
            $this->defaultPayload
        );

        $data = $localizaReservation->getData();

        $this->assertEquals([
            'reserveCode' => 'AV1BRQW35U',
            'reservationStatus' => 'Pending',
        ], $data);
    }

    #[Group("localiza_veh_res")]
    #[Group("localiza")]
    #[Test]
    public function get_input_data_with_phone_and_phone_country_code_for_colombian_phone(){
        $newPayload = $this->defaultPayload;
        $newPayload['phone'] = '+57 315 5555555';

        $localizaReservation = new LocalizaAPIVehRes(
            $newPayload
        );

        $rawXml = $localizaReservation->getFilledInputXML();
        $nodeXml = new SimpleXMLElement($rawXml);
        $nodeXml->registerXPathNamespace("OTA",$this->namespace);
        $phoneNodes = $nodeXml->xpath('//OTA:Telephone');

        if(count($phoneNodes) > 0){
            $phoneNode = $phoneNodes[0];
            $countryCodePhone = $phoneNode->attributes()->CountryCode;
            $phone = $phoneNode->attributes()->PhoneNumber;
            $this->assertEquals('57', $countryCodePhone);
            $this->assertEquals('3155555555', $phone);
        }
        else $this->fail('No phone nodes founded!');
    }

    #[Group("localiza_veh_res")]
    #[Group("localiza")]
    #[Test]
    public function get_input_data_with_phone_and_phone_country_code_for_american_phone(){
        $newPayload = $this->defaultPayload;
        $newPayload['phone'] = '+1 315 5555555';

        $localizaReservation = new LocalizaAPIVehRes(
            $newPayload
        );

        $rawXml = $localizaReservation->getFilledInputXML();
        $nodeXml = new SimpleXMLElement($rawXml);
        $nodeXml->registerXPathNamespace("OTA",$this->namespace);
        $phoneNodes = $nodeXml->xpath('//OTA:Telephone');

        if(count($phoneNodes) > 0){
            $phoneNode = $phoneNodes[0];
            $countryCodePhone = $phoneNode->attributes()->CountryCode;
            $phone = $phoneNode->attributes()->PhoneNumber;
            $this->assertEquals('1', $countryCodePhone);
            $this->assertEquals('3155555555', $phone);
        }
        else $this->fail('No phone nodes founded!');
    }


}
