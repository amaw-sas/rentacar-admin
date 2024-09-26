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
        $updates = [
            'Confirmado' => ReservationStatus::Utilizado->value,
            'Sin confirmar' => ReservationStatus::NoContactado->value,
            'Con código' => ReservationStatus::Reservado->value, // Ajusta según tu lógica
            'Con código En revisión' => ReservationStatus::Pendiente->value, // Ajusta según tu lógica
            'Confirmado Pendiente Pago' => ReservationStatus::PendientePago->value,
        ];

        foreach ($updates as $old => $new) {
            DB::table('reservations')->where('status', $old)->update(['status' => $new]);
        }
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
