<?php

namespace App\Http\Controllers\Dummy;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Rentcar\Localiza\VehAvailRate\LocalizaAPIVehAvailRate;

class DummyVehicleAvailableRateController extends Controller
{
    public function index(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function no_categories_available(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-no-available-categories-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function fecha_devolucion_fuera_horario(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-out-schedule-return-date-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function hora_devolucion_fuera_horario(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-out-schedule-return-hour-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function fecha_recogida_fuera_horario(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-out-schedule-pickup-date-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function hora_recogida_fuera_horario(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-out-schedule-pickup-hour-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function error_desconocido(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-unknown-error-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function horas_extra(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-extra-hours-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function tasa_retorno(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-return-fee-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }

    public function timeout(){
        $raw_xml = view('localiza.tests.responses.vehavailrate.vehavailrate-xml');

        Http::fake([
            '*' =>  Http::response($raw_xml, 408)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        return $localiza->getData();
    }


}
