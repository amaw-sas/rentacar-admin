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
            'category'  =>  $this->formatted_category,
            'pickup_location'  =>  $this->formatted_pickup_place,
            'return_location'  =>  $this->formatted_return_place,
            'pickup_date'  =>  $this->formatted_pickup_date,
            'pickup_hour'  =>  $this->formatted_pickup_hour,
            'return_date'  =>  $this->formatted_return_date,
            'return_hour'  =>  $this->formatted_return_hour,
            'selected_days'  =>  $this->selected_days,
            'extra_hours'  =>  $this->extra_hours,
            'extra_hours_price'  =>  $this->formatted_extra_hours_price,
            'coverage_days'  =>  $this->coverage_days,
            'coverage_price'  =>  $this->formatted_coverage_price,
            'return_fee'  =>  $this->formatted_return_fee,
            'tax_fee'  =>  $this->formatted_tax_fee,
            'iva_fee'  =>  $this->formatted_iva_fee,
            'total_price'  =>  $this->formatted_total_price,
            'total_price_to_pay'  =>  $this->formatted_total_price_to_pay,
            'total_price_localiza'  =>  ($this->total_price_localiza > 0) ? $this->formatted_total_price_localiza : null,
            'user'  =>  $this->user,
            'reserve_code'  =>  $this->reserve_code,
            'monthly_mileage'  =>  $this->monthly_mileage,
            'total_insurance'  => (bool)  $this->total_insurance,
            'franchise'  =>  $this->franchiseObject->name ?? "",
            'status'  =>  $this->status,
            'created_at'  =>  $this->created_at->format('Y-m-d H:i a'),
            'updated_at'  =>  $this->updated_at->format('Y-m-d H:i a'),
            'email_preview_url'      => route('reservations.emailPreview', $this->id),
            'edit_url'      => route('reservations.edit', $this->id),
            'delete_url'      => route('reservations.destroy', $this->id),
            'whatsapp_link' => Whatsapp::generateLink($this->phone)
        ];
    }
}
