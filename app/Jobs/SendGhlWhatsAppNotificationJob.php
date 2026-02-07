<?php

namespace App\Jobs;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Services\Ghl\GhlClient;
use App\Services\Ghl\GhlMessageMapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Send WhatsApp notification via GoHighLevel.
 *
 * This job is dispatched when the WHATSAPP_NOTIFICATION_PROVIDER
 * feature flag is set to 'ghl' or 'both'.
 */
class SendGhlWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    protected Reservation $reservation;

    /**
     * Create a new job instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     */
    public function handle(GhlClient $ghlClient): void
    {
        $franchise = $this->reservation->franchiseObject;

        if (!$franchise) {
            Log::warning('SendGhlWhatsAppNotificationJob: No franchise associated', [
                'reservation_id' => $this->reservation->id,
            ]);
            return;
        }

        $franchiseKey = GhlClient::getFranchiseKey($franchise);

        // Check if GHL is configured for this franchise
        if (!config("ghl.franchises.{$franchiseKey}.api_key")) {
            Log::info("SendGhlWhatsAppNotificationJob: GHL not configured for franchise {$franchiseKey}");
            return;
        }

        // Get phone number
        $phone = $this->reservation->phone;

        if (!$phone) {
            Log::warning('SendGhlWhatsAppNotificationJob: No phone number', [
                'reservation_id' => $this->reservation->id,
            ]);
            return;
        }

        try {
            $client = $ghlClient->forFranchise($franchiseKey);

            // Clean up phone number
            $phone = GhlMessageMapper::cleanupPhone($phone);

            // Get main message based on reservation status
            $message = GhlMessageMapper::getMessage($this->reservation);

            if (!$message) {
                Log::info('SendGhlWhatsAppNotificationJob: No message for status', [
                    'reservation_id' => $this->reservation->id,
                    'status' => $this->getStatusValue(),
                ]);
                return;
            }

            // Send main message
            $result = $client->sendWhatsAppToPhone($phone, $message);

            if (!$result) {
                Log::error('SendGhlWhatsAppNotificationJob: Failed to send main message', [
                    'reservation_id' => $this->reservation->id,
                    'phone' => $phone,
                    'franchise' => $franchiseKey,
                ]);
                return;
            }

            Log::info('SendGhlWhatsAppNotificationJob: Main message sent', [
                'reservation_id' => $this->reservation->id,
                'status' => $this->getStatusValue(),
                'franchise' => $franchiseKey,
            ]);

            // Send additional messages (for Reservado status)
            $additionalMessages = GhlMessageMapper::getAdditionalMessages($this->reservation);

            foreach ($additionalMessages as $index => $additionalMessage) {
                // Small delay between messages to maintain order
                sleep(2);

                $additionalResult = $client->sendWhatsAppToPhone($phone, $additionalMessage);

                if ($additionalResult) {
                    Log::info('SendGhlWhatsAppNotificationJob: Additional message sent', [
                        'reservation_id' => $this->reservation->id,
                        'message_index' => $index + 1,
                        'franchise' => $franchiseKey,
                    ]);
                } else {
                    Log::warning('SendGhlWhatsAppNotificationJob: Failed to send additional message', [
                        'reservation_id' => $this->reservation->id,
                        'message_index' => $index + 1,
                        'franchise' => $franchiseKey,
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('SendGhlWhatsAppNotificationJob: Exception', [
                'reservation_id' => $this->reservation->id,
                'franchise' => $franchiseKey,
                'error' => $e->getMessage(),
            ]);

            if (app()->bound('sentry')) {
                \Sentry\captureException($e);
            }

            throw $e;
        }
    }

    /**
     * Get the status value safely (handles both string and enum).
     */
    private function getStatusValue(): string
    {
        $status = $this->reservation->status;

        return $status instanceof ReservationStatus
            ? $status->value
            : (string) $status;
    }
}
