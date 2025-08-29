<?php

namespace App\Console\Commands\Wati\SendReservationPickupNotification;

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

        $today = now()->format('Y-m-d');
        $templateName = $this->getTemplateName();
        $broadcastName = $this->getBaseBroadcastName() . ' ' . now()->format('Y-m-d');
        $baseLog = $this->getLogPrefix() . " Pickup Notification";

        $reservations = $this->getBaseQuery()
            ->whereNotNull('reserve_code')
            ->where(function (Builder $query) {
                $query
                ->where('status', ReservationStatus::Reservado)
                ->orWhere('status', ReservationStatus::Mensualidad);
            })
            ->get();

        $contacts = $reservations->map(function ($reservation) {
            return [
                'whatsappNumber' => $reservation->phone,
                'name' => $reservation->fullname,
            ];
        });

        $receivers = $reservations->map(function ($reservation) {

            $franchiseName = $reservation->franchiseObject->name;
            $reservationCode = $reservation->reserve_code;
            $whatsappNumber = $reservation->phone;
            $userName = $reservation->fullname;

            return [
                'whatsappNumber' => $whatsappNumber,
                'customParams' => [
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
            ]];


        });

        if(count($reservations) > 0){
            // Create contacts in wati
            $contacts->each(function ($user) use ($watiApi, $baseLog) {
                $whatsappNumber = $user['whatsappNumber'];
                $userName = $user['name'];
                $addContactSuccessLogInfo = "{$baseLog} Contact registered: {$userName} ({$whatsappNumber})";
                $addContactErrorLogInfo = "{$baseLog} Error registering contact: {$userName} ({$whatsappNumber})";

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
                }
            });

            // Send notifications via wati
            $sendMessageTemplateSuccessLogInfo = "{$baseLog} sent {$today}";
            $sendMessageTemplateErrorLogInfo = "{$baseLog} Error sending notification {$today}";

            try {
                $users = $receivers->toArray();
                if(count($users) === 0){
                    throw new Exception("Failed to send notification in {$today}, no receivers provided. ");
                }

                $response = $watiApi->sendTemplateMessages($templateName, $broadcastName, $users);
                $result = $response['result'] ?? false;

                if ($response['result']) {
                    $this->info($sendMessageTemplateSuccessLogInfo);
                    Log::info($sendMessageTemplateSuccessLogInfo);
                } else {
                    throw new Exception("Failed to send notification in {$today} " . json_encode($response));
                }
            } catch (Exception $e) {
                Log::error($sendMessageTemplateErrorLogInfo . " - " . $e->getMessage());
                $this->error($sendMessageTemplateErrorLogInfo);
                return false;
            }

        }



    }
}
