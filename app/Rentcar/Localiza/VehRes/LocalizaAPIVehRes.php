<?php

namespace App\Rentcar\Localiza\VehRes;

use App\Rentcar\Localiza\Contracts\LocalizaAPIRequest;
use App\Rentcar\Localiza\Exceptions\TimeoutException;
use App\Rentcar\Localiza\Exceptions\VehRes\NoReservationStatusException;
use App\Rentcar\Localiza\Exceptions\VehRes\NoReserveCodeException;
use App\Rentcar\Localiza\ProcessWarning;
use App\Rentcar\Localiza\LocalizaAPI;
use App\Rentcar\Localiza\VehRes\VehRes;
use App\Traits\MultipleAttributeSetter;
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
        catch(\Exception $th){
            abort(new TimeoutException($this->context));
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
        return <<<XML
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:ns="http://www.opentravel.org/OTA/2003/05">
                    <soapenv:Body>
                        <tem:OTA_VehRes xmlns="http://tempuri.org/">
                            <tem:OTA_VehResRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" EchoToken="{$this->token}" Target="Test" PrimaryLangID="por">
                                <tem:POS>
                                    <tem:Source ISOCountry="CO">
                                        <ns:RequestorID ID="{$this->requestorID}" Type="5" xmlns="http://www.opentravel.org/OTA/2003/05" />
                                    </tem:Source>
                                </tem:POS>
                                <tem:VehResRQCore xmlns="http://tempuri.org/">
                                    <ns:VehRentalCore PickUpDateTime="{$this->pickupDateTime}" ReturnDateTime="{$this->returnDateTime}" xmlns="http://www.opentravel.org/OTA/2003/05">
                                        <ns:PickUpLocation LocationCode="{$this->pickupLocation}" CodeContext="internal code"/>
                                        <ns:ReturnLocation LocationCode="{$this->returnLocation}" CodeContext="internal code"/>
                                    </ns:VehRentalCore>
                                    <ns:Customer>
                                        <ns:Primary>
                                            <ns:PersonName>
                                                <ns:Surname>{$this->name}</ns:Surname>
                                            </ns:PersonName>
                                            <ns:Email>{$this->email}</ns:Email>
                                            <ns:CitizenCountryName Code="CO" />
                                            <ns:Telephone PhoneNumber="{$this->phoneNumber}"/>
                                        </ns:Primary>
                                    </ns:Customer>
                                    <ns:VehPref CodeContext="internal code" Code="{$this->category}" />
                                    <ns:RateQualifier RateQualifier="{$this->rateQualifier}" />
                                </tem:VehResRQCore>
                                <tem:VehResRQInfo>
                                    <ns:RentalPaymentPref PaymentType="2">
                                        <ns:Voucher ValueType="2" />
                                    </ns:RentalPaymentPref>
                                    <ns:Reference Type="41" ID="{$this->referenceToken}"></ns:Reference>
                                </tem:VehResRQInfo>
                            </tem:OTA_VehResRQ>
                        </tem:OTA_VehRes>
                    </soapenv:Body>
                </soapenv:Envelope>
            XML;
    }
}
