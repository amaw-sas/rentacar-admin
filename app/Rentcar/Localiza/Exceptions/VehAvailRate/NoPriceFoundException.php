<?php

namespace App\Rentcar\Localiza\Exceptions\VehAvailRate;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class NoPriceFoundException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error'     => 'prices_not_found',
            'message'   => __('localiza.prices_not_found')
        ], $context);
    }
}
