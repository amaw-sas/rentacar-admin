<?php

namespace App\Console\Commands\SendSameDayLateReservation;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Exception;

abstract class SendReservationPickupNotification extends Command
{
    /**
     * Get the base query for reservations.
     *
     * @return Builder
     */
    abstract protected function getBaseQuery(): Builder;

    /**
     * Get the log prefix (e.g., 'Week', 'Three Days').
     *
     * @return string
     */
    abstract protected function getLogPrefix(): string;

    /**
     * Get the template name for Wati.
     *
     * @return string
     */
    abstract protected function getTemplateName(): string;

    /**
     * Get the base broadcast name for Wati.
     *
     * @return string
     */
    abstract protected function getBaseBroadcastName(): string;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $watiApi = app('wati');

        $reservations = $this->getBaseQuery()
            ->whereNotNull('reserve_code')
            ->where(function (Builder $query) {
                $query
                ->where('status', ReservationStatus::Reservado)
                ->orWhere('status', ReservationStatus::Mensualidad);
            })
            ->get();

        $reservations->each(function ($reservation) use ($watiApi) {
            $franchiseName = $reservation->franchiseObject->name;
            $reservationCode = $reservation->reserve_code;
            $whatsappNumber = $reservation->phone;
            $userName = $reservation->fullname;
            $templateName = $this->getTemplateName();
            $broadcastName = "{$this->getBaseBroadcastName()} - Código: {$reservationCode}";
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

            $baseLog = $this->getLogPrefix() . " Pickup Notification: Reserve Code: {$reservationCode}";

            $addContactSuccessLogInfo = "{$baseLog} Contact registered: {$userName} ({$whatsappNumber})";
            $sendMessageTemplateSuccessLogInfo = "{$baseLog} Notification sent: {$userName} ({$whatsappNumber})";
            $addContactErrorLogInfo = "{$baseLog} Error registering contact: {$userName} ({$whatsappNumber})";
            $sendMessageTemplateErrorLogInfo = "{$baseLog} Error sending notification: {$userName} ({$whatsappNumber})";

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
            } catch (Exception $e) {
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
