<?php
use App\Enums\ReservationStatus;
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
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status', [
                ReservationStatus::Confirmado->value,
                ReservationStatus::SinConfirmar->value,
                ReservationStatus::SinDisponibilidad->value,
                ReservationStatus::ConCodigo->value,
                ReservationStatus::ConCodigoEnRevision->value,
                ReservationStatus::Nueva->value,
                ReservationStatus::NoRecogido->value,
                ReservationStatus::ConfirmadoPendientePago->value,
            ])
            ->after('reserve_code')
            ->default(ReservationStatus::Nueva->value)
            ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status', [
                ReservationStatus::Confirmado->value,
                ReservationStatus::SinConfirmar->value,
                ReservationStatus::SinDisponibilidad->value,
                ReservationStatus::ConCodigo->value,
                ReservationStatus::Nueva->value,
                ReservationStatus::NoRecogido->value,
                ReservationStatus::ConfirmadoPendientePago->value,
            ])
            ->after('reserve_code')
            ->default(ReservationStatus::Nueva->value)
            ->change();
        });
    }
};
