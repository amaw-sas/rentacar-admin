<?php

namespace App\Services\Ghl;

use App\Enums\ReservationStatus;
use App\Models\Reservation;

class GhlOpportunityMapper
{
    /**
     * Custom fields that should be preserved when updating (not overwritten by reservation data).
     */
    protected static array $preservedCustomFields = [
        'sede_origen',
    ];

    /**
     * Map ReservationStatus to GHL stage config key.
     */
    protected static array $statusToStageKey = [
        'Nueva' => 'pendiente',
        'Pendiente' => 'pendiente',
        'Reservado' => 'reservado',
        'Pendiente Modificar' => 'pendiente_modificar',
        'Utilizado' => 'utilizado',
        'Sin disponibilidad' => 'sin_disponibilidad',
        'Mensualidad' => 'mensualidad',
        // Statuses that go to 'lost' don't need a stage (handled by $statusToGhlStatus)
    ];

    /**
     * Map ReservationStatus to GHL native status (open/won/lost/abandoned).
     * Statuses not listed here default to 'open'.
     */
    protected static array $statusToGhlStatus = [
        'No Contactado' => 'lost',
        'No recogido' => 'lost',
        'Baneado' => 'lost',
        'Cancelado' => 'lost',
        // Note: 'Sin disponibilidad' stays 'open' initially, GHL automation handles 48h delay to 'lost'
        // Note: 'Utilizado' stays 'open', GHL automation handles transition to 'won' after workflow completes
    ];

    /**
     * Map a Reservation to GHL Opportunity data for create.
     */
    public static function toGhlOpportunity(Reservation $reservation, GhlClient $client): array
    {
        $stageId = $client->getStageId(self::getStageKey($reservation->status));
        $ghlStatus = self::getGhlStatus($reservation->status);

        $data = [
            'name' => self::buildOpportunityName($reservation),
            'pipelineStageId' => $stageId,
            'status' => $ghlStatus,
            'monetaryValue' => (float) $reservation->total_price,
            'customFields' => self::buildCustomFields($reservation),
        ];

        return $data;
    }

    /**
     * Map a Reservation to GHL Opportunity data for update.
     *
     * @param Reservation $reservation The reservation to map
     * @param GhlClient $client The GHL client
     * @param array|null $existingOpportunity The existing opportunity data (to preserve certain custom fields)
     */
    public static function toGhlOpportunityUpdate(Reservation $reservation, GhlClient $client, ?array $existingOpportunity = null): array
    {
        $stageId = $client->getStageId(self::getStageKey($reservation->status));
        $ghlStatus = self::getGhlStatus($reservation->status);

        $newCustomFields = self::buildCustomFields($reservation);

        // Merge with existing custom fields, preserving specific fields like "sede_origen"
        if ($existingOpportunity) {
            $newCustomFields = self::mergeCustomFields($existingOpportunity['customFields'] ?? [], $newCustomFields);
        }

        $data = [
            'name' => self::buildOpportunityName($reservation),
            'status' => $ghlStatus,
            'monetaryValue' => (float) $reservation->total_price,
            'customFields' => $newCustomFields,
        ];

        // Only include stageId if it's a valid stage (statuses going to 'lost' may not have a stage)
        if ($stageId) {
            $data['pipelineStageId'] = $stageId;
        }

        return $data;
    }

    /**
     * Merge custom fields, preserving specific fields from existing data.
     *
     * @param array $existingFields Existing custom fields from GHL
     * @param array $newFields New custom fields from reservation
     * @return array Merged custom fields
     */
    protected static function mergeCustomFields(array $existingFields, array $newFields): array
    {
        // Extract preserved fields from existing data
        $preservedValues = [];
        foreach ($existingFields as $field) {
            $key = $field['key'] ?? $field['id'] ?? null;
            if ($key && in_array($key, self::$preservedCustomFields)) {
                $preservedValues[$key] = $field['value'] ?? $field['field_value'] ?? '';
            }
        }

        // Add preserved fields to new fields (if they have values and aren't already in new fields)
        $newFieldKeys = array_map(fn($f) => $f['key'] ?? '', $newFields);

        foreach ($preservedValues as $key => $value) {
            if (!empty($value) && !in_array($key, $newFieldKeys)) {
                $newFields[] = [
                    'key' => $key,
                    'field_value' => $value,
                ];
            }
        }

        return $newFields;
    }

    /**
     * Build opportunity name from reservation.
     */
    protected static function buildOpportunityName(Reservation $reservation): string
    {
        $fullname = $reservation->fullname ?? '';
        $categoryName = $reservation->categoryObject->name ?? '';

        // Remove "Gama " prefix (e.g., "Gama C" -> "C", "Gama G4" -> "G4")
        $category = preg_replace('/^Gama\s+/i', '', $categoryName);

        return "{$category} - {$fullname}";
    }

    /**
     * Build custom fields array for GHL.
     */
    protected static function buildCustomFields(Reservation $reservation): array
    {
        return [
            // New custom fields matching GHL configuration
            [
                'key' => 'ciudad_de_recogida',
                'field_value' => $reservation->pickupLocation->city->name ?? '',
            ],
            [
                'key' => 'ciudad_de_entrega',
                'field_value' => $reservation->returnLocation->city->name ?? '',
            ],
            [
                'key' => 'fecha_hora_recogida',
                'field_value' => self::formatPickupDateTime($reservation),
            ],
            [
                'key' => 'fecha_hora_entrega',
                'field_value' => self::formatReturnDateTime($reservation),
            ],
            [
                'key' => 'codigo_de_reserva',
                'field_value' => $reservation->reserve_code ?? '',
            ],
            // Legacy fields (keeping for backwards compatibility if they exist in GHL)
            [
                'key' => 'gama',
                'field_value' => $reservation->categoryObject->name ?? '',
            ],
        ];
    }

    /**
     * Format pickup date and time for GHL.
     */
    protected static function formatPickupDateTime(Reservation $reservation): string
    {
        if (!$reservation->pickup_date) {
            return '';
        }

        $date = $reservation->pickup_date->format('d/m/Y');
        $time = $reservation->pickup_hour?->format('H:i') ?? '';

        return $time ? "{$date} {$time}" : $date;
    }

    /**
     * Format return date and time for GHL.
     */
    protected static function formatReturnDateTime(Reservation $reservation): string
    {
        if (!$reservation->return_date) {
            return '';
        }

        $date = $reservation->return_date->format('d/m/Y');
        $time = $reservation->return_hour?->format('H:i') ?? '';

        return $time ? "{$date} {$time}" : $date;
    }

    /**
     * Get stage key from reservation status.
     */
    protected static function getStageKey(?string $status): string
    {
        return self::$statusToStageKey[$status] ?? 'pendiente';
    }

    /**
     * Get GHL native status from reservation status.
     *
     * @return string 'open', 'won', 'lost', or 'abandoned'
     */
    protected static function getGhlStatus(?string $status): string
    {
        return self::$statusToGhlStatus[$status] ?? 'open';
    }

    /**
     * Check if reservation status has a mapped GHL stage.
     */
    public static function hasGhlStage(?string $status): bool
    {
        return isset(self::$statusToStageKey[$status]);
    }

    /**
     * Check if reservation status should mark opportunity as lost.
     */
    public static function isLostStatus(?string $status): bool
    {
        return (self::$statusToGhlStatus[$status] ?? 'open') === 'lost';
    }
}
