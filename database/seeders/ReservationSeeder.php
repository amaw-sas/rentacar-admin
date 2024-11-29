<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Franchise;
use App\Models\Reservation;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** branches */
        $AABOT = Branch::where('code','AABOT')->first();
        $AAMED = Branch::where('code','AAMDL')->first();
        $branches = [$AABOT, $AAMED];

        /** categories */
        $C = Category::where('identification','C')->first();
        $F = Category::where('identification','F')->first();
        $categories = [$C, $F];

        /** franchises */
        $alquilatucarro = Franchise::where('name','alquilatucarro')->first();
        $alquilame = Franchise::where('name','alquilame')->first();
        $alquicarros = Franchise::where('name','alquicarros')->first();
        $franchises = [$alquilatucarro, $alquilame, $alquicarros];

        foreach($branches as $branch){
            foreach($categories as $category){
                foreach($franchises as $franchise){
                    Reservation::factory()->count(5)->create([
                        'franchise' => $franchise->id,
                        'category'  => $category->id,
                        'pickup_location'   => $branch->id,
                        'return_location'   => $branch->id,
                    ]);
                }
            }
        }
    }
}
