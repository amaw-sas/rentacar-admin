<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('address')->after('name')->nullable();
        });

        $csvData = array(
            "ACARM" => "Cra. 18 #59-37 - C. Cial. San Sur Local 1-06 (Frente Al Estadio Centenario)",
            "AABAN" => "Aeropuerto Ernesto Cortissoz Primer Piso Local 015",
            "ACBAN" => "Vía 40 #76-63",
            "ACBOJ" => "Cll. 170 #64-47 Jumbo",
            "ACCHI" => "Km 2 Vía Chía-Cajicá Costado Occidental C. Cial. Bazaar Chía Local 126 Frente Al C. Cial. Fontanar",
            "AABOT" => "Aeropuerto El Dorado Frente A La Salida De Vuelos Nacionales 1 Piso Puerta 2",
            "ACBED" => "Av. Cll. 26 #96A-21 Saliendo Del Aeropuerto El Dorado",
            "ACBBN" => "Cra. 58 #127-59 Local 240 - C. Cial. Bulevar Niza",
            "ACBOT" => "Cll. 72 #20B-59",
            "ACBEX" => "Cll. 134 #9-51 Éxito Country. Entrada Por La Cra. 10 Puerta 2",
            "ACBOF" => "Av. Las Americas #62-84 - C. Cial. Outlet Factory, Entrada 2 Sótano 1",
            "AABCR" => "Km 25 Via Lebrija - Aeropuerto Palonegro - Salidas Internacionales Al Lado De La Oficina Del Sau",
            "ACBCR" => "Cll. 114 #27-42",
            "AAKAL" => "Aeropuerto Alfonso Bonilla Aragón Llegadas Nacionales A La Izquierda Local #4",
            "ACKAL" => "Cll. 10 #52-50 - Barrio Camino Real Al Lado De La Estación De Servicio Esso Guadalupe",
            "ACKJC" => "Cll. 40 Norte #6A-45 - Supermercado Jumbo Chipichape",
            "AACTG" => "Aeropuerto Rafael Núñez - Cra. 3 #70-122 Llegadas Nacionales A la Izquierda Cruzando La Calle",
            "ACIBG" => "Av. Ambala Con Cll. 69 - C. Cial. Plazas Del Bosque Local 113",
            "ACMNZ" => "Cra. 23 #65-11 - Barrio Laureles Local 311 - C. Cial. Cable Plaza - Nivel 3 Junto A Cinemark",
            "AAMDL" => "Aeropuerto Jose Maria Córdoba Llegadas Internacionales 1 Piso Local 10",
            "ACMJM" => "Glorieta José María Córdova - Vía Guarne",
            "AAMED" => "Cra. 65A #13-157 Local 14",
            "ACMEX" => "Cra. 66B #32D-36 Conocido Como El Cafetero Queda Bajando Las Escaleras En El Parqueadero Del Éxito",
            "ACMDL" => "Cra. 48B #4Sur-15 - Av. Las Vegas Bajos Del Puente De La Cuatro Sur",
            "ACMEP" => "Cra. 43B #9-33 - Barrio Astorga - Parque Poblado",
            "ACMTR" => "Cra. 6 #68-72 - Bloque B Sótano C. Cial. Buenavista",
            "AANVA" => "Aeropuerto Benito Salas Local 12",
            "ACPEI" => "Av. 30 Agosto #27-31",
            "AAPEI" => "Aeropuerto Matecaña Entrada 2",
            "ACPMC" => "Km 4 Vía Cerritos, Parqueadero Sociedad De Mejoras - Antiguo Zoológico Matecaña",
            "ACSMR" => "Cll. 24 #4-15 Esquina",
            "AASMR" => "Aeropuerto Simón Bolivar - Frente A La Salida De Pasajeros",
            "ACTUN" => "Cll. 52 #5-125 Piso 2 (Peugeot)",
            "ACVLL" => "Cll. 15 #38-40 - C. Cial. Llano Centro Sótano 1 Local 5",
            "AAVAL" => "Aeropuerto Alfonso López Local 111",
            "ACPOE" => "Cra. 9 #6N-03 Éxito",
            "AACUC" => "Aeropuerto Camilo Daza - Area Comercial Local L1-039",
            "AAMTR" => "Aeropuerto Los Garzones A 10Km Del Centro De La Ciudad, Local 1A",
            "ACMAY" => "Sabaneta Via Las Vegas",
            "ACKPA" => "C. Cial. Plaza Madero L02-A2",
            "ACEGD" => "C. Cial. Viva Palmas Km 17 + 750 Vía El Escobero - Las Palmas - Municipio De Envigado"
        );

        foreach($csvData as $code => $address)
            DB::table('branches')->where('code', $code)->update(['address' => $address]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
