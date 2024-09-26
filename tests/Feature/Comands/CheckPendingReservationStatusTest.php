<?php

namespace Tests\Feature\Commands;

use App\Models\Reservation;
use App\Console\Commands\CheckPendingReservationStatus;
use App\Mail\ReservationClientNotification\ReservationClientNotification;
use App\Mail\ReservationClientNotification\AlquilatucarroReservationClientNotification;
use App\Enums\ReservationStatus;
use App\Mail\ReservationClientNotification\AlquicarrosReservationClientNotification;
use App\Mail\ReservationClientNotification\AlquilameReservationClientNotification;
use App\Models\Franchise;
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
        Mail::assertQueued(ReservationClientNotification::class);

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
        Mail::assertQueued(AlquilatucarroReservationClientNotification::class);

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
        Mail::assertQueued(AlquilameReservationClientNotification::class);

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
        Mail::assertQueued(AlquicarrosReservationClientNotification::class);

    }

    #[Group("check_pending_reservation_status")]
    #[Test]
    public function when_reservation_receives_cancelled_status_change_to_sin_disponibilidad_status(): void {

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

        $this->assertEquals(ReservationStatus::SinDisponibilidad->value, $reservation->status);
        Mail::assertQueued(ReservationClientNotification::class);

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
        Mail::assertQueued(ReservationClientNotification::class);

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
        Mail::assertNotQueued(ReservationClientNotification::class);

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
        Mail::assertNotQueued(ReservationClientNotification::class);

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
        Mail::assertNotQueued(ReservationClientNotification::class);

    }
}
