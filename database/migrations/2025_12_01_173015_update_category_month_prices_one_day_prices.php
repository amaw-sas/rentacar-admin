<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $data = [
            'C' => 125_200,
            'F' => 148_900,
            'FX' => 153_800,
            'GC' => 197_900,
            'G4' => 214_266,
            'LE' => 232_600,
        ];

        foreach($data as $category => $one_day_price){
            $monthPrice = (Category::where('identification',$category)->first())->monthPrices()->first();
            if($monthPrice){
                $monthPrice->one_day_price = $one_day_price;
                $monthPrice->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_month_prices', function (Blueprint $table) {
            //
        });
    }
};
