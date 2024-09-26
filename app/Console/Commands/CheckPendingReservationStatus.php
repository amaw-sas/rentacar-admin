<?php

namespace App\Console\Commands;

use App\Enums\ReservationAPIStatus;
use App\Enums\ReservationStatus;
use App\Jobs\SendClientReservationNotificationJob;
use App\Models\Reservation;
use App\Rentcar\Localiza\VehRetRes\LocalizaAPIVehRetRes;
use Illuminate\Console\Command;

class CheckPendingReservationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:check-status';

    /**
     * Positive reservation api statuses
     *
     * @var array[ReservationAPIStatus]
     */
    protected $positiveReservationApiStatuses = [
        ReservationAPIStatus::Confirmed,
    ];

    /**
     * Negative reservation api statuses
     *
     * @var array[ReservationAPIStatus]
     */
    protected $negativeReservationApiStatuses = [
        ReservationAPIStatus::Cancelled,
        ReservationAPIStatus::NoShow,
        ReservationAPIStatus::Failed,
        ReservationAPIStatus::Expired,
    ];

    /**
     * Positive reservation api statuses
     *
     * @var array[ReservationAPIStatus]
     */
    protected $undeterminateReservationApiStatuses = [
        ReservationAPIStatus::OnRequest,
        ReservationAPIStatus::Waitlist,
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check pending reservations status querying Localiza API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingReservations = Reservation::where('status', ReservationStatus::Pendiente->value)->get();

        foreach($pendingReservations as $reservation){
            $localizaRequest = new LocalizaAPIVehRetRes([
                'reserve_code'  =>  $reservation->reserve_code,
            ]);

            $localizaResponse = $localizaRequest->getData();


            $reservationStatus = ReservationAPIStatus::tryFrom($localizaResponse['reservationStatus']) ?? null;

            // dd($localizaResponse, $reservationStatus);

            if($reservationStatus){

                if(in_array($reservationStatus, $this->positiveReservationApiStatuses)){
                    $reservation->status = ReservationStatus::Reservado->value;
                    $reservation->save();

                    dispatch(new SendClientReservationNotificationJob($reservation));
                }
                else if(in_array($reservationStatus, $this->negativeReservationApiStatuses)){
                    $reservation->status = ReservationStatus::SinDisponibilidad->value;
                    $reservation->save();

                    dispatch(new SendClientReservationNotificationJob($reservation));
                }
                else if(in_array($reservationStatus, $this->undeterminateReservationApiStatuses)){
                    $reservation->status = ReservationStatus::Indeterminado;
                    $reservation->save();
                }
            }
        }
    }
}
