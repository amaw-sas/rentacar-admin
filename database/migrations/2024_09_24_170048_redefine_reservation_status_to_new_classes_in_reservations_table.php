<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Enums\ReservationStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip ENUM modification on SQLite (used for testing)
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        $finalEnumValues = [
            ReservationStatus::Nueva->value,
            ReservationStatus::Pendiente->value,
            ReservationStatus::Reservado->value,
            ReservationStatus::SinDisponibilidad->value,
            ReservationStatus::Utilizado->value,
            ReservationStatus::NoContactado->value,
            ReservationStatus::Baneado->value,
            ReservationStatus::NoRecogido->value,
            ReservationStatus::PendientePago->value,
            ReservationStatus::PendienteModificar->value,
            ReservationStatus::Cancelado->value,
            ReservationStatus::Indeterminado->value,
        ];

        $enumString = implode("','", $finalEnumValues);
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
