<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationCreateResource extends JsonResource
{

    public $model = Reservation::class;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'fullname'  =>  $this->fullname,
            'identification_type'  =>  $this->identification_type,
            'identification'  =>  $this->identification,
            'phone'  =>  $this->phone,
            'email'  =>  $this->email,
            'category'  =>  $this->category,
            'pickup_location'  =>  $this->pickup_location,
            'return_location'  =>  $this->return_location,
            'pickup_date'  =>  ($this->pickup_date) ? $this->pickup_date : Carbon::now()->format('Y-m-d'),
            'pickup_hour'  =>  ($this->pickup_hour) ? $this->pickup_hour : Carbon::now()->setHour(8)->setMinute(00)->format('H:m'),
            'return_date'  =>  ($this->return_date) ? $this->return_date : Carbon::now()->addDays(7)->format('Y-m-d'),
            'return_hour'  =>  ($this->return_hour) ? $this->return_hour : Carbon::now()->setHour(8)->setMinute(00)->format('H:m'),
            'selected_days'  =>  $this->selected_days,
            'extra_hours'  =>  $this->extra_hours,
            'extra_hours_price'  =>  $this->extra_hours_price,
            'coverage_days'  =>  $this->coverage_days,
            'coverage_price'  =>  $this->coverage_price,
            'tax_fee'  =>  $this->tax_fee,
            'iva_fee'  =>  $this->iva_fee,
            'total_price'  =>  $this->total_price,
            'total_price_localiza'  =>  $this->total_price_localiza,
            'user'  =>  $this->user,
            'reserve_code'  =>  $this->reserve_code,
            'status'  =>  $this->status,
            'franchise'  =>  $this->franchise,
        ];

        if($this->id)
            $resource['id'] = $this->id;

        return $resource;
    }
}
