<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Exception;

class SendSameDayMorningReservationPickupNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-same-day-morning-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a same day morning (14:00 same day to 02:00 tomorrow reservations) reservation pickup notification to the user via WhatsApp using Wati API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $watiApi = app('wati');
        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        $startHour = "14:00";
        $endHour = "02:00";

        $initDatetime = $today . ' ' . $startHour;
        $endDatetime = $tomorrow . ' ' . $endHour;

        Reservation::whereRaw(
            "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i') BETWEEN ? AND ?",
            [$initDatetime, $endDatetime]
        )
        ->where('status', ReservationStatus::Reservado)
        ->get()
        ->each(function ($reservation) use ($watiApi) {
                $franchise = $reservation->franchiseObject->name;
                $reservationCode = $reservation->reserve_code;
                $whatsappNumber = $reservation->phone;
                $userName = $reservation->fullname;
                $templateName = 'aviso_recogida_vehiculo_mismo_dia';
                $broadcastName = 'Notificación de Recogida de Vehículo';
                $parameters = [
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
                        'name' => 'franchise',
                        'value' => $franchise,
                    ]

                ];
                $sameDayMorningBaseLog = "Same Day Morning Pickup Notification:";

                $addContactSuccessLogInfo="{$sameDayMorningBaseLog} Contact registered: {$userName} ({$whatsappNumber})";
                $sendMessageTemplateSuccessLogInfo="{$sameDayMorningBaseLog} Notification sent: {$userName} ({$whatsappNumber})";
                $addContactErrorLogInfo="{$sameDayMorningBaseLog} Error registering contact: {$userName} ({$whatsappNumber})";
                $sendMessageTemplateErrorLogInfo="{$sameDayMorningBaseLog} Error sending notification: {$userName} ({$whatsappNumber})";

                // register the contact in wati
                try {
                    $response = $watiApi->addContact($whatsappNumber, $userName);
                    $result = $response['result'] ?? false;

                    if ($result) {
                        $this->info($addContactSuccessLogInfo);
                        Log::info($addContactSuccessLogInfo);
                    } else {
                        throw new \Exception("Failed to register contact: " . json_encode($response));
                    }
                }
                catch (Exception $e) {
                    Log::error($addContactErrorLogInfo . " - " . $e->getMessage() . " - " . $e->getTraceAsString());
                    $this->error($addContactErrorLogInfo);
                    return;
                }

                // send the notification
                try {
                    $response = $watiApi->sendTemplateMessage($whatsappNumber, $templateName, $broadcastName, $parameters);
                    $result = $response['result'] ?? false;

                    if ($result) {
                        $this->info($sendMessageTemplateSuccessLogInfo);
                        Log::info($sendMessageTemplateSuccessLogInfo);
                    } else {
                        throw new Exception("Failed to send notification to {$userName} ({$whatsappNumber}): " . json_encode($response));
                    }
                } catch (Exception $e) {
                    Log::error($sendMessageTemplateErrorLogInfo . " - " . $e->getMessage() . " - " . $e->getTraceAsString());
                    $this->error($sendMessageTemplateErrorLogInfo);
                    return;
                }
            });

        }
}
