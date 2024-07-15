<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryMonthPriceTotalInsurancePrice extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'C' => 476_000,
            'F' => 476_000,
            'FX' => 476_000,
            'G4' => 595_000,
            'GC' => 595_000,
            'GR' => 595_000,
            'LE' => 595_000,
            'VP' => 595_000,
        ];

        foreach($data as $category => $total_insurance_price){
            $monthPrice = (Category::where('identification',$category)->first())->monthPrices()->first();
            $monthPrice->total_insurance_price = $total_insurance_price;
            $monthPrice->save();
        }

    }
}
