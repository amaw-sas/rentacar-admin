<?php

namespace Tests\Unit\Ghl;

use App\Enums\ReservationStatus;
use App\Models\Category;
use App\Models\Location;
use App\Models\Reservation;
use App\Services\Ghl\GhlClient;
use App\Services\Ghl\GhlOpportunityMapper;
use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GhlOpportunityMapperTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Create a mock reservation with specified attributes.
     */
    protected function createMockReservation(array $attributes = []): Reservation
    {
        $reservation = Mockery::mock(Reservation::class)->makePartial();

        // Default values
        $defaults = [
            'status' => ReservationStatus::Reservado->value,
            'reserve_code' => 'TEST123',
            'total_price' => 500000,
            'pickup_date' => Carbon::parse('2024-06-15'),
            'return_date' => Carbon::parse('2024-06-20'),
            'pickup_hour' => Carbon::parse('10:00'),
            'return_hour' => Carbon::parse('18:00'),
        ];

        $merged = array_merge($defaults, $attributes);

        foreach ($merged as $key => $value) {
            $reservation->shouldReceive('getAttribute')
                ->with($key)
                ->andReturn($value);
            $reservation->{$key} = $value;
        }

        // Mock related objects
        $category = new \stdClass();
        $category->name = $attributes['category_name'] ?? 'SUV Compacta';
        $reservation->shouldReceive('getAttribute')
            ->with('categoryObject')
            ->andReturn($category);
        $reservation->categoryObject = $category;

        // Mock pickup location with city
        $pickupCity = new \stdClass();
        $pickupCity->name = $attributes['pickup_city_name'] ?? 'Bogotá';

        $pickupLocation = new \stdClass();
        $pickupLocation->name = $attributes['pickup_location_name'] ?? 'Aeropuerto';
        $pickupLocation->city = $pickupCity;
        $reservation->shouldReceive('getAttribute')
            ->with('pickupLocation')
            ->andReturn($pickupLocation);
        $reservation->pickupLocation = $pickupLocation;

        // Mock return location with city
        $returnCity = new \stdClass();
        $returnCity->name = $attributes['return_city_name'] ?? 'Medellín';

        $returnLocation = new \stdClass();
        $returnLocation->name = $attributes['return_location_name'] ?? 'Centro';
        $returnLocation->city = $returnCity;
        $reservation->shouldReceive('getAttribute')
            ->with('returnLocation')
            ->andReturn($returnLocation);
        $reservation->returnLocation = $returnLocation;

        return $reservation;
    }

    #[Group("ghl")]
    #[Test]
    public function it_builds_opportunity_data_for_create(): void
    {
        $reservation = $this->createMockReservation([
            'status' => ReservationStatus::Reservado->value,
            'reserve_code' => 'TEST123',
            'total_price' => 500000,
        ]);

        $client = Mockery::mock(GhlClient::class);
        $client->shouldReceive('getStageId')
            ->with('reservado')
            ->andReturn('stage-123');

        $data = GhlOpportunityMapper::toGhlOpportunity($reservation, $client);

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('pipelineStageId', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('monetaryValue', $data);
        $this->assertArrayHasKey('customFields', $data);
        $this->assertEquals('stage-123', $data['pipelineStageId']);
        $this->assertEquals('open', $data['status']);
        $this->assertEquals(500000.0, $data['monetaryValue']);
        $this->assertStringContainsString('TEST123', $data['name']);
    }

    #[Group("ghl")]
    #[Test]
    public function it_preserves_sede_origen_when_updating_opportunity(): void
    {
        $reservation = $this->createMockReservation([
            'status' => ReservationStatus::Reservado->value,
            'reserve_code' => 'TEST456',
            'total_price' => 750000,
        ]);

        $client = Mockery::mock(GhlClient::class);
        $client->shouldReceive('getStageId')
            ->with('reservado')
            ->andReturn('stage-456');

        // Simulate existing opportunity with sede_origen set by advisor
        $existingOpportunity = [
            'id' => 'opp-123',
            'name' => 'Previous Name',
            'customFields' => [
                [
                    'key' => 'sede_origen',
                    'value' => 'Landing Barranquilla',
                ],
                [
                    'key' => 'fecha_recogida',
                    'value' => '2024-01-01',
                ],
            ],
        ];

        $data = GhlOpportunityMapper::toGhlOpportunityUpdate($reservation, $client, $existingOpportunity);

        // Check that sede_origen is preserved
        $sedeOrigenField = collect($data['customFields'])
            ->firstWhere('key', 'sede_origen');

        $this->assertNotNull($sedeOrigenField, 'sede_origen should be preserved');
        $this->assertEquals('Landing Barranquilla', $sedeOrigenField['field_value']);
    }

    #[Group("ghl")]
    #[Test]
    public function it_preserves_sede_origen_with_field_value_format(): void
    {
        $reservation = $this->createMockReservation([
            'status' => ReservationStatus::Pendiente->value,
        ]);

        $client = Mockery::mock(GhlClient::class);
        $client->shouldReceive('getStageId')
            ->with('pendiente')
            ->andReturn('stage-pendiente');

        // Some GHL responses use field_value instead of value
        $existingOpportunity = [
            'id' => 'opp-789',
            'customFields' => [
                [
                    'key' => 'sede_origen',
                    'field_value' => 'Facebook Ads',
                ],
            ],
        ];

        $data = GhlOpportunityMapper::toGhlOpportunityUpdate($reservation, $client, $existingOpportunity);

        $sedeOrigenField = collect($data['customFields'])
            ->firstWhere('key', 'sede_origen');

        $this->assertNotNull($sedeOrigenField);
        $this->assertEquals('Facebook Ads', $sedeOrigenField['field_value']);
    }

    #[Group("ghl")]
    #[Test]
    public function it_does_not_add_empty_sede_origen(): void
    {
        $reservation = $this->createMockReservation([
            'status' => ReservationStatus::Reservado->value,
        ]);

        $client = Mockery::mock(GhlClient::class);
        $client->shouldReceive('getStageId')
            ->with('reservado')
            ->andReturn('stage-123');

        // Existing opportunity with empty sede_origen
        $existingOpportunity = [
            'id' => 'opp-empty',
            'customFields' => [
                [
                    'key' => 'sede_origen',
                    'value' => '',
                ],
            ],
        ];

        $data = GhlOpportunityMapper::toGhlOpportunityUpdate($reservation, $client, $existingOpportunity);

        // Empty sede_origen should not be added
        $sedeOrigenField = collect($data['customFields'])
            ->firstWhere('key', 'sede_origen');

        $this->assertNull($sedeOrigenField, 'Empty sede_origen should not be preserved');
    }

    #[Group("ghl")]
    #[Test]
    public function it_handles_update_without_existing_opportunity(): void
    {
        $reservation = $this->createMockReservation([
            'status' => ReservationStatus::Reservado->value,
            'total_price' => 300000,
        ]);

        $client = Mockery::mock(GhlClient::class);
        $client->shouldReceive('getStageId')
            ->with('reservado')
            ->andReturn('stage-abc');

        // No existing opportunity (null)
        $data = GhlOpportunityMapper::toGhlOpportunityUpdate($reservation, $client, null);

        $this->assertArrayHasKey('customFields', $data);
        $this->assertEquals(300000.0, $data['monetaryValue']);

        // Should not have sede_origen since there's no existing data
        $sedeOrigenField = collect($data['customFields'])
            ->firstWhere('key', 'sede_origen');

        $this->assertNull($sedeOrigenField);
    }

    #[Group("ghl")]
    #[Test]
    public function it_overwrites_non_preserved_fields(): void
    {
        $reservation = $this->createMockReservation([
            'status' => ReservationStatus::Reservado->value,
            'reserve_code' => 'NEW-CODE',
        ]);

        $client = Mockery::mock(GhlClient::class);
        $client->shouldReceive('getStageId')
            ->with('reservado')
            ->andReturn('stage-123');

        $existingOpportunity = [
            'id' => 'opp-123',
            'customFields' => [
                [
                    'key' => 'codigo_de_reserva',
                    'value' => 'OLD-CODE',
                ],
                [
                    'key' => 'sede_origen',
                    'value' => 'TikTok Organic',
                ],
            ],
        ];

        $data = GhlOpportunityMapper::toGhlOpportunityUpdate($reservation, $client, $existingOpportunity);

        // codigo_de_reserva should be updated (not preserved)
        $codigoField = collect($data['customFields'])
            ->firstWhere('key', 'codigo_de_reserva');

        $this->assertNotNull($codigoField);
        $this->assertEquals('NEW-CODE', $codigoField['field_value']);

        // sede_origen should be preserved
        $sedeOrigenField = collect($data['customFields'])
            ->firstWhere('key', 'sede_origen');

        $this->assertNotNull($sedeOrigenField);
        $this->assertEquals('TikTok Organic', $sedeOrigenField['field_value']);
    }

    #[Group("ghl")]
    #[Test]
    public function it_maps_status_to_correct_stage_key(): void
    {
        $this->assertTrue(GhlOpportunityMapper::hasGhlStage('Nueva'));
        $this->assertTrue(GhlOpportunityMapper::hasGhlStage('Pendiente'));
        $this->assertTrue(GhlOpportunityMapper::hasGhlStage('Reservado'));
        $this->assertTrue(GhlOpportunityMapper::hasGhlStage('Sin disponibilidad'));
        $this->assertTrue(GhlOpportunityMapper::hasGhlStage('Mensualidad'));

        // These don't have mapped stages
        $this->assertFalse(GhlOpportunityMapper::hasGhlStage('Utilizado'));
        $this->assertFalse(GhlOpportunityMapper::hasGhlStage('Cancelado'));
        $this->assertFalse(GhlOpportunityMapper::hasGhlStage(null));
    }

    #[Group("ghl")]
    #[Test]
    public function it_includes_all_reservation_custom_fields(): void
    {
        $reservation = $this->createMockReservation([
            'status' => ReservationStatus::Reservado->value,
            'pickup_date' => Carbon::parse('2024-06-15'),
            'return_date' => Carbon::parse('2024-06-20'),
            'pickup_hour' => Carbon::parse('10:00'),
            'return_hour' => Carbon::parse('18:00'),
            'reserve_code' => 'RES-789',
        ]);

        $client = Mockery::mock(GhlClient::class);
        $client->shouldReceive('getStageId')
            ->andReturn('stage-123');

        $data = GhlOpportunityMapper::toGhlOpportunity($reservation, $client);

        $fieldKeys = collect($data['customFields'])->pluck('key')->toArray();

        // New field structure matching GHL configuration
        $this->assertContains('ciudad_de_recogida', $fieldKeys);
        $this->assertContains('ciudad_de_entrega', $fieldKeys);
        $this->assertContains('fecha_hora_recogida', $fieldKeys);
        $this->assertContains('fecha_hora_entrega', $fieldKeys);
        $this->assertContains('codigo_de_reserva', $fieldKeys);
        $this->assertContains('gama', $fieldKeys);
    }
}
