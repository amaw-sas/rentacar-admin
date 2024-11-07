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
            "C" => ["2k_kms" => 3865990, "3k_kms" => 4223990],
            "F" => ["2k_kms" => 4686990, "3k_kms" => 5321990],
            "FX" => ["2k_kms" => 5306990, "3k_kms" => 5934990],
            "G" => ["2k_kms" => 6584990, "3k_kms" => 7364990],
            "G4" => ["2k_kms" => 7144990, "3k_kms" => 8141990],
            "GC" => ["2k_kms" => 6479990, "3k_kms" => 7383990],
            "GR" => ["2k_kms" => 10710990, "3k_kms" => 12204990],
            "GX" => ["2k_kms" => 7961990, "3k_kms" => 8756990],
            "H" => ["2k_kms" => 5428990, "3k_kms" => 5970990],
            "L" => ["2k_kms" => 6988990, "3k_kms" => 7815990],
            "LE" => ["2k_kms" => 8726990, "3k_kms" => 9943990],
            "LP" => ["2k_kms" => 8288990, "3k_kms" => 9116990],
            "VP" => ["2k_kms" => 7716990, "3k_kms" => 8792990],
            "FL" => ["2k_kms" => 5887990, "3k_kms" => 6584990],
            "FU" => ["2k_kms" => 6081990, "3k_kms" => 6801990],
            "GL" => ["2k_kms" => 6061990, "3k_kms" => 6801990]
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
