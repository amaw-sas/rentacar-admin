<?php

namespace App\Rentcar\Localiza\Exceptions\VehAvailRate;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class OutOfScheduleReturnHourException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error'     => 'out_of_schedule_return_hour_error',
            'message'   => __('localiza.out_of_schedule_return_hour_error'),
        ], $context);
    }
}
