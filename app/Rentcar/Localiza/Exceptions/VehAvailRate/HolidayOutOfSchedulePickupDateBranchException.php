<?php

namespace App\Rentcar\Localiza\Exceptions\VehAvailRate;

use App\Rentcar\Localiza\Exceptions\LocalizaResponseException;

class HolidayOutOfSchedulePickupDateBranchException extends LocalizaResponseException {
    public function __construct($context = []){
        parent::__construct([
            'error' => 'holiday_out_of_schedule_pickup_date_branch',
            'message' => __('localiza.holiday_out_of_schedule_pickup_date_branch')
        ], $context);
    }
}
