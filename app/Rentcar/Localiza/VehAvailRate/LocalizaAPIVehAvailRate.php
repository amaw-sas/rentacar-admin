<?php

namespace App\Rentcar\Localiza\VehAvailRate;

use App\Jobs\LogVehAvailableRatesQueryJob;
use App\Rentcar\Localiza\Contracts\LocalizaAPIRequest;
use App\Rentcar\Localiza\Exceptions\VehAvailRate\NoPriceFoundException;
use App\Rentcar\Localiza\Exceptions\TimeoutException;
use App\Rentcar\Localiza\VehAvailRate\VehAvail;
use App\Rentcar\Localiza\LocalizaAPI;
use App\Rentcar\Localiza\ProcessWarning;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\MultipleAttributeSetter;
use SimpleXMLElement;

class LocalizaAPIVehAvailRate extends LocalizaAPI implements LocalizaAPIRequest {

    use MultipleAttributeSetter;

    private $pickupLocation;
    private $returnLocation;
    private $pickupDateTime;
    private $returnDateTime;
    private $currencyExchange;
    private $exchangeCOP;

    private $soapAction = '"http://www.opentravel.org/OTA/2003/05:OTA_VehAvailRateRQ"';
    private $context;


    public function __construct(array $attributes)
    {
        parent::__construct();

        $this->setAttributes($attributes);

        $this->currencyExchange = false;
        $this->exchangeCOP = Cache::get('cop-exchange',4100);
        $this->context = [
            'pickupLocation'    =>  $this->pickupLocation,
            'returnLocation'    =>  $this->returnLocation,
            'pickupDateTime'    =>  $this->pickupDateTime,
            'returnDateTime'    =>  $this->returnDateTime,
        ];
    }

    public function getData() : array {
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

    public function getFilledInputXML(): string {
        $data = array_merge($this->attributes, $this->getAgencyIdentificationData());

        return view('localiza.inputs.vehavailrate', $data)->render();
    }
}
