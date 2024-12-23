<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DataProvider\ReservationsDataProviderCollection;
use App\Models\Franchise;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class ReservationsDataProviderController extends Controller
{
    public function __invoke(Request $request)
    {
        if($request->filled('franchise')){
            $franchise = Franchise::where('name',$request->input('franchise'))->firstOrFail();

            return Cache::rememberForever("reservations_data_provider_{$franchise->name}", fn() =>
                new ReservationsDataProviderCollection(
                    Category::allowed()->select()->selectRaw('? as franchise_id', [$franchise->id])->orderBy('order','asc')->get()->all(),
                    $franchise
                )
            );

        }

        abort(404);
    }
}
