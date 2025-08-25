<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

use App\Providers\WatiServiceProvider;
use App\Models\Reservation;
use App\Enums\ReservationStatus;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Http;


class SendPostReservationPickupNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake HTTP responses to prevent real API calls
        Http::fake([
            '*' => Http::response(['result' => true], 200),
        ]);
    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function send_morning_post_reservation_pickup_notification(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn([
                    'result' => true,
                ])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function send_late_post_reservation_pickup_notification(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn([
                    'result' => true,
                ])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function send_morning_post_reservation_pickup_notification_with_monthly_status(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Mensualidad,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function send_late_post_reservation_pickup_notification_with_monthly_status(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Mensualidad,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn([
                    'result' => true,
                ])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function when_morning_post_reservation_pickup_notification_add_contact_fail(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => false])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true]);

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Error registering contact: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function when_late_post_reservation_pickup_notification_add_contact_fail(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => false])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true]);

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Error registering contact: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function when_morning_post_reservation_pickup_notification_send_template_fail(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => false]);

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Error sending notification: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function when_late_post_reservation_pickup_notification_send_template_fail(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => false]);

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Error sending notification: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function in_morning_post_reservation_pickup_notification_only_send_it_once(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

        $this->travel(1)->days();

        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function in_late_post_reservation_pickup_notification_only_send_it_once(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

        $this->travel(1)->days();

        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function in_morning_post_reservation_pickup_notification_for_two_reservations(): void
    {
        $reservation1 = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $reservation2 = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->twice();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->twice();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Contact registered: {$reservation1->fullname} ({$reservation1->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Notification sent: {$reservation1->fullname} ({$reservation1->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Contact registered: {$reservation2->fullname} ({$reservation2->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Notification sent: {$reservation2->fullname} ({$reservation2->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function in_late_post_reservation_pickup_notification_for_two_reservations(): void
    {
        $reservation1 = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $reservation2 = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->twice();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->twice();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Contact registered: {$reservation1->fullname} ({$reservation1->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Notification sent: {$reservation1->fullname} ({$reservation1->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Contact registered: {$reservation2->fullname} ({$reservation2->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Notification sent: {$reservation2->fullname} ({$reservation2->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function send_morning_post_reservation_pickup_notification_only_when_reserved_or_monthly_status(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Pendiente,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true]);

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true]);

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->doesntExpectOutput("Post Morning Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Post Morning Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function send_late_post_reservation_pickup_notification_only_when_reserved_or_monthly_status(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Pendiente,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true]);

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true]);

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->doesntExpectOutput("Post Late Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Post Late Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function in_morning_post_reservation_pickup_notification_if_one_reservation_fails_must_evaluate_others(): void
    {
        $reservation1 = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $reservation2 = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn([
                    'result' => false
                ],
                [
                    'result' => true
                ])
                ->twice();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Contact registered: {$reservation1->fullname} ({$reservation1->phone})")
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Notification sent: {$reservation1->fullname} ({$reservation1->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Contact registered: {$reservation2->fullname} ({$reservation2->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Notification sent: {$reservation2->fullname} ({$reservation2->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function in_late_post_reservation_pickup_notification_if_one_reservation_fails_must_evaluate_others(): void
    {
        $reservation1 = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $reservation2 = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn([
                    'result' => false
                ],
                [
                    'result' => true
                ])
                ->twice();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Contact registered: {$reservation1->fullname} ({$reservation1->phone})")
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Notification sent: {$reservation1->fullname} ({$reservation1->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Contact registered: {$reservation2->fullname} ({$reservation2->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Notification sent: {$reservation2->fullname} ({$reservation2->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function in_morning_post_reservation_pickup_notification_dont_evaluate_monthly_reservation_if_not_meet_dates(): void
    {
        $reservation1 = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $reservation2 = Reservation::factory()->create([
            'pickup_date' => now()->subWeek()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Mensualidad,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Contact registered: {$reservation1->fullname} ({$reservation1->phone})")
            ->expectsOutput("Morning Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Notification sent: {$reservation1->fullname} ({$reservation1->phone})")
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Contact registered: {$reservation2->fullname} ({$reservation2->phone})")
            ->doesntExpectOutput("Morning Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Notification sent: {$reservation2->fullname} ({$reservation2->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function in_late_post_reservation_pickup_notification_dont_evaluate_monthly_reservation_if_not_meet_dates(): void
    {
        $reservation1 = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
        ]);

        $reservation2 = Reservation::factory()->create([
            'pickup_date' => now()->subWeek()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Mensualidad,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true])
                ->once();

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true])
                ->once();

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Contact registered: {$reservation1->fullname} ({$reservation1->phone})")
            ->expectsOutput("Late Post Pickup Notification: Reserve Code: {$reservation1->reserve_code} Notification sent: {$reservation1->fullname} ({$reservation1->phone})")
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Contact registered: {$reservation2->fullname} ({$reservation2->phone})")
            ->doesntExpectOutput("Late Post Pickup Notification: Reserve Code: {$reservation2->reserve_code} Notification sent: {$reservation2->fullname} ({$reservation2->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function dont_send_morning_post_reservation_pickup_notification_if_theres_no_reserve_code(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->subDay()->format('Y-m-d'),
            'pickup_hour' => '18:00',
            'status' => ReservationStatus::Reservado,
            'reserve_code' => null,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true]);

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true]);

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-morning-post-reservation-pickup-notification')
            ->doesntExpectOutput("Post Morning Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Post Morning Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

    #[Group("send-post-reservation-pickup-notification")]
    #[Test]
    public function dont_send_late_post_reservation_pickup_notification_if_theres_no_reserve_code(): void
    {
        $reservation = Reservation::factory()->create([
            'pickup_date' => now()->format('Y-m-d'),
            'pickup_hour' => '08:00',
            'status' => ReservationStatus::Reservado,
            'reserve_code' => null,
        ]);

        $watiMock = $this->mock(WatiServiceProvider::class, function(MockInterface $mock) {
            $mock->shouldReceive('addContact')
                ->andReturn(['result' => true]);

            $mock->shouldReceive('sendTemplateMessage')
                ->andReturn(['result' => true]);

            return $mock;
        });

        $this->app->instance('wati', $watiMock);

        // Act: Call the command to send the notification
        $this->artisan('wati:send-late-post-reservation-pickup-notification')
            ->doesntExpectOutput("Post Late Pickup Notification: Reserve Code: {$reservation->reserve_code} Contact registered: {$reservation->fullname} ({$reservation->phone})")
            ->doesntExpectOutput("Post Late Pickup Notification: Reserve Code: {$reservation->reserve_code} Notification sent: {$reservation->fullname} ({$reservation->phone})")
            ->assertSuccessful();

    }

}
