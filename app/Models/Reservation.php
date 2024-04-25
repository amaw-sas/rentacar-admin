<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * get the pickup location branch of this instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pickupLocation(){
        return $this->hasOne(Branch::class, 'id','pickup_location');
    }

    /**
     * get the pickup location branch of this instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function returnLocation(){
        return $this->hasOne(Branch::class, 'id','return_location');
    }

    /**
     * get the franchise of this instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function franchiseObject(){
        return $this->hasOne(Franchise::class, 'id','franchise');
    }

    /**
     * get the category of this instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function categoryObject(){
        return $this->hasOne(Category::class, 'id', 'category');
    }

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

    private function moneyFormat($number){
        $fmt = numfmt_create( 'es_CO', \NumberFormatter::CURRENCY );
        return numfmt_format_currency($fmt, $number, "COP");
    }

    private function dateFormat($date, $format = "Y-m-d"){
        try {
            return Carbon::createFromFormat($format, $date)->locale('es')->isoFormat("LL");
        } catch (\Throwable $th) {
            return Carbon::createFromFormat($format, $date)->format('Y-m-d');
        }
    }

    private function hourFormat($hour, $format = "H:i"){
        try {
            return Carbon::createFromFormat($format, $hour)->format("H:i a");
        } catch (\Throwable $th) {
            return Carbon::createFromFormat("H:i:s", $hour)->format("H:i a");
        }
    }

    private function formattedBranch(Branch $branch){
        return "{$branch->name} - {$branch->code}";
    }

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'selected_days' => 0,
        'extra_hours' => 0,
        'extra_hours_price' => 0,
        'coverage_days' => 0,
        'coverage_price' => 0,
        'tax_fee' => 0,
        'iva_fee' => 0,
        'total_price' => 0,
        'total_price_localiza' => 0,
    ];

}
