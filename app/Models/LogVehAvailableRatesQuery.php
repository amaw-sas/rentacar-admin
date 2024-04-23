<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;

class LogVehAvailableRatesQuery extends Model
{
    use HasFactory, Prunable;

    public $guarded = [];

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        /** remove log  */
        return static::where('created_at', '<=', now()->subMonths(3));
    }

}
