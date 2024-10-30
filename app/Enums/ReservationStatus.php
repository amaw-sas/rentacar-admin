<?php

namespace App\Enums;

/**
 * Cambiar Confirmado por
 */
enum ReservationStatus: string {
    case Nueva = "Nueva";
    case Pendiente = "Pendiente";
    case Reservado = "Reservado";
    case SinDisponibilidad = "Sin disponibilidad";
    case Utilizado = "Utilizado";
    case NoContactado = "No Contactado";
    case Baneado = "Baneado";
    case NoRecogido = "No recogido";
    case PendientePago = "Pendiente Pago";
    case PendienteModificar = "Pendiente Modificar";
    case Cancelado = "Cancelado";
    case Indeterminado = "Indeterminado";
    case Mensualidad = "Mensualidad";
}

