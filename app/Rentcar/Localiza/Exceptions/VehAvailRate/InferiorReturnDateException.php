<?php

namespace App\Rentcar\Localiza\Exceptions\VehAvailRate;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class InferiorReturnDateException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error'     => 'inferior_return_date',
            'message'   => __('localiza.inferior_return_date')
        ], $context);
    }
}
