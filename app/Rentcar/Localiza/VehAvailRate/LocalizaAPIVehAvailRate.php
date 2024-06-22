<?php

namespace App\Rentcar\Localiza\VehAvailRate;

use App\Jobs\LogVehAvailableRatesQueryJob;
use App\Rentcar\Localiza\Exceptions\NoPriceFoundException;
use App\Rentcar\Localiza\Exceptions\TimeoutException;
use App\Rentcar\Localiza\VehAvailRate\VehAvail;
use App\Rentcar\Localiza\LocalizaAPI;
use App\Rentcar\Localiza\ProcessWarning;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Exceptions\HttpResponseException;
use SimpleXMLElement;

class LocalizaAPIVehAvailRate extends LocalizaAPI {

    private $pickupLocation;
    private $returnLocation;
    private $pickupDateTime;
    private $returnDateTime;
    private $currencyExchange;
    private $exchangeCOP;
    private $soapAction = '"http://www.opentravel.org/OTA/2003/05:OTA_VehAvailRateRQ"';
    private $context;


    public function __construct($pickupLocation, $returnLocation, $pickupDateTime, $returnDateTime, $currencyExchange = false)
    {
        parent::__construct();

        $this->pickupLocation = $pickupLocation;
        $this->returnLocation = $returnLocation;
        $this->pickupDateTime = $pickupDateTime;
        $this->returnDateTime = $returnDateTime;
        $this->currencyExchange = $currencyExchange;
        $this->exchangeCOP = Cache::get('cop-exchange',4100);
        $this->context = [
            'pickupLocation'    =>  $this->pickupLocation,
            'returnLocation'    =>  $this->returnLocation,
            'pickupDateTime'    =>  $this->pickupDateTime,
            'returnDateTime'    =>  $this->returnDateTime,
        ];
    }

    public function getData(){
        try {
            $filledVehicleAvailableRateXML = $this->getFilledInputXML();
            $response = $this->callAPI($this->soapAction, $filledVehicleAvailableRateXML);
        }
        catch(\Exception $th){
            dispatch(new LogVehAvailableRatesQueryJob(408, null, $this->context, []));
            abort(new TimeoutException($this->context));
        }

        $xml = new SimpleXMLElement($response->body());
        $xml->registerXPathNamespace("OTA",$this->namespace);

        $data = [];

        $warnings = (array) $xml->xpath("//OTA:Warning");
        if(count($warnings) > 0) {
            $job = new LogVehAvailableRatesQueryJob($response->status(), $response->body(), $this->context, $data);
            new ProcessWarning($warnings[0], $this->context, $job);
        }

        //code...
        $vehiclesNodes = (array) $xml->xpath("//OTA:VehAvail");
        if(count($vehiclesNodes) > 0){

            foreach($vehiclesNodes as $vehicleNode){
                $vehicleNode->registerXPathNamespace("A", $this->namespace);
                $vehicleData = (new VehAvail($vehicleNode))->toArray();

                /** remove categories temporaly */
                if($vehicleData["categoryCode"] == "PX" || $vehicleData["categoryCode"] == "P" || $vehicleData["categoryCode"] == "U")
                    continue;

                /**
                 * when there's a result containing armenia or ibague branch and category vp, pass on
                 * armenia AARME
                 * ibague ACIBG
                 * category: VP
                 */
                if(($vehicleData["categoryCode"] == "VP" && $this->pickupLocation == "AARME") || ($vehicleData["categoryCode"] == "VP" && $this->pickupLocation == "ACIBG"))
                    continue;

                if(!$vehicleData['totalAmount']){

                    abort(new NoPriceFoundException($this->context));
                }

                $data[] = $vehicleData;

            }

            dispatch(new LogVehAvailableRatesQueryJob($response->status(), $response->body(), $this->context, $data));

            return $data;
        }

        return [];
    }



    private function getFilledInputXML() {
        return <<<XML
        <s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
            <s:Body
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xmlns:xsd="http://www.w3.org/2001/XMLSchema">
                <OTA_VehAvailRate
                    xmlns="http://tempuri.org/">
                    <OTA_VehAvailRateRQ PrimaryLangID="esp"
                        RetransmissionIndicator="false" TransactionStatusCode="Start" Version="0"
                        TimeStamp="0001-01-01T00:00:00" EchoToken="{$this->token}"
                        MaxPerVendorInd="false">
                        <POS>
                            <Source ISOCountry="CO">
                                <RequestorID
                                    ID="{$this->requestorID}" Type="5" xmlns="http://www.opentravel.org/OTA/2003/05" />
                            </Source>
                        </POS>
                        <VehAvailRQCore>
                            <VehRentalCore PickUpDateTime="{$this->pickupDateTime}"
                                ReturnDateTime="{$this->returnDateTime}"
                                xmlns="http://www.opentravel.org/OTA/2003/05">
                                <PickUpLocation
                                    LocationCode="{$this->pickupLocation}" CodeContext="internal code" />
                                <ReturnLocation
                                    LocationCode="{$this->returnLocation}" CodeContext="internal code" />
                            </VehRentalCore>
                            <Customer
                                xmlns="http://www.opentravel.org/OTA/2003/05">
                                <Primary>
                                    <CitizenCountryName
                                        Code="CO" />
                                </Primary>
                            </Customer>
                        </VehAvailRQCore>
                    </OTA_VehAvailRateRQ>
                </OTA_VehAvailRate>
            </s:Body>
        </s:Envelope>
        XML;
    }
}
