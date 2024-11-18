<?php

namespace App\Rentcar\Localiza\Exceptions\VehRes;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class NoReservationStatusException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error'     =>  'no_reservation_status',
            'message'   =>  __('localiza.no_reservation_status')
        ], $context);
    }
}
