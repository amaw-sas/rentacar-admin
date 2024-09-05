<?php

namespace App\Rentcar\Localiza\VehRes;

use SimpleXMLElement;
use Illuminate\Contracts\Support\Arrayable;
use App\Rentcar\Localiza\Exceptions\NoDataFoundException;

class VehRes implements Arrayable {

    public $node;

    public function __construct(SimpleXMLElement $node){
        $this->node = $node;
    }

    private function getReserveCode(): array {
        $node = $this->node->xpath('.//A:ConfID[@Type="14"]');
        $result = [
            'reserveCode' => null,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['reserveCode'] = (string) $node->attributes()->ID;
        }
        else abort(new NoDataFoundException);

        return $result;
    }

    private function getReservationStatus(): array {
        $node = $this->node->xpath(".//A:VehReservation");
        $result = [
            'reservationStatus' => null,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['reservationStatus'] = (string) $node->attributes()->ReservationStatus;
        }
        else abort(new NoDataFoundException);

        return $result;
    }

    public function toArray(): array{
        return array_merge([
            $this->getReserveCode()
        ]);
    }
}
