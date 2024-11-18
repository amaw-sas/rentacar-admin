<?php

namespace App\Rentcar\Localiza\VehRetRes;

use SimpleXMLElement;
use Illuminate\Contracts\Support\Arrayable;
use App\Rentcar\Localiza\Exceptions\NoDataFoundException;

class VehRetRes implements Arrayable {

    public $node;

    public function __construct(SimpleXMLElement $node){
        $this->node = $node;
    }

    private function getReservationStatus(): array {
        $result = [
            'reservationStatus' => null,
        ];

        $result['reservationStatus'] = (string) $this->node->attributes()->ReservationStatus;

        return $result;
    }

    public function toArray(): array{
        return array_merge(
            $this->getReservationStatus()
        );
    }
}
