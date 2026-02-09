<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Services\Ghl\GhlClient;
use App\Services\Ghl\GhlReminderMessageMapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job to send WhatsApp reminder notifications via GoHighLevel.
 *
 * Supports different reminder types:
 * - pickup: Week/3-day before pickup reminder
 * - same_day: Same-day pickup reminder with location details
 * - post_pickup: Post-pickup feedback message
 */
class SendGhlReminderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const TYPE_PICKUP = 'pickup';
    public const TYPE_SAME_DAY = 'same_day';
    public const TYPE_POST_PICKUP = 'post_pickup';

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Reservation $reservation,
        public string $reminderType = self::TYPE_PICKUP,
        public string $logPrefix = 'GHL Reminder'
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GhlClient $ghlClient): void
    {
        $reservation = $this->reservation;
        $phone = $reservation->phone;

        if (empty($phone)) {
            Log::warning("{$this->logPrefix}: Reservation {$reservation->id} has no phone number");
            return;
        }

        $franchise = $reservation->franchiseObject;
        if (!$franchise) {
            Log::warning("{$this->logPrefix}: Reservation {$reservation->id} has no franchise");
            return;
        }

        $franchiseKey = GhlClient::getFranchiseKey($franchise);
        if (!config("ghl.franchises.{$franchiseKey}")) {
            Log::info("{$this->logPrefix}: Franchise {$franchise->id} not configured for GHL");
            return;
        }

        try {
            $client = $ghlClient->forFranchise($franchiseKey);
            $cleanPhone = $this->cleanupPhone($phone);
            $message = $this->getMessage();

            $result = $client->sendWhatsAppToPhone($cleanPhone, $message);

            if ($result) {
                Log::info("{$this->logPrefix}: Message sent to {$cleanPhone} for reservation {$reservation->id}");
            } else {
                Log::error("{$this->logPrefix}: Failed to send message to {$cleanPhone} for reservation {$reservation->id}");
            }
        } catch (\Exception $e) {
            Log::error("{$this->logPrefix}: Exception sending message for reservation {$reservation->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get the message based on reminder type.
     */
    protected function getMessage(): string
    {
        return match ($this->reminderType) {
            self::TYPE_SAME_DAY => GhlReminderMessageMapper::getSameDayPickupReminderMessage($this->reservation),
            self::TYPE_POST_PICKUP => GhlReminderMessageMapper::getPostPickupMessage($this->reservation),
            default => GhlReminderMessageMapper::getPickupReminderMessage($this->reservation),
        };
    }

    /**
     * Cleanup phone number for GHL.
     */
    protected function cleanupPhone(string $phone): string
    {
        // Remove all non-numeric characters except leading +
        $cleaned = preg_replace('/[^\d+]/', '', $phone);

        // Ensure it starts with + for international format
        if (!str_starts_with($cleaned, '+')) {
            // Assume Spanish number if no country code
            if (strlen($cleaned) === 9) {
                $cleaned = '+34' . $cleaned;
            } else {
                $cleaned = '+' . $cleaned;
            }
        }

        return $cleaned;
    }
}
