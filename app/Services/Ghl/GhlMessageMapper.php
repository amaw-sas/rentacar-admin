<?php

namespace App\Services\Ghl;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Maps Reservation data to WhatsApp message content for GHL.
 *
 * Unlike WATI (template-based), GHL uses plain text messages.
 * This mapper formats reservation data into readable messages.
 */
class GhlMessageMapper
{
    /**
     * Get the primary notification message for a reservation status.
     */
    public static function getMessage(Reservation $reservation): ?string
    {
        // Handle both string and enum status values
        $status = $reservation->status instanceof ReservationStatus
            ? $reservation->status
            : ReservationStatus::tryFrom($reservation->status);

        return match ($status) {
            ReservationStatus::Reservado => self::getReservadoMessage($reservation),
            ReservationStatus::Pendiente => self::getPendienteMessage($reservation),
            ReservationStatus::SinDisponibilidad => self::getSinDisponibilidadMessage($reservation),
            ReservationStatus::Mensualidad => self::getMensualidadMessage($reservation),
            default => null,
        };
    }

    /**
     * Get additional instruction messages for Reservado status.
     *
     * Returns array of messages to send after the main reservation message.
     */
    public static function getAdditionalMessages(Reservation $reservation): array
    {
        // Handle both string and enum status values
        $status = $reservation->status instanceof ReservationStatus
            ? $reservation->status
            : ReservationStatus::tryFrom($reservation->status);

        if ($status !== ReservationStatus::Reservado) {
            return [];
        }

        return [
            self::getInstruccionesMessage($reservation),
            self::getInstruccionesAdicionalesMessage($reservation),
        ];
    }

    /**
     * Format the Reservado (confirmed) message.
     *
     * Equivalent to WATI template: nueva_reserva_5
     */
    protected static function getReservadoMessage(Reservation $reservation): string
    {
        $franchiseName = $reservation->franchiseObject?->name ?? 'nuestra empresa';
        $fullname = self::cleanupName($reservation->fullname ?? $reservation->email);

        $pickupDate = self::formatDate($reservation->pickup_date);
        $pickupHour = self::formatHour($reservation->pickup_hour);
        $pickupLocation = $reservation->pickupLocation?->name ?? 'Por confirmar';
        $pickupAddress = $reservation->pickupLocation?->pickup_address ?? '';
        $pickupMap = $reservation->pickupLocation?->pickup_map ?? '';

        $returnDate = self::formatDate($reservation->return_date);
        $returnHour = self::formatHour($reservation->return_hour);
        $returnLocation = $reservation->returnLocation?->name ?? 'Por confirmar';
        $returnAddress = $reservation->returnLocation?->return_address ?? $reservation->returnLocation?->pickup_address ?? '';
        $returnMap = $reservation->returnLocation?->return_map ?? $reservation->returnLocation?->pickup_map ?? '';

        $message = "🚗 *¡Reserva Confirmada!*\n\n";
        $message .= "Hola {$fullname},\n\n";
        $message .= "Tu reserva con *{$franchiseName}* ha sido confirmada.\n\n";
        $message .= "📋 *Código de reserva:* {$reservation->reserve_code}\n\n";

        $message .= "📍 *Recogida:*\n";
        $message .= "• Fecha: {$pickupDate}\n";
        $message .= "• Hora: {$pickupHour}\n";
        $message .= "• Lugar: {$pickupLocation}\n";
        if ($pickupAddress) {
            $message .= "• Dirección: {$pickupAddress}\n";
        }
        if ($pickupMap) {
            $message .= "• Mapa: {$pickupMap}\n";
        }

        $message .= "\n📍 *Devolución:*\n";
        $message .= "• Fecha: {$returnDate}\n";
        $message .= "• Hora: {$returnHour}\n";
        $message .= "• Lugar: {$returnLocation}\n";
        if ($returnAddress) {
            $message .= "• Dirección: {$returnAddress}\n";
        }
        if ($returnMap) {
            $message .= "• Mapa: {$returnMap}\n";
        }

        $message .= "\n¡Gracias por confiar en nosotros! 🙌";

        return $message;
    }

    /**
     * Format the Pendiente (pending) message.
     *
     * Equivalent to WATI template: reserva_pendiente
     */
    protected static function getPendienteMessage(Reservation $reservation): string
    {
        $franchiseName = $reservation->franchiseObject?->name ?? 'nuestra empresa';
        $fullname = self::cleanupName($reservation->fullname ?? $reservation->email);

        $message = "⏳ *Reserva Pendiente*\n\n";
        $message .= "Hola {$fullname},\n\n";
        $message .= "Hemos recibido tu solicitud de reserva con *{$franchiseName}*.\n\n";
        $message .= "Estamos revisando la disponibilidad y te confirmaremos pronto.\n\n";
        $message .= "¡Gracias por tu paciencia! 🙏";

        return $message;
    }

    /**
     * Format the SinDisponibilidad (no availability) message.
     *
     * Equivalent to WATI template: reserva_sin_disponibilidad
     */
    protected static function getSinDisponibilidadMessage(Reservation $reservation): string
    {
        $fullname = self::cleanupName($reservation->fullname ?? $reservation->email);
        $website = $reservation->franchiseObject?->reserva_button ?? 'nuestro sitio web';

        $message = "😔 *Lo sentimos*\n\n";
        $message .= "Hola {$fullname},\n\n";
        $message .= "Lamentablemente no tenemos disponibilidad para las fechas solicitadas.\n\n";
        $message .= "Te invitamos a buscar otras fechas en:\n";
        $message .= "{$website}\n\n";
        $message .= "¡Esperamos poder atenderte pronto! 🚗";

        return $message;
    }

    /**
     * Format the Mensualidad (monthly rental) message.
     *
     * Equivalent to WATI template: reserva_mensual
     */
    protected static function getMensualidadMessage(Reservation $reservation): string
    {
        $franchiseName = $reservation->franchiseObject?->name ?? 'nuestra empresa';
        $fullname = self::cleanupName($reservation->fullname ?? $reservation->email);

        $message = "📅 *Reserva Mensual Confirmada*\n\n";
        $message .= "Hola {$fullname},\n\n";
        $message .= "Tu reserva mensual con *{$franchiseName}* ha sido procesada.\n\n";
        $message .= "Nos pondremos en contacto contigo para coordinar los detalles.\n\n";
        $message .= "¡Gracias por confiar en nosotros! 🙌";

        return $message;
    }

    /**
     * Get pickup instructions message.
     *
     * Equivalent to WATI template: nueva_reserva_instrucciones_2
     */
    protected static function getInstruccionesMessage(Reservation $reservation): string
    {
        $message = "📝 *Instrucciones para la Recogida*\n\n";
        $message .= "Por favor ten en cuenta:\n\n";
        $message .= "✅ Llega puntual a la hora acordada\n";
        $message .= "✅ Presenta tu documento de identidad\n";
        $message .= "✅ Ten a mano tu licencia de conducción vigente\n";
        $message .= "✅ Tarjeta de crédito para el depósito de garantía\n\n";
        $message .= "Si tienes alguna pregunta, no dudes en escribirnos. 💬";

        return $message;
    }

    /**
     * Get additional instructions message.
     *
     * Equivalent to WATI template: nueva_reserva_instrucciones_adicionales
     */
    protected static function getInstruccionesAdicionalesMessage(Reservation $reservation): string
    {
        $message = "ℹ️ *Información Adicional*\n\n";
        $message .= "• El vehículo se entrega con tanque lleno y debe devolverse igual\n";
        $message .= "• Revisa el estado del vehículo antes de recibirlo\n";
        $message .= "• En caso de accidente, comunícate inmediatamente\n";
        $message .= "• Respeta los límites de velocidad y normas de tránsito\n\n";
        $message .= "¡Disfruta tu viaje! 🛣️";

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

    /**
     * Cleanup phone number (remove spaces and +).
     */
    public static function cleanupPhone(string $phone): string
    {
        return Str::of($phone)->trim()->remove(' ')->remove('+')->toString();
    }
}
