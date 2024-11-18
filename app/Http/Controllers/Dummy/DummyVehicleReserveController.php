<?php

namespace App\Http\Controllers\Dummy;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Rentcar\Localiza\VehRes\LocalizaAPIVehRes;

class DummyVehicleReserveController extends Controller
{
    public $payload = [
        "fullname" => "John Doe",
        "email" => "john@doe.net",
        "phone" => "+573155555555",
        "pickup_location" => "AABOT",
        "return_location" => "AABOT",
        "pickup_datetime" => "2024-01-15T23:00:00",
        "return_datetime" => "2024-01-17T13:00:00",
        "category" => "C",
        "reference_token" => "ewfwefewfefefef",
        "rate_qualifier" => "111111",
    ];

    public function index(){
        return 'ok';
    }

    public function reservado(){
        Http::preventStrayRequests();
        Mail::fake();

        $raw_xml = view('localiza.tests.responses.vehres.vehres-confirmed-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $localiza = new LocalizaAPIVehRes($this->payload);

        return $localiza->getData();
    }

    public function pendiente(){
        Http::preventStrayRequests();
        Mail::fake();

        $raw_xml = view('localiza.tests.responses.vehres.vehres-pending-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $localiza = new LocalizaAPIVehRes($this->payload);

        return $localiza->getData();
    }

    public function error_desconocido(){
        Http::preventStrayRequests();
        Mail::fake();

        $raw_xml = view('localiza.tests.responses.vehres.vehres-unknown-error-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $localiza = new LocalizaAPIVehRes($this->payload);

        return $localiza->getData();
    }

    public function timeout(){
        Http::preventStrayRequests();
        Mail::fake();

        $raw_xml = view('localiza.tests.responses.vehres.vehres-timeout-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 408)
        ]);

        $localiza = new LocalizaAPIVehRes($this->payload);

        return $localiza->getData();
    }


}
