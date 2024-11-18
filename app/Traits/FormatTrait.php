<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use Propaganistas\LaravelPhone\PhoneNumber;

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

    public function phoneFormat($phone, $format = "international"){
        try {
            $phone_number = new PhoneNumber($phone);
            if($phone_number){
                $formatted_phone = match($format) {
                    "international" => $phone_number->formatInternational(),
                    "national" => $phone_number->formatNational(),
                };

       return $formatted_phone;
            }
            else return $phone;
        } catch (\Throwable $th) {
            return $phone;
        }
        finally {
            return $phone;
        }
    }
}
