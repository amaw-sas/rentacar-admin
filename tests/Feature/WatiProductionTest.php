<?php

namespace Tests\Feature;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class WatiProductionTest extends TestCase
{
    use RefreshDatabase;

    #[Group("wati_production")]
    #[Group("production")]
    #[Test]
    public function send_same_day_morning_reservation_pickup_notification()
    {
        $this->markTestSkipped('This test is for production environment.');

        $actualTestPhone = config('wati.test_phone');

        // Arrange: Create a reservation with pickup date set to tomorrow
        $reservation = Reservation::factory()->create([
            'fullname' => 'Test User',
            'phone' => $actualTestPhone,
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '16:00',
            'reserve_code' => 'AVC123',
            'status' => ReservationStatus::Reservado,
        ]);

        $this->artisan('send-same-day-morning-reservation-pickup-notification')
            ->expectsOutput("Same Day Morning Pickup Notification: Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Same Day Morning Pickup Notification: Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("wati_production")]
    #[Group("production")]
    #[Test]
    public function send_same_day_late_reservation_pickup_notification()
    {
        $this->markTestSkipped('This test is for production environment.');

        $actualTestPhone = config('wati.test_phone');

        // Arrange: Create a reservation with pickup date set to tomorrow
        $reservation = Reservation::factory()->create([
            'fullname' => 'Test User',
            'phone' => $actualTestPhone,
            'pickup_date' => now()->addDay()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'reserve_code' => 'AVC123',
            'status' => ReservationStatus::Reservado,
        ]);

        $this->artisan('send-same-day-late-reservation-pickup-notification')
            ->expectsOutput("Same Day Late Pickup Notification: Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Same Day Late Pickup Notification: Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("wati_production")]
    #[Group("production")]
    #[Test]
    public function send_three_days_reservation_pickup_notification()
    {
        $this->markTestSkipped('This test is for production environment.');

        $actualTestPhone = config('wati.test_phone');

        // Arrange: Create a reservation with pickup date set to tomorrow
        $reservation = Reservation::factory()->create([
            'fullname' => 'Test User',
            'phone' => $actualTestPhone,
            'pickup_date' => now()->addDays(3)->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'reserve_code' => 'AVC123',
            'status' => ReservationStatus::Reservado,
        ]);

        $this->artisan('send-three-days-reservation-pickup-notification')
            ->expectsOutput("Three Days Pickup Notification: Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Three Days Pickup Notification: Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("wati_production")]
    #[Group("production")]
    #[Test]
    public function send_week_reservation_pickup_notification()
    {
        $this->markTestSkipped('This test is for production environment.');

        $actualTestPhone = config('wati.test_phone');

        // Arrange: Create a reservation with pickup date set to tomorrow
        $reservation = Reservation::factory()->create([
            'fullname' => 'Test User',
            'phone' => $actualTestPhone,
            'pickup_date' => now()->addWeek()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'reserve_code' => 'AVC123',
            'status' => ReservationStatus::Reservado,
        ]);

        $this->artisan('send-week-reservation-pickup-notification')
            ->expectsOutput("Week Pickup Notification: Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Week Pickup Notification: Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }
}
