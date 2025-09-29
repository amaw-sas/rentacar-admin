<?php

namespace App\Listeners\SendClientReservationNotification;

use Illuminate\Support\Facades\Log;
use App\Events\SendReservationNotificationEvent;
use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Facades\Wati;
use \Exception;

class SendClientReservationWhatsappNotificationListener extends SendClientReservationNotificationListener
{

    public $templateMessages;
    public $sendingMethods;
    public $today;

    public function __construct()
    {
        $this->today = now()->format('Y-m-d');

        $this->templateMessages = [
            ReservationStatus::Reservado->value => 'nueva_reserva_4',
            ReservationStatus::Pendiente->value => 'reserva_pendiente',
            ReservationStatus::SinDisponibilidad->value => 'reserva_sin_disponibilidad',
        ];

        $this->sendingMethods = [
            ReservationStatus::Reservado->value => 'sendReservedReservationNotification',
            ReservationStatus::Pendiente->value => 'sendPendingReservationNotification',
            ReservationStatus::SinDisponibilidad->value => 'sendFailedReservationNotification',
        ];

    }

    protected function sendPendingReservationNotification(Reservation $reservation): void
    {
        $franchiseName = $reservation->franchiseObject->name;
        $reservationCode = $reservation->reserve_code;
        $whatsappNumber = $reservation->phone;
        $userName = $reservation->fullname;

        $params = [
            [
                'name' => 'fullname',
                'value' => $userName,
            ],
            [
                'name' => 'franchise_name',
                'value' => $franchiseName,
            ],
        ];

        $baseLog = "Pending Reservation Notification";
        $successLog = "{$baseLog} Code: {$reservationCode} sent {$this->today}";
        $errorLog = "{$baseLog} Error sending notification {$this->today}";

        $templateName = $this->templateMessages[ReservationStatus::Pendiente->value];
        $broadcastName =  "RP {$reservationCode}";

        $this->sendReservationNotification(
            $whatsappNumber,
            $templateName,
            $broadcastName,
            $params,
            $successLog,
            $errorLog
        );
    }

    protected function sendReservedReservationNotification(Reservation $reservation): void
    {
        $franchiseName = $reservation->franchiseObject->name;
        $reservationCode = $reservation->reserve_code;
        $whatsappNumber = $reservation->phone;
        $userName = $reservation->fullname;

        $baseLog = "New Reservation Notification";
        $successLog = "{$baseLog} Code: {$reservationCode} sent {$this->today}";
        $errorLog = "{$baseLog} Error sending notification {$this->today}";

        $templateName = $this->templateMessages[ReservationStatus::Reservado->value];
        $broadcastName = "NR {$reservationCode}";

        $params = [
            [
                'name' => 'fullname',
                'value' => $userName,
            ],
            [
                'name' => 'reservation_code',
                'value' => $reservationCode,
            ],
            [
                'name' => 'pickup_date',
                'value' => $reservation->pickup_date->locale('es')->isoFormat('LL'),
            ],
            [
                'name' => 'pickup_hour',
                'value' => $reservation->pickup_hour->format('H:i a'),
            ],
            [
                'name' => 'pickup_location',
                'value' => $reservation->pickupLocation->name,
            ],
            [
                'name' => 'return_date',
                'value' => $reservation->return_date->locale('es')->isoFormat('LL'),
            ],
            [
                'name' => 'return_hour',
                'value' => $reservation->return_hour->format('H:i a'),
            ],
            [
                'name' => 'return_location',
                'value' => $reservation->returnLocation->name,
            ],
            [
                'name' => 'franchise_name',
                'value' => $franchiseName,
            ],
        ];

        $this->sendReservationNotification(
            $whatsappNumber,
            $templateName,
            $broadcastName,
            $params,
            $successLog,
            $errorLog,
        );

        $this->sendReservedReservationNotificationInstructions($reservation);
        $this->sendReservedReservationNotificationInstructionsAdditional($reservation);
    }

    protected function sendReservedReservationNotificationInstructions(Reservation $reservation): void
    {
        $reservationCode = $reservation->reserve_code;
        $whatsappNumber = $reservation->phone;
        $userName = $reservation->fullname;
        $params = [];

        $templateName = 'nueva_reserva_instrucciones_2';
        $broadcastName = "NRI {$reservationCode}";

        $baseLog = "Reservation Instructions Notification";
        $successLog = "{$baseLog} Code: {$reservationCode} sent {$this->today}";
        $errorLog = "{$baseLog} Error sending notification {$this->today}";

        $this->sendReservationNotification(
            $whatsappNumber,
            $templateName,
            $broadcastName,
            $params,
            $successLog,
            $errorLog,
        );
    }

    protected function sendReservedReservationNotificationInstructionsAdditional(Reservation $reservation): void
    {
        $reservationCode = $reservation->reserve_code;
        $whatsappNumber = $reservation->phone;
        $userName = $reservation->fullname;
        $params = [];

        $templateName = 'nueva_reserva_instrucciones_adicionales';
        $broadcastName = "NRIA {$reservationCode}";

        $baseLog = "Reservation More Instructions Notification";
        $successLog = "{$baseLog} Code: {$reservationCode} sent {$this->today}";
        $errorLog = "{$baseLog} Error sending notification {$this->today}";

        $this->sendReservationNotification(
            $whatsappNumber,
            $templateName,
            $broadcastName,
            $params,
            $successLog,
            $errorLog,
        );
    }

    protected function sendFailedReservationNotification(Reservation $reservation): void
    {
        $franchiseReservationWebsite = $reservation->franchiseObject->reserva_button;
        $reservationCode = $reservation->reserve_code;
        $whatsappNumber = $reservation->phone;
        $userName = $reservation->fullname;
        $params = [
            [
                'name' => 'fullname',
                'value' => $userName,
            ],
            [
                'name' => 'franchise_reservation_website',
                'value' => $franchiseReservationWebsite,
            ]
        ];

        $baseLog = "Failed Notification";
        $successLog = "{$baseLog} Code: {$reservationCode} sent {$this->today}";
        $errorLog = "{$baseLog} Error sending notification {$this->today}";

        $templateName = $this->templateMessages[ReservationStatus::SinDisponibilidad->value];
        $broadcastName = "FR {$reservationCode}";

        $this->sendReservationNotification(
            $whatsappNumber,
            $templateName,
            $broadcastName,
            $params,
            $successLog,
            $errorLog
        );
    }


    protected function sendReservationNotification(
        string $whatsappNumber,
        string $templateName,
        string $broadcastName,
        array $params,
        string $successLog = '',
        string $errorLog = '',
    ): void
    {
        try {
            $response = Wati::sendTemplateMessage($whatsappNumber, $templateName, $broadcastName, $params);
            $result = $response['result'] ?? false;

            if ($response['result']) {
                Log::info($successLog);
            } else {
                throw new Exception($errorLog . json_encode($response));
            }
        } catch (Exception $e) {
            Log::error($errorLog . " - " . $e->getMessage());
        }
    }



    /**
     * Handle the event.
     */
    public function handle(SendReservationNotificationEvent $event): void
    {
        $reservation = $event->reservation;
        $status = $reservation->status;

        // Obtener el nombre del método desde el arreglo usando el valor del enum
        $methodName = $this->sendingMethods[$status] ?? null;

        // Verificar si el método existe y es callable
        if ($methodName && method_exists($this, $methodName)) {
            // Ejecutar el método dinámicamente
            $this->$methodName($reservation);
        } else {
            // Manejar el caso en que no se encuentre el método
            throw new Exception("Método de notificación no encontrado para el estado: {$status}");
        }

    }
}
