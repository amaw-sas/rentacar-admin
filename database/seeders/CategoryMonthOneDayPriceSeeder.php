<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryMonthOneDayPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'C' => 220_000,
            'F' => 250_000,
            'FX' => 300_000,
            'FL' => 290_000,
            'FU' => 340_000,
            'GC' => 550_000,
            'G4' => 550_000,
            'VP' => 550_000,
            'GL' => 595_000,
            'LE' => 570_000,
        ];

        foreach($data as $category => $one_day_price){
            $monthPrice = (Category::where('identification',$category)->first())->monthPrices()->first();
            if($monthPrice){
                $monthPrice->one_day_price = $one_day_price;
                $monthPrice->save();
            }
        }
    }
}
