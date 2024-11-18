<?php

use App\Models\Branch;
use App\Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $bogota = City::where('name','Bogotá')->first();
        $pereira = City::where('name','Pereira')->first();

        if($bogota){
            Branch::create([
                'code'      =>  'ACBED',
                'name'      =>  'Bogotá Fontibón',
                'city_id'   =>  $bogota->id,
            ]);
        }

        if($pereira){
            Branch::create([
                'code'      =>  'ACPMC',
                'name'      =>  'Pereira Antiguo Zoológico Matecaña',
                'city_id'   =>  $pereira->id,
            ]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Branch::where('code','ACBED')->first()->delete();
        Branch::where('code','ACPMC')->first()->delete();
    }
};
