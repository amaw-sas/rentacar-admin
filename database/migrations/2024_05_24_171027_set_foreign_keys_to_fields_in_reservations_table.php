<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Branch;
use App\Models\Franchise;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('category')->nullable()->change();
            $table->unsignedBigInteger('franchise')->nullable()->change();
            $table->unsignedBigInteger('pickup_location')->nullable()->change();
            $table->unsignedBigInteger('return_location')->nullable()->change();

            $table->foreign('category')
                ->references('id')
                ->on('categories')
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('franchise')
                ->references('id')
                ->on('franchises')
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('pickup_location')
                ->references('id')
                ->on('branches')
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('return_location')
                ->references('id')
                ->on('branches')
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignIdFor(Category::class,'category')->nullable()->change();
            $table->foreignIdFor(Franchise::class,'franchise')->nullable()->change();
            $table->foreignIdFor(Branch::class,'pickup_location')->nullable()->change();
            $table->foreignIdFor(Branch::class,'return_location')->nullable()->change();

            $table->dropForeign('reservations_category_foreign');
            $table->dropForeign('reservations_franchise_foreign');
            $table->dropForeign('reservations_pickup_location_foreign');
            $table->dropForeign('reservations_return_location_foreign');
        });
    }
};
