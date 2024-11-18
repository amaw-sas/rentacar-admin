<?php

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
        Schema::table('branches', function (Blueprint $table) {
            $table->string('return_address')->after('pickup_address')->nullable();
            $table->string('pickup_map')->after('return_address')->nullable();
            $table->string('return_map')->after('pickup_map')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('return_address');
            $table->dropColumn('pickup_map');
            $table->dropColumn('return_map');
        });
    }
};
