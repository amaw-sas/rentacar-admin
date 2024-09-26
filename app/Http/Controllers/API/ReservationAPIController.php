<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use \Sentry\captureException;
use UnexpectedValueException;
use Exception;

use App\Models\Reservation;
use App\Enums\ReservationAPIStatus;
use App\Enums\ReservationStatus;
use App\Http\Requests\StoreReservationAPIRequest;
use App\Jobs\SendClientReservationNotificationJob;
use App\Rentcar\Localiza\VehRes\LocalizaAPIVehRes;

class ReservationAPIController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReservationAPIRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreReservationAPIRequest $request)
    {
        $reservationSavingData = $request->safe()->except(['rate_qualifier','reference_token']);
        $reservationLocalizaApiData = $request->safe()->only([
            'fullname','email','phone','reference_token',
            'category','rate_qualifier','pickup_location','return_location'
        ]);

        $reservation = new Reservation();
        $reservation->fill($reservationSavingData);

        $payload = array_merge($reservationLocalizaApiData, [
            'pickup_datetime' => $reservation->getPickupDateTime(),
            'return_datetime' => $reservation->getReturnDateTime(),
        ]);

        $localizaApi = new LocalizaAPIVehRes(
           $payload
        );

        $reservationResult = $localizaApi->getData();

        try {
            $reservationStatus = ReservationAPIStatus::tryFrom($reservationResult['reservationStatus']);

            if(
                $reservationStatus === ReservationAPIStatus::Confirmed ||
                $reservationStatus === ReservationAPIStatus::Pending
            ){
                if($reservationStatus === ReservationAPIStatus::Confirmed)
                    $reservation->status = ReservationStatus::Reservado->value;
                else if($reservationStatus === ReservationAPIStatus::Pending)
                    $reservation->status = ReservationStatus::Pendiente->value;

                if($reservation->save())
                    dispatch(new SendClientReservationNotificationJob($reservation));

            }

            return $reservationResult;

        } catch (UnexpectedValueException $exception) {
            Log::error($exception->getMessage());
            abort(500, __('localiza.no_reservation_status'));
            captureException($exception);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            abort(500, __('localiza.error_saving_reservation'));
            captureException($exception);
        }

    }

}
