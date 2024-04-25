<?php

namespace App\Enums;

enum ReservationStatus: string {
    case SinConfirmar = "Sin confirmar";
    case SinDisponibilidad = "Sin disponibilidad";
    case ConCodigo = "Con código";
    case Nueva = "Nueva";
    case NoRecogido = "No recogido";
}

