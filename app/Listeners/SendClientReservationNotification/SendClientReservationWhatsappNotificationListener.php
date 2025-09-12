<?php

namespace App\Listeners\SendClientReservationNotification;

use Illuminate\Support\Facades\Log;
use App\Events\NewReservationEvent;

class SendClientReservationWhatsappNotificationListener extends SendClientReservationNotificationListener
{

    protected function getLogPrefix(): string
    {
        return 'New Reservation';
    }

    protected function getTemplateName(): string
    {
        return 'nueva_reserva';
    }

    protected function getBaseBroadcastName(): string
    {
        return 'NR';
    }

    /**
     * Handle the event.
     */
    public function handle(NewReservationEvent $event): void
    {
        $watiApi = app('wati');

        $templateName = $this->getTemplateName();
        $broadcastName = $this->getBaseBroadcastName() . ' ' . now()->format('Y-m-d');
        $baseLog = $this->getLogPrefix() . " Notification";
        $reservation = $event->reservation;
        $today = now()->format('Y-m-d');

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

        $addContactSuccessLogInfo = "{$baseLog} Contact registered: {$userName} ({$whatsappNumber})";
        $addContactErrorLogInfo = "{$baseLog} Error registering contact: {$userName} ({$whatsappNumber})";

        try {
            $response = $watiApi->addContact($whatsappNumber, $userName);
            $result = $response['result'] ?? false;
            if ($result) {
                Log::info($addContactSuccessLogInfo);
            } else {
                throw new \Exception("Failed to register contact: " . json_encode($response));
            }
        } catch (Exception $e) {
            Log::error($addContactErrorLogInfo . " - " . $e->getMessage());
        }

        // Send first part new reservations notifications via wati
        $sendMessageTemplateSuccessLogInfo = "{$baseLog} Code: {$reservationCode} sent {$today}";
        $sendMessageTemplateErrorLogInfo = "{$baseLog} Error sending notification {$today}";

        try {

            $response = $watiApi->sendTemplateMessage($whatsappNumber, $templateName, $broadcastName, $params);
            $result = $response['result'] ?? false;

            if ($response['result']) {
                Log::info($sendMessageTemplateSuccessLogInfo);
            } else {
                throw new Exception("Failed to send notification in {$today} " . json_encode($response));
            }
        } catch (Exception $e) {
            Log::error($sendMessageTemplateErrorLogInfo . " - " . $e->getMessage());
        }

        // Send second part new reservations notifications (instructions) via wati
        try {
            $sendMessageTemplateSuccessLogInfo = "{$baseLog} Instructions Code: {$reservationCode} sent {$today}";
            $sendMessageTemplateErrorLogInfo = "{$baseLog} Error sending new reservation instructions notification {$today}";

            $templateName = 'nueva_reserva_instrucciones';
            $broadcastName = "NRI {$reservationCode} {$today}";
            $params = [];

            $response = $watiApi->sendTemplateMessage($whatsappNumber, $templateName, $broadcastName, $params);
            $result = $response['result'] ?? false;

            if ($response['result']) {
                Log::info($sendMessageTemplateSuccessLogInfo);
            } else {
                throw new Exception("Failed to send new reservation notification {$reservationCode} in {$today} " . json_encode($response));
            }
        } catch (Exception $e) {
            Log::error($sendMessageTemplateErrorLogInfo . " - " . $e->getMessage());
        }
    }
}
