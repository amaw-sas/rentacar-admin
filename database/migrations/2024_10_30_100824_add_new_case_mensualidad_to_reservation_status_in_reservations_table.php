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
        $currentReservationStatusCases = ReservationStatus::cases();
        $newReservationStatusCases = array_map(fn($case) => $case->value, $currentReservationStatusCases);

        Schema::table('reservations', function (Blueprint $table) use ($newReservationStatusCases) {
            $table->enum('status', $newReservationStatusCases)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $currentReservationStatusCases = array_filter(ReservationStatus::cases(), fn($case) => $case->value != ReservationStatus::Mensualidad->value);
        $oldReservationStatusCases = array_map(fn($case) => $case->value, $currentReservationStatusCases);

        Schema::table('reservations', function (Blueprint $table) use ($oldReservationStatusCases) {
            $table->enum('status', $oldReservationStatusCases)->change();
        });
    }
};
