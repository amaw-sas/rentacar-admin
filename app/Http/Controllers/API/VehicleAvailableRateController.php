<?php

namespace App\Http\Controllers\API;

use App\Rentcar\Localiza\VehAvailRate\LocalizaAPIVehAvailRate;
use App\Http\Requests\VehicleAvailableRateRequest;
use App\Http\Controllers\Controller;

class VehicleAvailableRateController extends Controller
{
    public function __invoke(VehicleAvailableRateRequest $request)
    {
        $pickupLocation = $request->input('pickupLocation');
        $returnLocation = $request->input('returnLocation');
        $pickupDateTime = $request->input('pickupDateTime');
        $returnDateTime = $request->input('returnDateTime');

        $payload = [
            "pickupLocation" => $pickupLocation,
            "returnLocation" => $returnLocation,
            "pickupDateTime" => $pickupDateTime,
            "returnDateTime" => $returnDateTime,
        ];


        $localizaApi = new LocalizaAPIVehAvailRate(
            $payload
        );

        return $localizaApi->getData();

    }



}
