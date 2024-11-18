<?php

namespace App\Rentcar\Localiza\VehRetRes;

use App\Rentcar\Localiza\Contracts\LocalizaAPIRequest;
use App\Rentcar\Localiza\Exceptions\TimeoutException;
use App\Rentcar\Localiza\Exceptions\UnknowException;
use App\Rentcar\Localiza\Exceptions\VehRes\NoReservationStatusException;
use App\Rentcar\Localiza\Exceptions\VehRes\NoReserveCodeException;
use App\Rentcar\Localiza\ProcessWarning;
use App\Rentcar\Localiza\LocalizaAPI;
use App\Rentcar\Localiza\VehRetRes\VehRetRes;
use App\Traits\MultipleAttributeSetter;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class LocalizaAPIVehRetRes extends LocalizaAPI implements LocalizaAPIRequest {

    use MultipleAttributeSetter;

    private string $reserve_code;

    private string $soapAction = '"http://www.opentravel.org/OTA/2003/05:OTA_VehRetResRQ"';
    private array $context;

    public function __construct(array $attributes){
        parent::__construct();

        $this->setAttributes($attributes);

        $this->context = [
            'reserve_code'          => $this->reserve_code,
        ];

    }

    public function getData(): array
    {
        try {
            $filledVehicleReservationXML = $this->getFilledInputXML();
            Log::info($filledVehicleReservationXML);
            $response = $this->callAPI($this->soapAction, $filledVehicleReservationXML);
        }
        catch(ConnectionException $th){
            Log::error($th);
            abort(new TimeoutException($this->context));
        }
        catch(\Exception $th){
            Log::error($th);
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
            $reservationData = (new VehRetRes($reservationNode))->toArray();

            if(!$reservationData['reservationStatus'])
                abort(new NoReservationStatusException($this->context));

            return $reservationData;
        }

        return [];
    }

    public function getFilledInputXML() : string {
        $data = array_merge($this->attributes, $this->getAgencyIdentificationData());

        return view('localiza.inputs.vehretres', $data)->render();
    }
}
