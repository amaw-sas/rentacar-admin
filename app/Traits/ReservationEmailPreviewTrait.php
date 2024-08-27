<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ReservationEmailPreviewTrait {

    use FormatTrait;

    public function formattedIncludedFees(): Attribute {
        return Attribute::make(
            get: fn () => match(true){
                $this->selected_days == 30 && $this->total_insurance => "Kilometraje: {$this->monthly_mileage}, Seguro total",
                $this->selected_days == 30 && !$this->total_insurance => "Kilometraje: {$this->monthly_mileage}, Seguro básico",
                $this->selected_days < 30 && $this->total_insurance => "Kilometraje ilimitado, Seguro total",
                $this->selected_days < 30 && !$this->total_insurance => "Kilometraje ilimitado, Seguro básico",
            },
        );
    }

    public function originalVehicleUnitPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->categoryObject?->monthPrices?->first()?->one_day_price ?? 0,
        );
    }

    public function formattedOriginalVehicleUnitPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->original_vehicle_unit_price),
        );
    }

    public function ivaFeeFromLocalizaPrice(): Attribute {
        $totalPrice = $this->total_price_to_pay;
        $ivaPercentage = config('localiza.ivaPercentage');
        $totalPriceMinusIvaAmount = round($totalPrice / (1 + ($ivaPercentage / 100)));
        $ivaAmount = $totalPrice - $totalPriceMinusIvaAmount;

        return Attribute::make(
            get: fn () => ($totalPrice) ? $ivaAmount : null,
        );
    }

    public function formattedIvaFeeFromLocalizaPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->iva_fee_from_localiza_price)
        );
    }

    public function taxFeeFromLocalizaPrice(): Attribute {
        $totalPrice = $this->total_price_to_pay;
        $ivaFee = $this->iva_fee_from_localiza_price;
        $totalPriceMinusTaxFee = ($totalPrice - $ivaFee) / 11;
        $taxFeeAmount = $totalPriceMinusTaxFee;

        return Attribute::make(
            get: fn () => ($totalPrice && $ivaFee) ? $taxFeeAmount : null,
        );
    }

    public function formattedTaxFeeFromLocalizaPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->tax_fee_from_localiza_price)
        );
    }

    public function subtotalFromLocalizaPrice(): Attribute {
        $totalPrice = $this->total_price_to_pay;
        $ivaFee = $this->iva_fee_from_localiza_price;
        $taxFee = $this->tax_fee_from_localiza_price;

        return Attribute::make(
            get: fn () => ($totalPrice && $ivaFee && $taxFee) ? $totalPrice - $ivaFee - $taxFee : null,
        );
    }

    public function formattedSubtotalFromLocalizaPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->subtotal_from_localiza_price)
        );
    }

    public function basePriceFromLocalizaPrice(): Attribute {
        $subtotal = $this->subtotal_from_localiza_price;
        $returnFee = $this->return_fee;
        $extraHoursPrice = $this->extra_hours_price;

        return Attribute::make(
            get: fn () => ($subtotal) ? $subtotal - $returnFee - $extraHoursPrice : null,
        );
    }

    public function formattedBasePriceFromLocalizaPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->base_price_from_localiza_price)
        );
    }

    public function dailyBasePriceFromLocalizaPrice(): Attribute {
        $basePrice = $this->base_price_from_localiza_price;
        $selectedDays = $this->selected_days;

        return Attribute::make(
            get: fn () => ($basePrice && $selectedDays) ? round($basePrice / $selectedDays, 1) : null,
        );
    }

    public function formattedDailyBasePriceFromLocalizaPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->daily_base_price_from_localiza_price)
        );
    }

    public function discountPercentageFromLocalizaPrice(): Attribute {
        $dailyBasePrice = $this->daily_base_price_from_localiza_price;
        $originalDailyBasePrice = $this->original_vehicle_unit_price;

        return Attribute::make(
            get: fn () => ($dailyBasePrice && $originalDailyBasePrice) ? $this->getDiscountFromAmounts($dailyBasePrice, $originalDailyBasePrice) : null,
        );
    }

    public function formattedDiscountPercentageFromLocalizaPrice(): Attribute {
        return Attribute::make(
            get: fn () => "{$this->discount_percentage_from_localiza_price}%"
        );
    }

    public function discountAmountFromLocalizaPrice(): Attribute {
        $dailyBasePrice = $this->daily_base_price_from_localiza_price;
        $originalDailyBasePrice = $this->original_vehicle_unit_price;

        return Attribute::make(
            get: fn () => ($dailyBasePrice && $originalDailyBasePrice) ? $originalDailyBasePrice - $dailyBasePrice : null,
        );
    }

    public function formattedDiscountAmountFromLocalizaPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->daily_base_price_from_localiza_price)
        );
    }

    private function discountPercentageFromAmount(int $amount, int $percentage): int {
        $discountedAmount = $this->applyPercentageToAmount($amount, $percentage);
        return $amount - $discountedAmount;
    }

    private function applyPercentageToAmount(int $amount, int $percentage) : int {
        return round($amount * ($percentage / 100), 1);
    }

    private function getDiscountFromAmounts(int $initialAmount, int $finalAmount): float {
        return round((($finalAmount - $initialAmount) / $finalAmount) * 100, 1, 1);
    }


}
