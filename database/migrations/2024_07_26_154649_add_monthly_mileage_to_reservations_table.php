<?php

use App\Enums\MonthlyMileage;
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
            $table->enum('monthly_mileage', [
                MonthlyMileage::oneKKms->value,
                MonthlyMileage::twoKKms->value,
                MonthlyMileage::threeKKms->value,
            ])
            ->nullable()
            ->after('status')
            ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('monthly_mileage');
        });
    }
};
