<?php

namespace Tests\Feature\Ghl;

use App\Enums\ReservationStatus;
use App\Jobs\SendGhlReminderNotificationJob;
use App\Models\Franchise;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GhlReminderCommandsTest extends TestCase
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
    }

    #[Group("ghl")]
    #[Test]
    public function week_command_dispatches_jobs_for_pickup_in_week(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        // Should be included - pickup in 1 week, correct status
        $included = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->addWeek()->format('Y-m-d'),
        ]);

        // Should be excluded - pickup in 2 weeks
        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'DEF456',
            'pickup_date' => now()->addWeeks(2)->format('Y-m-d'),
        ]);

        // Should be excluded - wrong status
        Reservation::factory()->create([
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'GHI789',
            'pickup_date' => now()->addWeek()->format('Y-m-d'),
        ]);

        $this->artisan('ghl:send-week-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertPushed(SendGhlReminderNotificationJob::class, 1);
        Queue::assertPushed(SendGhlReminderNotificationJob::class, function ($job) use ($included) {
            return $job->reservation->id === $included->id
                && $job->reminderType === SendGhlReminderNotificationJob::TYPE_PICKUP;
        });
    }

    #[Group("ghl")]
    #[Test]
    public function three_days_command_dispatches_jobs_for_pickup_in_3_days(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        $included = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        // Should be excluded - pickup in 4 days
        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'DEF456',
            'pickup_date' => now()->addDays(4)->format('Y-m-d'),
        ]);

        $this->artisan('ghl:send-three-days-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertPushed(SendGhlReminderNotificationJob::class, 1);
        Queue::assertPushed(SendGhlReminderNotificationJob::class, function ($job) use ($included) {
            return $job->reservation->id === $included->id;
        });
    }

    #[Group("ghl")]
    #[Test]
    public function same_day_command_dispatches_jobs_for_07_11_window(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        // Should be included - pickup at 09:00
        $included = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '09:00:00',
        ]);

        // Should be excluded - pickup at 12:00 (outside window)
        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'DEF456',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '12:00:00',
        ]);

        $this->artisan('ghl:send-same-day-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertPushed(SendGhlReminderNotificationJob::class, 1);
        Queue::assertPushed(SendGhlReminderNotificationJob::class, function ($job) use ($included) {
            return $job->reservation->id === $included->id
                && $job->reminderType === SendGhlReminderNotificationJob::TYPE_SAME_DAY;
        });
    }

    #[Group("ghl")]
    #[Test]
    public function same_day_morning_command_dispatches_jobs_for_11_15_window(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        // Should be included - pickup at 13:00
        $included = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '13:00:00',
        ]);

        // Should be excluded - pickup at 16:00 (outside window)
        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'DEF456',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '16:00:00',
        ]);

        $this->artisan('ghl:send-same-day-morning-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertPushed(SendGhlReminderNotificationJob::class, 1);
        Queue::assertPushed(SendGhlReminderNotificationJob::class, function ($job) use ($included) {
            return $job->reservation->id === $included->id;
        });
    }

    #[Group("ghl")]
    #[Test]
    public function same_day_late_command_dispatches_jobs_for_15_24_window(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        // Should be included - pickup at 18:00
        $included = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '18:00:00',
        ]);

        // Should be excluded - pickup at 10:00 (outside window)
        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'DEF456',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '10:00:00',
        ]);

        $this->artisan('ghl:send-same-day-late-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertPushed(SendGhlReminderNotificationJob::class, 1);
        Queue::assertPushed(SendGhlReminderNotificationJob::class, function ($job) use ($included) {
            return $job->reservation->id === $included->id;
        });
    }

    #[Group("ghl")]
    #[Test]
    public function morning_post_pickup_command_dispatches_jobs(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        // Should be included - pickup yesterday at 18:00
        $included = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00:00',
        ]);

        // Should be excluded - pickup yesterday at 10:00 (too early)
        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'DEF456',
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '10:00:00',
        ]);

        $this->artisan('ghl:send-morning-post-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertPushed(SendGhlReminderNotificationJob::class, 1);
        Queue::assertPushed(SendGhlReminderNotificationJob::class, function ($job) use ($included) {
            return $job->reservation->id === $included->id
                && $job->reminderType === SendGhlReminderNotificationJob::TYPE_POST_PICKUP;
        });
    }

    #[Group("ghl")]
    #[Test]
    public function late_post_pickup_command_dispatches_jobs(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        // Should be included - pickup today at 10:00
        $included = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '10:00:00',
        ]);

        // Should be excluded - pickup today at 18:00 (too late)
        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => 'DEF456',
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '18:00:00',
        ]);

        $this->artisan('ghl:send-late-post-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertPushed(SendGhlReminderNotificationJob::class, 1);
        Queue::assertPushed(SendGhlReminderNotificationJob::class, function ($job) use ($included) {
            return $job->reservation->id === $included->id;
        });
    }

    #[Group("ghl")]
    #[Test]
    public function command_skips_reservations_without_phone(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '',
            'reserve_code' => 'ABC123',
            'pickup_date' => now()->addWeek()->format('Y-m-d'),
        ]);

        $this->artisan('ghl:send-week-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertNotPushed(SendGhlReminderNotificationJob::class);
    }

    #[Group("ghl")]
    #[Test]
    public function command_skips_reservations_without_reserve_code(): void
    {
        $franchise = Franchise::factory()->create(['name' => 'Test Franchise']);

        Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'franchise' => $franchise->id,
            'phone' => '+34612345678',
            'reserve_code' => '',
            'pickup_date' => now()->addWeek()->format('Y-m-d'),
        ]);

        $this->artisan('ghl:send-week-reservation-pickup-notification')
            ->assertExitCode(0);

        Queue::assertNotPushed(SendGhlReminderNotificationJob::class);
    }
}
