<?php

namespace App\Rentcar\Localiza\VehRes;

use App\Rentcar\Localiza\Contracts\LocalizaAPIRequest;
use App\Rentcar\Localiza\Exceptions\TimeoutException;
use App\Rentcar\Localiza\Exceptions\UnknowException;
use App\Rentcar\Localiza\Exceptions\VehRes\NoReservationStatusException;
use App\Rentcar\Localiza\Exceptions\VehRes\NoReserveCodeException;
use App\Rentcar\Localiza\ProcessWarning;
use App\Rentcar\Localiza\LocalizaAPI;
use App\Rentcar\Localiza\VehRes\VehRes;
use App\Traits\MultipleAttributeSetter;
use Illuminate\Http\Client\ConnectionException;
use Propaganistas\LaravelPhone\PhoneNumber;
use SimpleXMLElement;

class LocalizaAPIVehRes extends LocalizaAPI implements LocalizaAPIRequest {

    use MultipleAttributeSetter;

    private $fullname;
    private $email;
    private $phone;
    private $reference_token;
    private $return_datetime;
    private $pickup_datetime;
    private $return_location;
    private $pickup_location;
    private $category;
    private $rate_qualifier;

    private $soapAction = '"http://www.opentravel.org/OTA/2003/05:OTA_VehResRQ"';
    private $context;

    public function __construct(array $attributes){
        parent::__construct();

        $this->setAttributes($attributes);

        $this->context = [
            'pickupDateTime'    => $this->pickup_datetime,
            'returnDateTime'    => $this->return_datetime,
            'pickupLocation'    => $this->pickup_location,
            'returnLocation'    => $this->return_location,
            'category'          => $this->category,
            'rateQualifier'     => $this->rate_qualifier,
        ];

    }

    public function getData(): array
    {
        try {
            $filledVehicleReservationXML = $this->getFilledInputXML();
            $response = $this->callAPI($this->soapAction, $filledVehicleReservationXML);
        }
        catch(ConnectionException $th){
            abort(new TimeoutException($this->context));
        }
        catch(\Exception $th){
            abort(new UnknowException($this->context));
        }

        $xml = new SimpleXMLElement($response->body());
        $xml->registerXPathNamespace("OTA",$this->namespace);

        $warnings = (array) $xml->xpath("//OTA:Warning");
        if(count($warnings) > 0) {
            new ProcessWarning($warnings[0], $this->context);
        }

        $reservationNodes = (array) $xml->xpath("//OTA:VehReservation");

        if(count($reservationNodes) > 0){
            $reservationNode = $reservationNodes[0];
            $reservationNode->registerXPathNamespace("A", $this->namespace);
            $reservationData = (new VehRes($reservationNode))->toArray();

            if(!$reservationData['reserveCode'])
                abort(new NoReserveCodeException($this->context));
            if(!$reservationData['reservationStatus'])
                abort(new NoReservationStatusException($this->context));

            return $reservationData;
        }

        return [];
    }

    public function getFilledInputXML() : string {
        $data = array_merge($this->attributes, $this->getAgencyIdentificationData());

        $phone = new PhoneNumber($data['phone']);
        if($phone->isValid()){
            $libPhone = $phone->toLibPhoneObject();
            $data['phone'] = (string) $libPhone->getNationalNumber();
            $data['phone_country_code'] = (string) $libPhone->getCountryCode();
        }
        else {
            $data['phone_country_code'] = "57";
        }

        return view('localiza.inputs.vehres', $data)->render();
    }
}
