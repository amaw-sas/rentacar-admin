<?php

namespace App\Rentcar\Localiza\Exceptions\VehAvailRate;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class HolidayPickupDateBranchException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error' => 'holiday_pickup_date_branch',
            'message' => __('localiza.holiday_pickup_date_branch')
        ], $context);
    }
}
