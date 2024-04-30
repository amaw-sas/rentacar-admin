<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait FormatTrait {

    public function moneyFormat($number){
        $fmt = numfmt_create( 'es_CO', \NumberFormatter::CURRENCY );
        return numfmt_format_currency($fmt, $number, "COP");
    }

    public function dateFormat($date, $format = "Y-m-d"){
        try {
            return Carbon::createFromFormat($format, $date)->locale('es')->isoFormat("LL");
        } catch (\Throwable $th) {
            return Carbon::createFromFormat($format, $date)->format('Y-m-d');
        }
    }

    public function hourFormat($hour, $format = "H:i"){
        try {
            return Carbon::createFromFormat($format, $hour)->format("H:i a");
        } catch (\Throwable $th) {
            return Carbon::createFromFormat("H:i:s", $hour)->format("H:i a");
        }
    }
}
