<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait FormatTrait {

    public function moneyFormat($number){
        $fmt = numfmt_create( 'es_CO', \NumberFormatter::CURRENCY );
        numfmt_set_attribute($fmt, \NumberFormatter::FRACTION_DIGITS, 0);
        return numfmt_format_currency($fmt, $number, "COP");
    }

    public function dateFormat($date, $format = "Y-m-d", $output_format = "LL"){
        if($date instanceof Carbon){
            return $date->locale('es')->isoFormat($output_format);
        }
        else if(is_string($date)) {
            try {
                return Carbon::createFromFormat($format, $date)->locale('es')->isoFormat("LL");
            } catch (\Throwable $th) {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            }
        }
    }

    public function hourFormat($hour, $format = "H:i"){
        if($hour instanceof Carbon){
            return $hour->locale('es')->format('h:i a');
        }
        else if(is_string($hour)) {
            try {
                return Carbon::createFromFormat($format, $hour)->format("h:i a");
            } catch (\Throwable $th) {
                return Carbon::createFromFormat("H:i:s", $hour)->format("h:i a");
            }
        }
    }
}
