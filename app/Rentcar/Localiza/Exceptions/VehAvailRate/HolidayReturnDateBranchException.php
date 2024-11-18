<?php

namespace App\Rentcar\Localiza\Exceptions\VehAvailRate;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class HolidayReturnDateBranchException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error' => 'holiday_return_date_branch',
            'message' => __('localiza.holiday_return_date_branch')
        ], $context);
    }
}
