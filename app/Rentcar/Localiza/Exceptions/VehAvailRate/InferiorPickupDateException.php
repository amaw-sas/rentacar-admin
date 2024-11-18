<?php

namespace App\Rentcar\Localiza\Exceptions\VehAvailRate;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class InferiorPickupDateException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error'     => 'inferior_pickup_date',
            'message'   => __('localiza.inferior_pickup_date')
        ], $context);
    }
}
