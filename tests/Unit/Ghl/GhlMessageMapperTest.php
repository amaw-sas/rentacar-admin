<?php

namespace Tests\Unit\Ghl;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Services\Ghl\GhlMessageMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GhlMessageMapperTest extends TestCase
{
    use RefreshDatabase;

    #[Group("ghl")]
    #[Test]
    public function it_returns_message_for_reservado_status(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'fullname' => 'Juan Pérez',
            'reserve_code' => 'ABC123',
        ]);

        $message = GhlMessageMapper::getMessage($reservation);

        $this->assertNotNull($message);
        $this->assertStringContainsString('Reserva Confirmada', $message);
        $this->assertStringContainsString('Juan Pérez', $message);
        $this->assertStringContainsString('ABC123', $message);
    }

    #[Group("ghl")]
    #[Test]
    public function it_returns_message_for_pendiente_status(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Pendiente->value,
            'fullname' => 'María García',
        ]);

        $message = GhlMessageMapper::getMessage($reservation);

        $this->assertNotNull($message);
        $this->assertStringContainsString('Reserva Pendiente', $message);
        $this->assertStringContainsString('María García', $message);
    }

    #[Group("ghl")]
    #[Test]
    public function it_returns_message_for_sin_disponibilidad_status(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::SinDisponibilidad->value,
            'fullname' => 'Carlos López',
        ]);

        $message = GhlMessageMapper::getMessage($reservation);

        $this->assertNotNull($message);
        $this->assertStringContainsString('Lo sentimos', $message);
        $this->assertStringContainsString('Carlos López', $message);
    }

    #[Group("ghl")]
    #[Test]
    public function it_returns_message_for_mensualidad_status(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Mensualidad->value,
            'fullname' => 'Ana Martínez',
        ]);

        $message = GhlMessageMapper::getMessage($reservation);

        $this->assertNotNull($message);
        $this->assertStringContainsString('Reserva Mensual Confirmada', $message);
        $this->assertStringContainsString('Ana Martínez', $message);
    }

    #[Group("ghl")]
    #[Test]
    public function it_returns_null_for_unknown_status(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Utilizado->value,
        ]);

        $message = GhlMessageMapper::getMessage($reservation);

        $this->assertNull($message);
    }

    #[Group("ghl")]
    #[Test]
    public function it_returns_additional_messages_only_for_reservado(): void
    {
        $reservadoReservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
        ]);

        $pendienteReservation = Reservation::factory()->create([
            'status' => ReservationStatus::Pendiente->value,
        ]);

        $reservadoMessages = GhlMessageMapper::getAdditionalMessages($reservadoReservation);
        $pendienteMessages = GhlMessageMapper::getAdditionalMessages($pendienteReservation);

        $this->assertCount(2, $reservadoMessages);
        $this->assertCount(0, $pendienteMessages);
    }

    #[Group("ghl")]
    #[Test]
    public function additional_messages_contain_instructions(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
        ]);

        $messages = GhlMessageMapper::getAdditionalMessages($reservation);

        $this->assertStringContainsString('Instrucciones', $messages[0]);
        $this->assertStringContainsString('documento de identidad', $messages[0]);
        $this->assertStringContainsString('Información Adicional', $messages[1]);
        $this->assertStringContainsString('tanque lleno', $messages[1]);
    }

    #[Group("ghl")]
    #[Test]
    #[DataProvider('phoneCleanupProvider')]
    public function it_cleans_phone_numbers_correctly(string $input, string $expected): void
    {
        $result = GhlMessageMapper::cleanupPhone($input);

        $this->assertEquals($expected, $result);
    }

    public static function phoneCleanupProvider(): array
    {
        return [
            'removes plus sign' => ['+573001234567', '573001234567'],
            'removes spaces' => ['57 300 123 4567', '573001234567'],
            'removes plus and spaces' => ['+57 300 123 4567', '573001234567'],
            'trims whitespace' => ['  573001234567  ', '573001234567'],
            'handles clean number' => ['573001234567', '573001234567'],
        ];
    }

    #[Group("ghl")]
    #[Test]
    public function reservado_message_includes_pickup_and_return_details(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => ReservationStatus::Reservado->value,
            'pickup_date' => '2024-03-15',
            'pickup_hour' => '10:00',
            'return_date' => '2024-03-20',
            'return_hour' => '18:00',
        ]);

        $message = GhlMessageMapper::getMessage($reservation);

        $this->assertStringContainsString('Recogida', $message);
        $this->assertStringContainsString('Devolución', $message);
        $this->assertStringContainsString('Fecha:', $message);
        $this->assertStringContainsString('Hora:', $message);
    }
}
