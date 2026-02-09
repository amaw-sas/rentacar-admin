<?php

namespace App\Services\Ghl;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Maps Reservation data to WhatsApp reminder message content for GHL.
 *
 * Provides formatted messages for pickup reminders and post-pickup feedback.
 */
class GhlReminderMessageMapper
{
    /**
     * Get the pickup reminder message (week or 3 days before).
     *
     * Equivalent to WATI template: recordatorio_recogida
     */
    public static function getPickupReminderMessage(Reservation $reservation): string
    {
        $fullname = self::cleanupName($reservation->fullname ?? $reservation->email);
        $franchiseName = $reservation->franchiseObject?->name ?? 'nuestra empresa';

        $pickupDate = self::formatDate($reservation->pickup_date);
        $pickupHour = self::formatHour($reservation->pickup_hour);
        $pickupLocation = $reservation->pickupLocation?->name ?? 'Por confirmar';

        $message = "🔔 *Recordatorio de Recogida*\n\n";
        $message .= "Hola {$fullname},\n\n";
        $message .= "Te recordamos que tu reserva con *{$franchiseName}* está próxima.\n\n";
        $message .= "📋 *Código de reserva:* {$reservation->reserve_code}\n\n";
        $message .= "📍 *Recogida:*\n";
        $message .= "• Fecha: {$pickupDate}\n";
        $message .= "• Hora: {$pickupHour}\n";
        $message .= "• Lugar: {$pickupLocation}\n\n";
        $message .= "¡Te esperamos! 🚗";

        return $message;
    }

    /**
     * Get the same-day pickup reminder message (with location details).
     *
     * Equivalent to WATI template: recordatorio_recogida_mismo_dia_1
     */
    public static function getSameDayPickupReminderMessage(Reservation $reservation): string
    {
        $fullname = self::cleanupName($reservation->fullname ?? $reservation->email);
        $franchiseName = $reservation->franchiseObject?->name ?? 'nuestra empresa';

        $pickupHour = self::formatHour($reservation->pickup_hour);
        $pickupLocation = $reservation->pickupLocation?->name ?? 'Por confirmar';
        $pickupAddress = $reservation->pickupLocation?->pickup_address ?? '';
        $pickupMap = $reservation->pickupLocation?->pickup_map ?? '';

        $message = "⏰ *¡Tu recogida es HOY!*\n\n";
        $message .= "Hola {$fullname},\n\n";
        $message .= "Te recordamos que hoy recoges tu vehículo con *{$franchiseName}*.\n\n";
        $message .= "📋 *Código de reserva:* {$reservation->reserve_code}\n\n";
        $message .= "📍 *Detalles de recogida:*\n";
        $message .= "• Hora: {$pickupHour}\n";
        $message .= "• Lugar: {$pickupLocation}\n";

        if ($pickupAddress) {
            $message .= "• Dirección: {$pickupAddress}\n";
        }
        if ($pickupMap) {
            $message .= "• Mapa: {$pickupMap}\n";
        }

        $message .= "\n✅ Recuerda traer:\n";
        $message .= "• Documento de identidad\n";
        $message .= "• Licencia de conducir vigente\n";
        $message .= "• Tarjeta de crédito para garantía\n\n";
        $message .= "¡Te esperamos! 🚗";

        return $message;
    }

    /**
     * Get the post-pickup feedback message.
     *
     * Equivalent to WATI template: post_reserva
     */
    public static function getPostPickupMessage(Reservation $reservation): string
    {
        $fullname = self::cleanupName($reservation->fullname ?? $reservation->email);
        $franchiseName = $reservation->franchiseObject?->name ?? 'nuestra empresa';

        $message = "🚗 *¿Cómo va tu experiencia?*\n\n";
        $message .= "Hola {$fullname},\n\n";
        $message .= "Esperamos que estés disfrutando tu vehículo con *{$franchiseName}*.\n\n";
        $message .= "Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos.\n\n";
        $message .= "¡Buen viaje! 🛣️";

        return $message;
    }

    /**
     * Format date for display.
     */
    protected static function formatDate(?string $date): string
    {
        if (!$date) {
            return 'Por confirmar';
        }

        try {
            return Carbon::parse($date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
        } catch (\Exception) {
            return $date;
        }
    }

    /**
     * Format hour for display.
     */
    protected static function formatHour(?string $hour): string
    {
        if (!$hour) {
            return 'Por confirmar';
        }

        try {
            return Carbon::parse($hour)->format('h:i A');
        } catch (\Exception) {
            return $hour;
        }
    }

    /**
     * Cleanup name for display.
     */
    protected static function cleanupName(string $name): string
    {
        return Str::of($name)->trim()->replaceMatches('/\s+/', ' ')->toString();
    }
}
