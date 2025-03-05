<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Branch;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // get branch
        $medellinLasVegasPobladoBranch = Branch::where('code','ACMDL')->first();

        if($medellinLasVegasPobladoBranch){
            // remove if existing such branch
            $medellinLasVegasPobladoBranch->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // get branch
        $medellinLasVegasPobladoBranch = Branch::where('code','ACMDL')->first();

        if(is_null($medellinLasVegasPobladoBranch)){
            // create if not existing such branch
            Branch::create([
                'code' => 'ACMDL',
                'name' => 'Medellin Las Vegas El Poblado',
                'pickup_address' => 'Cra. 48B #4Sur-15, Av. Las Vegas Bajo del Puente de la 4 Sur',
                'return_address' => null,
                'pickup_map' => 'https://maps.app.goo.gl/Kf7cNZnaNEVmDbcx5',
                'return_map' => null,
                'city_id' => City::where('name', 'Medellin')->first()->id,
            ]);
        }
    }
};
