<?php

namespace App\Rentcar\Localiza\VehRes;

use App\Rentcar\Localiza\LocalizaAPI;
use App\Rentcar\Localiza\VehRes\VehRes;
use SimpleXMLElement;

class LocalizaAPIVehRes extends LocalizaAPI {

    private $name;
    private $email;
    private $phoneNumber;
    private $referenceToken;
    private $returnDateTime;
    private $pickupDateTime;
    private $returnLocation;
    private $pickupLocation;
    private $category;
    private $rateQualifier;


    private $soapAction = '"http://www.opentravel.org/OTA/2003/05:OTA_VehResRQ"';
    private $context;

    public function __construct(
        $name,
        $email,
        $phoneNumber,
        $phonePrefix,
        $documentType,
        $document,
        $referenceToken,
        $returnDateTime,
        $pickupDateTime,
        $returnLocation,
        $pickupLocation,
        $category,
        $rateQualifier
    ){
        parent::__construct();

        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->referenceToken = $referenceToken;
        $this->returnDateTime = $returnDateTime;
        $this->pickupDateTime = $pickupDateTime;
        $this->returnLocation = $returnLocation;
        $this->pickupLocation = $pickupLocation;
        $this->category = $category;
        $this->rateQualifier = $rateQualifier;

    }

    private function getFilledInputXML(){
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
