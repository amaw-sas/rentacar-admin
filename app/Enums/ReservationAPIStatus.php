<?php

namespace App\Enums;

/**
 * Estados de la reserva segun el protocolo OTA 2023
 *
 * Confirmed - La reserva está confirmada y el vehículo está reservado para la fecha y hora especificadas.
 * Pending - La reserva está en proceso, aún no confirmada. Podría ser que se está esperando una confirmación del proveedor o la disponibilidad final.
 * Cancelled - La reserva ha sido cancelada.
 * On Request - La reserva ha sido solicitada pero no se ha confirmado todavía; se requiere una acción adicional, como la aprobación del proveedor.
 * Waitlist - El cliente está en lista de espera porque no había disponibilidad para la solicitud inicial.
 * No Show - El cliente no se presentó a recoger el vehículo en el tiempo estipulado, y por tanto, la reserva puede ser cancelada o sujeta a cargos.
 * Completed - La reserva ha sido completada, lo que significa que el vehículo fue alquilado y devuelto según lo acordado.
 * Modified - La reserva ha sido modificada desde su creación original. Esto podría incluir cambios en el tiempo de recogida, devolución, tipo de vehículo, etc.
 * Failed - La reserva no pudo ser procesada por alguna razón, como problemas con el pago o falta de disponibilidad.
 * Expired - La solicitud de reserva ha expirado debido a que no se confirmó dentro del tiempo límite establecido.
 */

enum ReservationAPIStatus: string {
    case Confirmed = "Confirmed";
    case Pending = "Pending";
    case Cancelled = "Cancelled";
    case OnRequest = "On Request";
    case Waitlist = "Waitlist";
    case NoShow = "No Show";
    case Completed = "Completed";
    case Failed = "Failed";
    case Expired = "Expired";
}

