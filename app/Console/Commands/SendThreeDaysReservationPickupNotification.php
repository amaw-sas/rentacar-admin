<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Exception;

class SendThreeDaysReservationPickupNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-three-days-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a three days before reservation pickup notification to the user via WhatsApp using Wati API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $watiApi = app('wati');
        $threedayslater = now()->addDays(3)->format('Y-m-d');

        // Send notifications for reservations with pickup date three days later
        Reservation::where('pickup_date', $threedayslater)
            ->where('status', ReservationStatus::Reservado)
            ->get()
            ->each(function ($reservation) use ($watiApi) {
                $franchise = $reservation->franchiseObject->name;
                $reservationCode = $reservation->reserve_code;
                $whatsappNumber = $reservation->phone;
                $userName = $reservation->fullname;
                $templateName = 'aviso_recogida_vehiculo_tres_dias';
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
                $threeDaysBaseLog="Three Days Pickup Notification:";

                $addContactSuccessLogInfo="{$threeDaysBaseLog} Contact registered: {$userName} ({$whatsappNumber})";
                $sendMessageTemplateSuccessLogInfo="{$threeDaysBaseLog} Notification sent: {$userName} ({$whatsappNumber})";
                $addContactErrorLogInfo="{$threeDaysBaseLog} Error registering contact: {$userName} ({$whatsappNumber})";
                $sendMessageTemplateErrorLogInfo="{$threeDaysBaseLog} Error sending notification: {$userName} ({$whatsappNumber})";

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
                    Log::error($addContactErrorLogInfo . " - " . $e->getMessage());
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
