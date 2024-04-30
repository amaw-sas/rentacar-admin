<?php

namespace App\Traits;

use App\Models\Branch;

trait ReservationFormatTrait {

    use FormatTrait;

    public function formattedCategory(){
        return $this->categoryObject->category;
    }

    public function formattedPickupPlace(){
        return $this->formattedBranch($this->pickupLocation);
    }

    public function formattedReturnPlace(){
        return $this->formattedBranch($this->returnLocation);
    }

    public function formattedExtraHoursPrice(){
        return $this->moneyFormat($this->extra_hours_price);
    }

    public function formattedCoveragePrice(){
        return $this->moneyFormat($this->coverage_price);
    }

    public function formattedTaxFee(){
        return $this->moneyFormat($this->tax_fee);
    }

    public function formattedIVAFee(){
        return $this->moneyFormat($this->tax_fee);
    }

    public function formattedTotalPrice(){
        return $this->moneyFormat($this->total_price);
    }

    public function formattedTotalPriceLocaliza(){
        return $this->moneyFormat($this->total_price_localiza);
    }

    public function formattedPickupDate(){
        return $this->dateFormat($this->pickup_date);
    }

    public function formattedReturnDate(){
        return $this->dateFormat($this->return_date);
    }

    public function formattedPickupHour(){
        return $this->hourFormat($this->pickup_hour);
    }

    public function formattedReturnHour(){
        return $this->hourFormat($this->return_hour);
    }

    private function formattedBranch(Branch $branch){
        return "{$branch->name} - {$branch->code}";
    }
}
