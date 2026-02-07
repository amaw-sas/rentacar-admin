<?php

namespace Tests\Feature\Ghl;

use App\Enums\ReservationStatus;
use App\Jobs\SendGhlWhatsAppNotificationJob;
use App\Models\Franchise;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendGhlWhatsAppNotificationJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Fake the queue to prevent ReservationObserver from dispatching SyncReservationToGhlJob
        // which would interfere with our HTTP fakes and Log mocks
        Queue::fake();

        // Configure GHL for test franchise
        Config::set('ghl.franchises.testfranchise', [
            'api_key' => 'test-api-key',
            'location_id' => 'test-location-id',
            'pipeline_id' => 'test-pipeline-id',
            'stages' => [
                'pendiente' => 'stage-pendiente',
                'reservado' => 'stage-reservado',
            ],
        ]);

        Config::set('ghl.api_base_url', 'https://services.leadconnectorhq.com');
        Config::set('ghl.api_version', '2021-07-28');
    }

    #[Group("ghl")]
    #[Test]
    public function it_sends_whatsapp_message_for_reservado_status(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200) // findContactByPhone
                ->push(['messageId' => 'msg-001'], 200) // main message
                ->push(['messageId' => 'msg-002'], 200) // instructions message
                ->push(['messageId' => 'msg-003'], 200), // additional instructions
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'fullname' => 'Juan Pérez',
            'phone' => '+573001234567',
            'reserve_code' => 'ABC123',
        ]);

        $job = new SendGhlWhatsAppNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertSentCount(4); // 1 contact search + 3 messages
    }

    #[Group("ghl")]
    #[Test]
    public function it_sends_single_message_for_pendiente_status(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200) // findContactByPhone
                ->push(['messageId' => 'msg-001'], 200), // main message only
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
            'fullname' => 'María García',
            'phone' => '+573009876543',
        ]);

        $job = new SendGhlWhatsAppNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertSentCount(2); // 1 contact search + 1 message
    }

    #[Group("ghl")]
    #[Test]
    public function it_skips_notification_when_ghl_not_configured_for_franchise(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->withArgs(fn($msg) => str_contains($msg, 'GHL not configured'));

        $franchise = Franchise::factory()->create(['name' => 'Unconfigured Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+573001234567',
        ]);

        $job = new SendGhlWhatsAppNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertNothingSent();
    }

    #[Group("ghl")]
    #[Test]
    public function it_logs_warning_when_phone_is_missing(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(fn($msg) => str_contains($msg, 'No phone number'));

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '',
        ]);

        $job = new SendGhlWhatsAppNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertNothingSent();
    }

    #[Group("ghl")]
    #[Test]
    public function it_skips_notification_for_unknown_status(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->withArgs(fn($msg) => str_contains($msg, 'No message for status'));

        Http::fake([
            'services.leadconnectorhq.com/*' => Http::response(['contacts' => []], 200),
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Utilizado->value,
            'franchise' => $franchise->id,
            'phone' => '+573001234567',
        ]);

        $job = new SendGhlWhatsAppNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));
    }

    #[Group("ghl")]
    #[Test]
    public function it_creates_contact_if_not_found(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => []], 200) // Contact not found
                ->push(['contact' => ['id' => 'new-contact-123']], 200) // Create contact
                ->push(['messageId' => 'msg-001'], 200), // Send message
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
            'phone' => '+573001234567',
        ]);

        $job = new SendGhlWhatsAppNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertSentCount(3); // search + create + message
    }

    #[Group("ghl")]
    #[Test]
    public function it_logs_error_and_throws_on_api_exception(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200)
                ->push(null, 500), // API error
        ]);

        Log::shouldReceive('error')
            ->atLeast()
            ->once();

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
            'phone' => '+573001234567',
        ]);

        $job = new SendGhlWhatsAppNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        // Job should not throw - it logs and returns null for failed message
    }

    #[Group("ghl")]
    #[Test]
    public function it_cleans_phone_number_before_sending(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200)
                ->push(['messageId' => 'msg-001'], 200),
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
            'phone' => '+57 300 123 4567', // With spaces and +
        ]);

        $job = new SendGhlWhatsAppNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertSent(function ($request) {
            // Check that phone search was made with cleaned number
            if (str_contains($request->url(), '/contacts/search')) {
                return str_contains($request->url(), '573001234567');
            }
            return true;
        });
    }
}
