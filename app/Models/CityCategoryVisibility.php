<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityCategoryVisibility extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'city_id',
        'category_id',
        'visible'
    ];
    protected $with = ['category'];

    /**
     * get city for this city category visibility
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function city(){
        return $this->belongsTo(City::class);
    }

    /**
     * get category for this city category visibility
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
