<?php

namespace App\Console\Commands\Wati\SendReservationPickupNotification;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use App\Rentcar\Wati as WatiUtils;
use App\Facades\Wati;
use Exception;

class SendSameDayReservationPickupNotification extends SendReservationPickupNotification
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-same-day-reservation-pickup-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    protected function getBaseQuery(): Builder
    {
        $tomorrow = now()->addDay()->format('Y-m-d');
        $startHour = "02:01";
        $endHour = "14:00";

        $initDatetime = $tomorrow . ' ' . $startHour;
        $endDatetime = $tomorrow . ' ' . $endHour;

        return Reservation::whereRaw(
            "STR_TO_DATE(CONCAT(pickup_date, ' ', pickup_hour), '%Y-%m-%d %H:%i') BETWEEN ? AND ?",
            [$initDatetime, $endDatetime]
        );
    }

    protected function getLogPrefix(): string
    {
        return '';
    }

    protected function getBaseBroadcastName(): string
    {
        return '';
    }

    protected function getTemplateName(): string
    {
        return 'recordatorio_recogida_mismo_dia_1';
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->format('Y-m-d');
        $templateName = $this->getTemplateName();
        $broadcastName = $this->getBaseBroadcastName() . ' ' . $today;
        $baseLog = $this->getLogPrefix() . " Pickup Notification";

        $reservations = $this->getBaseQuery()
            ->whereNotNull('reserve_code')
            ->where('status', ReservationStatus::Reservado)

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
            $whatsappNumber = WatiUtils::cleanupPhone($reservation->phone);
            $userName = WatiUtils::cleanupName($reservation->fullname);

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
                    'name' => 'pickup_location_address',
                    'value' => $reservation->pickupLocation->pickup_address,
                ],
                [
                    'name' => 'pickup_location_map',
                    'value' => $reservation->pickupLocation->pickup_map,
                ],
                [
                    'name' => 'franchise_name',
                    'value' => $franchiseName,
                ],
            ]];


        });

        if($reservations->count() > 0){
            // Create contacts in wati
            $contacts->each(function ($user) use ($baseLog) {
                $whatsappNumber = $user['whatsappNumber'];
                $userName = $user['name'];
                $addContactSuccessLogInfo = "{$baseLog} Contact registered: {$userName} ({$whatsappNumber})";
                $addContactErrorLogInfo = "{$baseLog} Error registering contact: {$userName} ({$whatsappNumber})";

                try {
                    $response = Wati::addContact($whatsappNumber, $userName);
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

                $response = Wati::sendTemplateMessages($templateName, $broadcastName, $users);
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
