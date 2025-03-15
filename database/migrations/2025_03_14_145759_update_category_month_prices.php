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
                "1k_kms" => 3320000,
                "2k_kms" => 3710000,
                "3k_kms" => 3710000
            ],
            "F" => [
                "1k_kms" => 3950000,
                "2k_kms" => 4340000,
                "3k_kms" => 4340000
            ],
            "FX" => [
                "1k_kms" => 4080000,
                "2k_kms" => 4470000,
                "3k_kms" => 4470000
            ],
            "G" => [
                "2k_kms" => 6584990,
                "3k_kms" => 6584990
            ],
            "G4" => [
                "1k_kms" => 5710000,
                "2k_kms" => 6280000,
                "3k_kms" => 6280000
            ],
            "GC" => [
                "1k_kms" => 5250000,
                "2k_kms" => 5820000,
                "3k_kms" => 5820000
            ],
            "GR" => [
                "1k_kms" => 9410000,
                "2k_kms" => 10600000,
                "3k_kms" => 10600000
            ],
            "GX" => [
                "2k_kms" => 7961990,
                "3k_kms" => 7961990
            ],
            "H" => [
                "2k_kms" => 5428990,
                "3k_kms" => 5428990
            ],
            "L" => [
                "2k_kms" => 6988990,
                "3k_kms" => 6988990
            ],
            "LE" => [
                "1k_kms" => 6170000,
                "2k_kms" => 7360000,
                "3k_kms" => 7360000
            ],
            "LP" => [
                "2k_kms" => 8288990,
                "3k_kms" => 8288990
            ],
            "VP" => [
                "1k_kms" => 5710000,
                "2k_kms" => 6100000,
                "3k_kms" => 6100000
            ],
            "FL" => [
                "1k_kms" => 4960000,
                "2k_kms" => 5350000,
                "3k_kms" => 5350000
            ],
            "FU" => [
                "1k_kms" => 5240000,
                "2k_kms" => 5630000,
                "3k_kms" => 5630000
            ],
            "GL" => [
                "1k_kms" => 6030000,
                "2k_kms" => 6600000,
                "3k_kms" => 6600000
            ]
        ];

        foreach($tarifas as $category_identification => $tarifa){
            $category = Category::where('identification',$category_identification)->first();
            if($category){
                DB::table('category_month_prices')->where('category_id', $category->id)->update([
                    '1k_kms' => $tarifa['2k_kms'],
                    '2k_kms' => $tarifa['2k_kms'],
                    '3k_kms' => $tarifa['3k_kms'],
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
