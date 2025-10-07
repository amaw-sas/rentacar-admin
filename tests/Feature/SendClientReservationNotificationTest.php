<?php

namespace Tests\Feature;

use Mockery;
use Mockery\MockInterface;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Events\SendReservationNotificationEvent;
use App\Events\NewMonthlyReservationEvent;
use App\Listeners\SendClientReservationNotification\SendClientReservationMailNotificationListener;
use App\Listeners\SendClientReservationNotification\SendClientReservationWhatsappNotificationListener;
use App\Facades\Wati;
use App\Models\Reservation;
use App\Enums\ReservationStatus;

class SendClientReservationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        Wati::shouldReceive('addContact')
            ->andReturn(['result' => true]);

        Wati::shouldReceive('sendTemplateMessage')
            ->andReturn(['result' => true]);

        Wati::shouldReceive('sendTemplateMessages')
            ->andReturn(['result' => true]);

    }

    #[Group("send-client-reservation-notification")]
    #[Test]
    public function when_reservation_is_reserved_trigger_whatsapp_notification_and_use_reserved_method(): void
    {
        $listener = $this->partialMock(SendClientReservationWhatsappNotificationListener::class, function (MockInterface $mock) {
            $mock
            ->shouldReceive('sendReservedReservationNotification')
            ->once();
        });


        $reservation = Reservation::factory()->create(['status' => ReservationStatus::Reservado->value]);

        SendReservationNotificationEvent::dispatch($reservation);

    }

    #[Group("send-client-reservation-notification")]
    #[Test]
    public function when_reservation_is_pending_trigger_whatsapp_notification_and_use_pending_method(): void
    {
        $listener = $this->partialMock(SendClientReservationWhatsappNotificationListener::class, function (MockInterface $mock) {
            $mock
            ->shouldReceive('sendPendingReservationNotification')
            ->once();
        });


        $reservation = Reservation::factory()->create(['status' => ReservationStatus::Pendiente->value]);

        SendReservationNotificationEvent::dispatch($reservation);

    }

    #[Group("send-client-reservation-notification")]
    #[Test]
    public function when_reservation_is_failed_trigger_whatsapp_notification_and_use_failed_method(): void
    {
        $listener = $this->partialMock(SendClientReservationWhatsappNotificationListener::class, function (MockInterface $mock) {
            $mock
            ->shouldReceive('sendFailedReservationNotification')
            ->once();
        });


        $reservation = Reservation::factory()->create(['status' => ReservationStatus::SinDisponibilidad->value]);

        SendReservationNotificationEvent::dispatch($reservation);

    }

    #[Group("send-client-reservation-notification")]
    #[Test]
    public function when_reservation_is_month_trigger_whatsapp_notification_and_use_month_method(): void
    {
        $listener = $this->partialMock(SendClientReservationWhatsappNotificationListener::class, function (MockInterface $mock) {
            $mock
            ->shouldReceive('sendMonthReservationNotification')
            ->once();
        });


        $reservation = Reservation::factory()->create(['status' => ReservationStatus::Mensualidad->value]);

        SendReservationNotificationEvent::dispatch($reservation);

    }
}
