<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ReservationCollection extends RentacarResourceCollection
{
    public $orderByCols = ['created_at', 'asc'];

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'headers'   => [
                [
                    "text"      =>  "CREADO",
                    "value"     =>  "created_at",
                    "fixed"     =>  true,
                ],
                [
                    "text"      =>  "NOMBRE",
                    "value"     =>  "fullname",
                    "fixed"     =>  true,
                ],
                [
                    "text"      =>  "ID",
                    "value"     =>  "identification",
                ],
                [
                    "text"      =>  "TELÉFONO",
                    "value"     =>  "phone",
                ],
                [
                    "text"      =>  "EMAIL",
                    "value"     =>  "email",
                ],
                [
                    "text"      =>  "CÓDIGO RESERVA",
                    "value"     =>  "reserve_code",
                ],
                [
                    "text"      =>  "CATEGORÍA",
                    "value"     =>  "category",
                ],
                [
                    "text"      =>  "FRANQUICIA",
                    "value"     =>  "franchise",
                ],
                [
                    "text"      =>  "ESTADO",
                    "value"     =>  "status",
                ],
                [
                    "text"  =>  "OPERACIONES",
                    "value" =>  "operation"
                ]
            ],
            'items' =>  $this->collection
        ];
    }
}
