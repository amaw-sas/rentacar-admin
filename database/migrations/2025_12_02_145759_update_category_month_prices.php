<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tarifas = [
            "C" => [
                "1k_kms" => 3_756_000,
                "2k_kms" => 4_196_000,
                "3k_kms" => 4_196_000
            ],
            "F" => [
                "1k_kms" => 4_467_000,
                "2k_kms" => 4_909_000,
                "3k_kms" => 4_909_000
            ],
            "FX" => [
                "1k_kms" => 4_614_000,
                "2k_kms" => 5_056_000,
                "3k_kms" => 5_056_000
            ],
            "FL" => [
                "1k_kms" => 5_610_000,
                "2k_kms" => 6_051_000,
                "3k_kms" => 6_051_000
            ],
            "FU" => [
                "1k_kms" => 5_926_000,
                "2k_kms" => 6_367_000,
                "3k_kms" => 6_367_000
            ],
            "GC" => [
                "1k_kms" => 5_938_000,
                "2k_kms" => 6_582_000,
                "3k_kms" => 6_582_000
            ],
            "G4" => [
                "1k_kms" => 6_458_000,
                "2k_kms" => 7_102_000,
                "3k_kms" => 7_102_000
            ],
            "GL" => [
                "1k_kms" => 6_820_000,
                "2k_kms" => 7_464_000,
                "3k_kms" => 7_464_000
            ],
            "LE" => [
                "1k_kms" => 6_978_000,
                "2k_kms" => 8_324_000,
                "3k_kms" => 8_324_000
            ],
            "GR" => [
                "1k_kms" => 10_641_000,
                "2k_kms" => 11_987_000,
                "3k_kms" => 11_987_000
            ],
            "GY" => [
                "1k_kms" => 15_266_000,
                "2k_kms" => 16_612_000,
                "3k_kms" => 16_612_000
            ],
            "VP" => [
                "1k_kms" => 6_458_000,
                "2k_kms" => 6_899_000,
                "3k_kms" => 6_899_000
            ],
        ];

        foreach($tarifas as $category_identification => $tarifa){
            $category = Category::where('identification',$category_identification)->first();
            if($category){
                DB::table('category_month_prices')->where('category_id', $category->id)->update([
                    '1k_kms' => $tarifa['1k_kms'] ?? 0,
                    '2k_kms' => $tarifa['2k_kms'],
                    '3k_kms' => $tarifa['3k_kms'] ?? 0,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
