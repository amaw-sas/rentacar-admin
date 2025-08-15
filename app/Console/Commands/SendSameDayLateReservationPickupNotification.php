<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Exception;

class SendSameDayLateReservationPickupNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-same-day-late-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a same day late (02:00 to 14:00 tomorrow reservations) reservation pickup notification to the user via WhatsApp using Wati API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $watiApi = app('wati');
        $tomorrow = now()->addDay()->format('Y-m-d');
        $startHour = "02:00";
        $endHour = "14:00";

        $initDatetime = $tomorrow . ' ' . $startHour;
        $endDatetime = $tomorrow . ' ' . $endHour;

        Reservation::whereRaw(
            "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i') BETWEEN ? AND ?",
            [$initDatetime, $endDatetime]
        )
        ->where('status', ReservationStatus::Reservado)
        ->orWhere('status', ReservationStatus::Mensualidad)
        ->get()
        ->each(function ($reservation) use ($watiApi) {
                $franchiseName = $reservation->franchiseObject->name;
                $franchiseWeb = config('rentacar.' . strtolower($franchiseName) . '.website' );
                $franchisePhone = config('rentacar.' . strtolower($franchiseName) . '.phone' );
                $reservationCode = $reservation->reserve_code;
                $whatsappNumber = $reservation->phone;
                $userName = $reservation->fullname;
                $templateName = 'recordatorio_alquiler_carro';
                $broadcastName = 'Recordatorio de Recogida de Vehículo';
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
                        'name' => 'pickup_location',
                        'value' => $reservation->pickupLocation->name,
                    ],
                    [
                        'name' => 'franchise_name',
                        'value' => $franchiseName,
                    ],
                    [
                        'name' => 'franchise_web',
                        'value' => $franchiseWeb,
                    ],
                    [
                        'name' => 'franchise_phone',
                        'value' => $franchisePhone,
                    ],

                ];
                $sameDayMorningBaseLog = "Same Day Late Pickup Notification:";

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
                    Log::error($sendMessageTemplateErrorLogInfo . " - " . $e->getMessage());
                    $this->error($sendMessageTemplateErrorLogInfo);
                    return;
                }
            });



        }
}
