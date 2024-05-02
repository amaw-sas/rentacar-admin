<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationCollection;
use App\Http\Resources\ReservationCreateResource;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = new Reservation;

        if($request->filled('s'))
            $query = $query->search($request->input('s'));


        if($request->filled(['orderBy','orderByOrientation']))
            $query = $query->orderBy(
                $request->input('orderBy'),
                $request->input('orderByOrientation')
            );

        return inertia('Reservations/Index', [
            'paginator' => new ReservationCollection($query->paginate()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Reservations/Create', [
            'item'  =>  (new ReservationCreateResource(new Reservation))->resolve()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        try {
            DB::beginTransaction();

            $reservation = Reservation::create($request->safe()->toArray());

            DB::commit();

            request()->session()->flash('flash.banner', __('generic.successful_saved'));
            request()->session()->flash('flash.bannerStyle', 'success');

        } catch (\Throwable $th) {
            Log::error("Error en guardar registro", [$th]);
            DB::rollBack();
            request()->session()->flash('flash.banner',__('generic.error_generic'));
            request()->session()->flash('flash.bannerStyle', 'error');
            back();
        }

        return redirect()->route('reservations.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        return inertia('Reservations/Edit', [
            'item'  =>  (new ReservationCreateResource($reservation))->resolve()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        try {
            DB::beginTransaction();
            $reservation->update($request->safe()->toArray());
            DB::commit();
            request()->session()->flash('flash.banner',__('generic.successful_saved'));
            request()->session()->flash('flash.bannerStyle', 'success');
        } catch (\Throwable $th) {
            Log::error('Error al actualizar registro', [$th]);
            DB::rollBack();
            request()->session()->flash('flash.banner',__('generic.error_generic'));
            request()->session()->flash('flash.bannerStyle', 'error');
            back();
        }

        return redirect()->route('reservations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        try {
            DB::beginTransaction();

            $reservation->forceDelete();

            DB::commit();

            request()->session()->flash('flash.banner', __('generic.successful_deleted'));
            request()->session()->flash('flash.bannerStyle', 'success');

        } catch (\Throwable $th) {
            Log::error('Error al borrar registro', [$th]);
            DB::rollBack();
            request()->session()->flash('flash.banner', __('generic.error_generic'));
            request()->session()->flash('flash.bannerStyle', 'error');
            back();
        }

        return redirect()->route('reservations.index');
    }
}
