<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Exception;

class SendWeekReservationPickupNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wati:send-week-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a week before reservation pickup notification to the user via WhatsApp using Wati API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $watiApi = app('wati');
        $weeklater = now()->addWeek()->format('Y-m-d');

        // Send notifications for reservations with pickup date a week later
        Reservation::whereDate('pickup_date', $weeklater)
            ->whereNotNull('reserve_code')
            ->where(function (Builder $query) {
                $query
                ->where('status', ReservationStatus::Reservado)
                ->orWhere('status', ReservationStatus::Mensualidad);
            })
            ->get()
            ->each(function ($reservation) use ($watiApi) {
                $franchiseName = $reservation->franchiseObject->name;
                $franchiseWeb = config('rentacar.' . strtolower($franchiseName) . '.website' );
                $franchisePhone = config('rentacar.' . strtolower($franchiseName) . '.phone' );
                $reservationCode = $reservation->reserve_code;
                $whatsappNumber = $reservation->phone;
                $userName = $reservation->fullname;
                $templateName = 'recordatorio_recogida';
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

                ];
                $weekBaseLog="Week Pickup Notification: Reserve Code: {$reservationCode}";

                $addContactSuccessLogInfo="{$weekBaseLog} Contact registered: {$userName} ({$whatsappNumber})";
                $sendMessageTemplateSuccessLogInfo="{$weekBaseLog} Notification sent: {$userName} ({$whatsappNumber})";
                $addContactErrorLogInfo="{$weekBaseLog} Error registering contact: {$userName} ({$whatsappNumber})";
                $sendMessageTemplateErrorLogInfo="{$weekBaseLog} Error sending notification: {$userName} ({$whatsappNumber})";

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

                    if ($response['result']) {
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
