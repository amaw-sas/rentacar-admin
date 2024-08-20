<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationEmailPreviewResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationEmailPreviewController extends Controller
{
    public function __invoke(Reservation $reservation)
    {
        return inertia('Reservations/EmailPreview', [
            'reservation' => new ReservationEmailPreviewResource($reservation),
            'localiza_image_url' => asset('storage/Localiza.png'),
            'user_image_url' => asset('storage/user.jpeg')
        ]);

    }
}
