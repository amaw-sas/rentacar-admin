<?php

namespace App\Http\Resources\DataProvider;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMonthPriceDataProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            '1k_kms'    =>  $this->{'1k_kms'},
            '2k_kms'    =>  $this->{'2k_kms'},
            '3k_kms'    =>  $this->{'3k_kms'},
            'init_date' =>  $this->init_date->format('d-m-Y'),
            'end_date'  =>  $this->end_date->format('d-m-Y'),
            'total_insurance_price'  =>  $this->total_insurance_price ?? 0,
            'one_day_price'  =>  $this->one_day_price ?? 0,
        ];
    }
}
