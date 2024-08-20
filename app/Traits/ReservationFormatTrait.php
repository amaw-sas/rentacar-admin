<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\IdentificationType;
use App\Models\Branch;

trait ReservationFormatTrait {

    use FormatTrait;

    public function shortIdentificationType(): Attribute {
        $identificationType = IdentificationType::from($this->identification_type);

        return Attribute::make(
            get: fn () => match(true){
                ($identificationType === IdentificationType::Cedula) => "C.C.",
                ($identificationType === IdentificationType::CedulaExtranjeria) => "C.E.",
                ($identificationType === IdentificationType::Pasaporte) => "Pasaporte"
            }
        );

    }

    public function formattedCategory(): Attribute {
        return Attribute::make(
            get: fn () => $this->categoryObject->category ?? "",
        );
    }

    public function formattedPickupPlace(): Attribute {
        return Attribute::make(
            get: fn () => $this->formattedBranch($this->pickupLocation)
        );
    }

    public function formattedPickupCity(): Attribute {
        return Attribute::make(
            get: fn () => $this->pickupLocation->city->name
        );
    }

    public function formattedReturnPlace(): Attribute {
        return Attribute::make(
            get: fn () => $this->formattedBranch($this->returnLocation)
        );
    }

    public function formattedReturnCity(): Attribute {
        return Attribute::make(
            get: fn () => $this->returnLocation->city->name
        );
    }

    public function formattedExtraHoursPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->extra_hours_price)
        );
    }

    public function formattedCoveragePrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->coverage_price)
        );
    }

    public function formattedTaxFee(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->tax_fee)
        );
    }

    public function formattedIVAFee(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->iva_fee)
        );
    }

    public function formattedReturnFee(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->return_fee ?? 0)
        );
    }

    public function formattedSubtotalPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->getSubtotalPrice())
        );
    }

    public function formattedTotalPrice(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->total_price)
        );
    }

    public function formattedTotalPriceLocaliza(): Attribute {
        return Attribute::make(
            get: fn () => $this->moneyFormat($this->total_price_localiza)
        );
    }

    public function formattedPickupDate(): Attribute {
        return Attribute::make(
            get: fn () => $this->dateFormat($this->pickup_date)
        );
    }

    public function formattedReturnDate(): Attribute {
        return Attribute::make(
            get: fn () => $this->dateFormat($this->return_date)
        );
    }

    public function formattedPickupHour(): Attribute {
        return Attribute::make(
            get: fn () => $this->hourFormat($this->pickup_hour)
        );
    }

    public function formattedReturnHour(): Attribute {
        return Attribute::make(
            get: fn () => $this->hourFormat($this->return_hour)
        );
    }

    private function formattedBranch(Branch|null $branch): string{
        return ($branch) ? "{$branch->name} - {$branch->code}" : "";
    }
}
