<?php

namespace Tests\Feature\Ghl;

use App\Enums\ReservationStatus;
use App\Jobs\SendGhlReminderNotificationJob;
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

class SendGhlReminderNotificationJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Queue::fake();

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
    public function it_sends_pickup_reminder_message(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200)
                ->push(['messageId' => 'msg-001'], 200),
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'fullname' => 'Juan Pérez',
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->addWeek()->format('Y-m-d'),
            'pickup_hour' => '10:00:00',
        ]);

        $job = new SendGhlReminderNotificationJob(
            $reservation,
            SendGhlReminderNotificationJob::TYPE_PICKUP
        );
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertSentCount(2); // contact search + message
    }

    #[Group("ghl")]
    #[Test]
    public function it_sends_same_day_reminder_message(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200)
                ->push(['messageId' => 'msg-001'], 200),
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'fullname' => 'María García',
            'phone' => '+34612345678',
            'reserve_code' => 'DEF456',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '09:00:00',
        ]);

        $job = new SendGhlReminderNotificationJob(
            $reservation,
            SendGhlReminderNotificationJob::TYPE_SAME_DAY
        );
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertSentCount(2);
    }

    #[Group("ghl")]
    #[Test]
    public function it_sends_post_pickup_message(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200)
                ->push(['messageId' => 'msg-001'], 200),
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'fullname' => 'Pedro López',
            'phone' => '+34612345678',
            'reserve_code' => 'GHI789',
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00:00',
        ]);

        $job = new SendGhlReminderNotificationJob(
            $reservation,
            SendGhlReminderNotificationJob::TYPE_POST_PICKUP
        );
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertSentCount(2);
    }

    #[Group("ghl")]
    #[Test]
    public function it_skips_when_phone_is_missing(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(fn($msg) => str_contains($msg, 'has no phone number'));

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '',
        ]);

        $job = new SendGhlReminderNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertNothingSent();
    }

    #[Group("ghl")]
    #[Test]
    public function it_skips_when_franchise_not_configured(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->withArgs(fn($msg) => str_contains($msg, 'not configured for GHL'));
        Log::shouldReceive('error')->zeroOrMoreTimes();
        Log::shouldReceive('warning')->zeroOrMoreTimes();

        $franchise = Franchise::factory()->create(['name' => 'Unconfigured Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
        ]);

        $job = new SendGhlReminderNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertNothingSent();
    }

    #[Group("ghl")]
    #[Test]
    public function it_cleans_spanish_phone_number(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200)
                ->push(['messageId' => 'msg-001'], 200),
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '612345678', // Spanish 9-digit number
            'reserve_code' => 'ABC123',
        ]);

        $job = new SendGhlReminderNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));

        Http::assertSent(function ($request) {
            if (str_contains($request->url(), '/contacts/search')) {
                // Should be cleaned to +34612345678
                return str_contains($request->url(), '34612345678');
            }
            return true;
        });
    }

    #[Group("ghl")]
    #[Test]
    public function it_uses_custom_log_prefix(): void
    {
        Log::shouldReceive('info')
            ->atLeast()
            ->once()
            ->withArgs(fn($msg) => str_contains($msg, 'Custom Prefix'));
        Log::shouldReceive('info')->zeroOrMoreTimes(); // Catch-all for other info logs
        Log::shouldReceive('error')->zeroOrMoreTimes();
        Log::shouldReceive('warning')->zeroOrMoreTimes();

        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200)
                ->push(['messageId' => 'msg-001'], 200),
        ]);

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
        ]);

        $job = new SendGhlReminderNotificationJob(
            $reservation,
            SendGhlReminderNotificationJob::TYPE_PICKUP,
            'Custom Prefix'
        );
        $job->handle(app(\App\Services\Ghl\GhlClient::class));
    }

    #[Group("ghl")]
    #[Test]
    public function it_logs_error_on_api_failure(): void
    {
        Http::fake([
            'services.leadconnectorhq.com/*' => Http::sequence()
                ->push(['contacts' => [['id' => 'contact-123']]], 200)
                ->push(null, 500),
        ]);

        Log::shouldReceive('error')
            ->atLeast()
            ->once();

        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
        ]);

        $job = new SendGhlReminderNotificationJob($reservation);
        $job->handle(app(\App\Services\Ghl\GhlClient::class));
    }
}
