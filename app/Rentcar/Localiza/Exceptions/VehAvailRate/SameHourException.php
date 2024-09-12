<?php

namespace App\Rentcar\Localiza\Exceptions\VehAvailRate;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class SameHourException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error'     => 'same_hour',
            'message'   => __('localiza.same_hour')
        ], $context);
    }
}
