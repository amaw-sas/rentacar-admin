<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

use App\Models\User;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

    }

    #[Group("reservation")]
    #[Test]
    public function create_a_reservation(){
        $reservationData = Reservation::factory()->make()->toArray();

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
    }

    #[Group("reservation")]
    #[Test]
    public function when_create_a_post_and_no_data_show_error_messages(){
        $reservationData = [];

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('errors.fullname.0', "El campo Nombre completo es requerido.")
                    ->where('errors.identification_type.0', "El campo Tipo de identificación es requerido.")
                    ->where('errors.identification.0', "El campo Identificación es requerido.")
                    ->where('errors.phone.0', "El campo Teléfono es requerido.")
                    ->where('errors.email.0', "El campo Correo eléctronico es requerido.")
                    ->where('errors.category.0', "El campo Categoría es requerido.")
                    ->where('errors.pickup_location.0', "El campo Lugar de recogida es requerido.")
                    ->where('errors.return_location.0', "El campo Lugar de retorno es requerido.")
                    ->where('errors.pickup_date.0', "El campo Día de recogida es requerido.")
                    ->where('errors.return_date.0', "El campo Día de retorno es requerido.")
                    ->where('errors.pickup_hour.0', "El campo Hora de recogida es requerido.")
                    ->where('errors.return_hour.0', "El campo Hora de retorno es requerido.")
                    ->where('errors.selected_days.0', "El campo Días reservados es requerido.")
                    ->where('errors.coverage_days.0', "El campo Días seguro es requerido.")
                    ->where('errors.coverage_price.0', "El campo Precio seguro es requerido.")
                    ->where('errors.tax_fee.0', "El campo Tasa administrativa es requerido.")
                    ->where('errors.iva_fee.0', "El campo Precio IVA es requerido.")
                    ->where('errors.total_price.0', "El campo Precio final es requerido.")
                    ->where('errors.franchise.0', "El campo Franquicia es requerido.")
                    ->where('errors.status.0', "El campo Estado es requerido.")

                    ->etc()
        );
    }

    #[Group("reservation")]
    #[Test]
    public function update_a_reservation(){
        $reservation = Reservation::factory()->create();
        $reservationData = $reservation->toArray();
        $reservationData['fullname'] = 'testing';
        $reservationData['reserve_code'] = '012345';

        $response = $this
            ->actingAs($this->user)
            ->putJson(route('reservations.update', [
                'reservation'   =>  $reservation->id,
            ]), $reservationData);

        $reservation->refresh();
        $this->assertEquals('testing', $reservation->fullname);
        $this->assertEquals('012345', $reservation->reserve_code);
    }

    #[Group("reservation")]
    #[Test]
    public function delete_a_reservation(){
        $reservation = Reservation::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->deleteJson(route('reservations.destroy', [
                'reservation'   =>  $reservation->id,
            ]));

        $reservation = Reservation::first();
        $this->assertNull($reservation);
    }

}
