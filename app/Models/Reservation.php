<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ReservationFormatTrait;

class Reservation extends Model
{
    use HasFactory, Searchable, ReservationFormatTrait;

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
