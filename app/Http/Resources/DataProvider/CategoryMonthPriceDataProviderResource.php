<?php

namespace App\Http\Resources\DataProvider;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMonthPriceDataProviderResource extends JsonResource
{
    protected $category;
    protected $totalCoverageMonthSet;

    public function __construct($category_month_price) {

        $this->category = $category_month_price->category->identification;;

        $this->totalCoverageMonthSet = [
            'C' => (int) config('localiza.totalCoverageMonthCategoryC'),
            'F' => (int) config('localiza.totalCoverageMonthCategoryF'),
            'FX' => (int) config('localiza.totalCoverageMonthCategoryFX'),
            'FL' => (int) config('localiza.totalCoverageMonthCategoryFL'),
            'FU' => (int) config('localiza.totalCoverageMonthCategoryFU'),
            'GC' => (int) config('localiza.totalCoverageMonthCategoryGC'),
            'GL' => (int) config('localiza.totalCoverageMonthCategoryGL'),
            'G4' => (int) config('localiza.totalCoverageMonthCategoryG4'),
            'GR' => (int) config('localiza.totalCoverageMonthCategoryGR'),
            'GY' => (int) config('localiza.totalCoverageMonthCategoryGY'),
            'LE' => (int) config('localiza.totalCoverageMonthCategoryLE'),
            'VP' => (int) config('localiza.totalCoverageMonthCategoryVP'),
        ];
        parent::__construct($category_month_price);
    }

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
            'total_insurance_price'  =>  $this->getTotalCoverageMonthUnitCharge($this->category) ?? 0,
            'one_day_price'  =>  $this->one_day_price ?? 0,
        ];
    }

    public function getTotalCoverageMonthUnitCharge(string $category): int {

        if(array_key_exists($category, $this->totalCoverageMonthSet))
            return $this->totalCoverageMonthSet[$category];

        return 100;
    }
}
