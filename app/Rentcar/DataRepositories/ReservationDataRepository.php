<?php

namespace App\Rentcar\DataRepositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;

class ReservationDataRepository extends DataRepository {

    public $model = Reservation::class;

    public array $orderByCols = ['created_at','desc'];


}
