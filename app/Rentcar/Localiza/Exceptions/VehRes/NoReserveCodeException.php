<?php

namespace App\Rentcar\Localiza\Exceptions\VehRes;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class NoReserveCodeException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error'     =>  'no_reserve_code',
            'message'   =>  __('localiza.no_reserve_code')
        ], $context);
    }
}
