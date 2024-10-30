<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use UnexpectedValueException;
use Exception;

use App\Models\Reservation;
use App\Enums\ReservationAPIStatus;
use App\Enums\ReservationStatus;
use App\Http\Requests\StoreReservationAPIRequest;
use App\Jobs\SendClientReservationNotificationJob;
use App\Jobs\SendLocalizaReservationRequestJob;
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
            'fullname','email','phone','reference_token','rate_qualifier'
        ]);

        $reservation = new Reservation();
        $reservation->fill($reservationSavingData);

        if($reservation->selected_days < 30){

            $payload = array_merge($reservationLocalizaApiData, [
                'pickup_datetime' => $reservation->getPickupDateTime(),
                'return_datetime' => $reservation->getReturnDateTime(),
                'category'      => $request->original_category,
                'pickup_location'      => $request->original_pickup_location,
                'return_location'      => $request->original_return_location,
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
                    if($reservationStatus === ReservationAPIStatus::Confirmed){
                        $reservation->status = ReservationStatus::Reservado->value;
                        $reservationResult['reservationStatus'] = "Confirmado";
                    }
                    else if($reservationStatus === ReservationAPIStatus::Pending){
                        $reservation->status = ReservationStatus::Pendiente->value;
                        $reservationResult['reservationStatus'] = "Pendiente";

                        dispatch(new SendLocalizaReservationRequestJob($reservation)); // aditional notification to localiza to hurry them up
                    }

                    $reservation->reserve_code = $reservationResult['reserveCode'];

                    if($reservation->save())
                        dispatch(new SendClientReservationNotificationJob($reservation));

                }

                return $reservationResult;

            } catch (UnexpectedValueException $exception) {
                Log::error($exception->getMessage());
                abort(500, __('localiza.no_reservation_status'));
                \Sentry\captureException($exception);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                abort(500, __('localiza.error_saving_reservation'));
                \Sentry\captureException($exception);
            }

        }
        else if($reservation->selected_days == 30) { // a monthly reservation
            try {
                $reservation->status = ReservationStatus::Mensualidad->value;
                if($reservation->save()){
                    dispatch(new SendLocalizaReservationRequestJob($reservation));
                    return [
                        'reservationStatus' => ReservationStatus::Pendiente->value,
                        'reserveCode'       => ReservationStatus::Pendiente->value,
                    ];
                }
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                abort(500, __('localiza.error_saving_reservation'));
                \Sentry\captureException($exception);
            }
        }


    }

}
