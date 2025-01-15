<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "categories";

    protected $guarded = [];
    protected $with = ['models'];


    /**
     * get models for a category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function models()
    {
        return $this->hasMany(CategoryModel::class, 'category_id');
    }

    /**
     * get month prices for a category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function monthPrices()
    {
        return $this->hasMany(CategoryMonthPrice::class, 'category_id');
    }

    /**
     * get allowed categories
     *
     * @return void
     */
    public function scopeAllowed(Builder $query): void {
        $query->whereIn('identification', [
            'C', 'F', 'FX', 'GC', 'G4', 'LE', 'GR', 'FU', 'FL', 'GL'
        ]);
    }
}
