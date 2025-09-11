<?php

namespace App\Listeners\SendLocalizaReservationRequest;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLocalizaReservationRequestListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 2;

}
