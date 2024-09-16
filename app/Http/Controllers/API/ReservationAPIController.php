<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreReservationAPIRequest;
use App\Mail\AlquicarrosReservationRequest;
use App\Mail\AlquilameReservationRequest;
use App\Mail\AlquilatucarroReservationRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class ReservationAPIController extends Controller
{

    public $franchisesEmails = [
        'alquilatucarro' => AlquilatucarroReservationRequest::class,
        'alquilame' => AlquilameReservationRequest::class,
        'alquicarros' => AlquicarrosReservationRequest::class,
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReservationAPIRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreReservationAPIRequest $request)
    {
        $reservationData = $request->safe()->except(['rate_qualifier','reference_token']);

        try {
            $reservation = Reservation::create($reservationData);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            abort(500, __('localiza.error_saving_reservation'));
            \Sentry\captureException($th);
        }

        if($reservation){
            $franchise = $reservation->franchiseObject->name;

            if(!is_null($franchise)){
                try {
                    // send to localiza a notification
                    $franchiseEmail = $this->franchisesEmails[$franchise];

                    Mail::mailer($franchise)
                    ->send(new $franchiseEmail($reservation));
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                    abort(500, __('localiza.error_sending_reservation_request_to_localiza'));
                    \Sentry\captureException($th);
                }
            }

            // try {
            //     // send to client a notification
            //     Mail::to($reservation->email)
            //     ->queue(new ReservationClientNotification($reservation));
            // } catch (\Throwable $th) {
            //     //TODO send error notification to sentry
            // }

            return response('ok',201);
        }
        else
            abort(500, __('localiza.error_saving_reservation'));
    }

}
