<?php

namespace App\Http\Resources;

use App\Rentcar\Whatsapp;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'fullname'  =>  $this->fullname,
            'identification_type'  =>  $this->identification_type,
            'identification'  =>  $this->identification,
            'phone'  =>  $this->phone,
            'email'  =>  $this->email,
            'category'  =>  $this->formattedCategory(),
            'pickup_location'  =>  $this->formattedPickupPlace(),
            'return_location'  =>  $this->formattedReturnPlace(),
            'pickup_date'  =>  $this->formattedPickupDate(),
            'pickup_hour'  =>  $this->formattedPickupHour(),
            'return_date'  =>  $this->formattedReturnDate(),
            'return_hour'  =>  $this->formattedReturnHour(),
            'selected_days'  =>  $this->selected_days,
            'extra_hours'  =>  $this->extra_hours,
            'extra_hours_price'  =>  $this->formattedExtraHoursPrice(),
            'coverage_days'  =>  $this->coverage_days,
            'coverage_price'  =>  $this->formattedCoveragePrice(),
            'tax_fee'  =>  $this->formattedTaxFee(),
            'iva_fee'  =>  $this->formattedIVAFee(),
            'total_price'  =>  $this->formattedTotalPrice(),
            'total_price_localiza'  =>  $this->formattedTotalPriceLocaliza(),
            'user'  =>  $this->user,
            'reserve_code'  =>  $this->reserve_code,
            'franchise'  =>  $this->franchiseObject->name,
            'status'  =>  $this->status,
            'created_at'  =>  $this->created_at->format('Y-m-d H:i a'),
            'updated_at'  =>  $this->updated_at->format('Y-m-d H:i a'),
            'edit_url'      => route('reservations.edit', $this->id),
            'delete_url'      => route('reservations.destroy', $this->id),
            'whatsapp_link' => Whatsapp::generateLink($this->phone)
        ];
    }
}
