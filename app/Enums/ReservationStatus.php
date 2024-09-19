<?php

namespace App\Enums;

enum ReservationStatus: string {
    case Confirmado = "Confirmado";
    case SinConfirmar = "Sin confirmar";
    case SinDisponibilidad = "Sin disponibilidad";
    case ConCodigo = "Con código";
    case ConCodigoEnRevision = "Con código En revisión";
    case Nueva = "Nueva";
    case NoRecogido = "No recogido";
    case ConfirmadoPendientePago = "Confirmado Pendiente Pago";
}

