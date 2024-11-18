<?php

namespace Tests\Feature\Commands;

use App\Models\Franchise;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use App\Console\Commands\CheckPendingReservationStatus;
use App\Mail\ReservationClientNotification\Reserved\ReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Reserved\AlquilatucarroReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Reserved\AlquilameReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Reserved\AlquicarrosReservedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\FailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquilatucarroFailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquilameFailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Failed\AlquicarrosFailedReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\PendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquilatucarroPendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquilamePendingReservationClientNotification;
use App\Mail\ReservationClientNotification\Pending\AlquicarrosPendingReservationClientNotification;

use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckPendingReservationStatusTest extends TestCase {
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
        Mail::fake();

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_reservation_receives_confirmed_status_change_to_reservado_status(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-confirmed-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::Reservado->value, $reservation->status);
        Mail::assertQueued(ReservedReservationClientNotification::class);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_alquilatucarro_reservation_is_confirmed_status_send_mail_notification(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-confirmed-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::Reservado->value, $reservation->status);
        Mail::assertQueued(AlquilatucarroReservedReservationClientNotification::class);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_alquilame_reservation_is_confirmed_status_send_mail_notification(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-confirmed-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilame'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::Reservado->value, $reservation->status);
        Mail::assertQueued(AlquilameReservedReservationClientNotification::class);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_alquicarros_reservation_is_confirmed_status_send_mail_notification(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-confirmed-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquicarros'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::Reservado->value, $reservation->status);
        Mail::assertQueued(AlquicarrosReservedReservationClientNotification::class);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_reservation_receives_cancelled_status_change_to_indeterminado_status(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-cancelled-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::Indeterminado->value, $reservation->status);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_reservation_receives_failed_status_change_to_sin_disponibilidad_status(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-failed-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::SinDisponibilidad->value, $reservation->status);
        Mail::assertQueued(FailedReservationClientNotification::class);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_reservation_receives_waitlist_status_change_to_indeterminado_status(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-waitlist-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::Indeterminado->value, $reservation->status);
        Mail::assertNotQueued(ReservedReservationClientNotification::class);
        Mail::assertNotQueued(PendingReservationClientNotification::class);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_reservation_receives_onrequest_status_change_to_indeterminado_status(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-onrequest-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::Indeterminado->value, $reservation->status);
        Mail::assertNotQueued(ReservedReservationClientNotification::class);
        Mail::assertNotQueued(PendingReservationClientNotification::class);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_reservation_receives_pending_status_do_nothing(): void {

        $xml = view('localiza.tests.responses.vehretres.vehretres-pending-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $franchise = Franchise::factory()->create([
            'name'  =>  'alquilatucarro'
        ]);

        $reservation = Reservation::factory()->create([
            'fullname'  => 'test',
            'status' => ReservationStatus::Pendiente->value,
            'franchise' => $franchise->id,
        ]);

        $this->artisan('reservations:check-status')->assertSuccessful();

        $reservation->refresh();

        $this->assertEquals(ReservationStatus::Pendiente->value, $reservation->status);
        Mail::assertNotQueued(ReservedReservationClientNotification::class);
        Mail::assertNotQueued(PendingReservationClientNotification::class);

    }
}
