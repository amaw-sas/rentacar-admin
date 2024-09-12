<?php

namespace App\Http\Controllers\Dummy;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Rentcar\Localiza\VehRes\LocalizaAPIVehRes;

class DummyVehicleReserveController extends Controller
{
    public $name;
    public $email;
    public $phoneNumber;
    public $referenceToken;
    public $pickupLocation;
    public $returnLocation;
    public $pickupDateTime;
    public $returnDateTime;
    public $category;
    public $rateQualifier;

    public function __construct()
    {
        parent::__construct();

        $this->name = "John Doe";
        $this->email = "john@doe.net";
        $this->phoneNumber = "+573155555555";
        $this->referenceToken = "ewfwefewfefefef";
        $this->pickupLocation = "AABOT";
        $this->returnLocation = "AABOT";
        $this->pickupDateTime = "2024-01-15T23:00:00";
        $this->returnDateTime = "2024-01-17T13:00:00";
        $this->category = "C";
        $this->rateQualifier = "111111";
    }

    public function confirmed(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $localiza = new LocalizaAPIVehRes(
            name: $this->name,
            email: $this->email,
            phoneNumber: $this->phoneNumber,
            referenceToken: $this->referenceToken,
            category: $this->category,
            rateQualifier: $this->rateQualifier,
            pickupLocation: $this->pickupLocation,
            returnLocation: $this->returnLocation,
            pickupDateTime: $this->pickupDateTime,
            returnDateTime: $this->returnDateTime,
        );

        return $localiza->getData();
    }

    public function error_desconocido(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-unknown-error-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $localiza = new LocalizaAPIVehRes(
            name: $this->name,
            email: $this->email,
            phoneNumber: $this->phoneNumber,
            referenceToken: $this->referenceToken,
            category: $this->category,
            rateQualifier: $this->rateQualifier,
            pickupLocation: $this->pickupLocation,
            returnLocation: $this->returnLocation,
            pickupDateTime: $this->pickupDateTime,
            returnDateTime: $this->returnDateTime,
        );

        return $localiza->getData();
    }

    public function timeout(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 408)
        ]);

        $localiza = new LocalizaAPIVehRes(
            name: $this->name,
            email: $this->email,
            phoneNumber: $this->phoneNumber,
            referenceToken: $this->referenceToken,
            category: $this->category,
            rateQualifier: $this->rateQualifier,
            pickupLocation: $this->pickupLocation,
            returnLocation: $this->returnLocation,
            pickupDateTime: $this->pickupDateTime,
            returnDateTime: $this->returnDateTime,
        );

        return $localiza->getData();
    }


}
