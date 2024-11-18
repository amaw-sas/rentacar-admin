<?php

use App\Enums\ReservationStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existingEnumValues = [
            'Confirmado',
            'Sin confirmar',
            'Sin disponibilidad',
            'Con código',
            'Con código En revisión',
            'Nueva',
            'No recogido',
            'Confirmado Pendiente Pago'
        ];

        $newEnumValues = [
            ReservationStatus::Pendiente->value,
            ReservationStatus::Reservado->value,
            ReservationStatus::SinDisponibilidad->value,
            ReservationStatus::Utilizado->value,
            ReservationStatus::NoContactado->value,
            ReservationStatus::Baneado->value,
            ReservationStatus::PendientePago->value,
            ReservationStatus::PendienteModificar->value,
            ReservationStatus::Cancelado->value,
            ReservationStatus::Indeterminado->value,
        ];

        $allValues = array_unique(array_merge($existingEnumValues, $newEnumValues));

        $enumString = implode("','", $allValues);
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('{$enumString}') NOT NULL DEFAULT '" . ReservationStatus::Pendiente->value . "'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
