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
        $branch = Branch::where('code', 'ACMNN')->first();
        if ($branch) {
            $branch->update([
                'name' => 'Medellín El Poblado',
                'pickup_address' => 'Carrera 48 # 17 - 49, El Poblado -  Agencia LOCALIZA RENT A CAR (LETRERO VERDE CON BLANCO)',
                'pickup_map' => 'https://maps.app.goo.gl/Qp9QxkEGTaPn8GLk7',
                'return_address' => null,
                'return_map' => null,
            ]);
        }
    }

};
