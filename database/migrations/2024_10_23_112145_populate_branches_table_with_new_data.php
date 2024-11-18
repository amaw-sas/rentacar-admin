<?php

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
        $csvData = <<<EOT
        "SIGLA","AGENCIA","DIRECCION RECOGIDA","MAPA RECOGIDA","DIRECCION DEVOLUCION","MAPA DEVOLUCION"
        "AARME","Armenia Aeropuerto"," Aeropuerto el Edén – Local # 18, Km 14 Vía a la Tebaida","https://maps.app.goo.gl/yxKpFsswp4DKd6BL7",,
        "AABAN","Barranquilla Aeropuerto","Aeropuerto Ernesto Cortissoz Piso 1 Local 015","https://maps.app.goo.gl/CJUF2P141zYKoomMA","Calle 30, #1-368, a 200 metros del Aeropuerto Ernesto Cortissoz","https://maps.app.goo.gl/Bh9eKGNzzswc7BfQ9"
        "ACBAN","Barranquilla Vía 40"," Vía 40, #76-63, al lado de Kia","https://maps.app.goo.gl/QGCKdzPTqYpmKZBw8",,
        "ACBSD","Soledad ","Cll. 30, #1-368, a 200 metros del Aeropuerto Ernesto Cortissoz","https://maps.app.goo.gl/Bh9eKGNzzswc7BfQ9",,
        "AABOT","Bogota Aeropuerto","Aeropuerto El Dorado, Piso 1 Puerta 7, Punto de atención para traslado hasta la rentadora de 6:00 am a 10:00 pm, en otro horario llamar al 350-280-6370","https://maps.app.goo.gl/U3Sct9jNM8BrLFR78","Diagonal  24C, 99-45 - a 5 minutos del Aeropuerto","https://maps.app.goo.gl/JjpsSCHkCrgGYa9P7"
        "ACBED","Bogota Fontibon"," Diagonal 24C #99-45, a 5 minutos del Aeropuerto","https://maps.app.goo.gl/JjpsSCHkCrgGYa9P7",,
        "ACBOJ","Bogota Almacen Jumbo calle 170","Cll. 170 #64-47 ","https://maps.app.goo.gl/Lgr33gpxFDgM2VS5A",,
        "ACBEX","Bogota Almacen Éxito Country","Cll. 134 #9-51, Parqueadero Nivel 2, Entrada por la Cra 10 ","https://maps.app.goo.gl/j4e6U88jwdxZ7Fos7",,
        "ACBNN","Bogota C.Cial Nuestro Bogotá","Av. Cra 86 # 55A -75 Sótano 1, Engativá","https://maps.app.goo.gl/8zvNGCa9wdjMzb9g9",,
        "AABCR","Bucaramanga Aeropuerto ","Aeropuerto Palonegro,  Km 25 Vía Lebrija, frente a las Salidas Internacionales","https://maps.app.goo.gl/86UCaYUUFro5tMBu9",,
        "ACBCR","Floridablanca ","Cll. 114 # 27-42,  Puente de Provenza  ","https://maps.app.goo.gl/QYQje7sN8Y12tt8t6",,
        "ACKJC","Cali Norte Chipichape","Supermercado Jumbo Cll. 40 Norte # 6A-45","https://maps.app.goo.gl/8gWfkeRRLoD4cbng6",,
        "ACKAL","Cali Sur Camino Real "," Cll. 10 # 52-50  al lado de la estación Primax  ","https://maps.app.goo.gl/zR5XaSGHcmNp8JdE9",,
        "AAKAL","Cali Aeropuerto","Aeropuerto Alfonso Bonilla Aragón – Local 4,  al lado de llegadas nacionales","https://maps.app.goo.gl/dSiGwEPhLiL6bq7z8","C.Cial. Plaza Madero Local 02-A2 - A 5 minutos del Aeropuerto Estación de Servicio ESSO ","https://maps.app.goo.gl/QCogJToARfMqpXhA6"
        "ACKPA","Palmira C.Cial Plaza Madero","C.Cial. Plaza Madero Local 02-A2 - A 5 minutos del Aeropuerto Estación de Servicio ESSO","https://maps.app.goo.gl/QCogJToARfMqpXhA6",,
        "AACTG","Cartagena Aeropuerto","Cra. 3 #70-122 Barrio Crespo, Diagonal al Aeropuerto Rafael Núñez - Entrando por Kokoriko, a mitad de cuadra ","https://maps.app.goo.gl/xbFboT6RDE7kpZsG8",,
        "AACUC","Cúcuta Aeropuerto","Aeropuerto Camilo Daza Local L1 - 039 Al lado de la entrada 1 ","https://maps.app.goo.gl/QiozK3kECcm7K3wy8",,
        "ACIBG","Ibaque C.Cial Plazas del Bosque ","Av. Ambalá con Cll. 69, Local 113 ","https://maps.app.goo.gl/coEMasjzVjkmsY3W9",,
        "ACMNZ","Manizales C.Cial Mall Plaza ","Cra 14, #55 D-251, Entrada 1, Av. Kevin Ángel ","https://maps.app.goo.gl/oMftWbumYhK6HjCe8",,
        "ACMDL","Medellin Las Vegas El Poblado ","Cra. 48B #4Sur-15, Av. Las Vegas Bajo del Puente de la 4 Sur","https://maps.app.goo.gl/GNZkrRvXvJb7c85w6",,
        "ACMCL","Medellin Éxito Colombia ","Cll. 49 B #66-01 Piso 3 Parqueadero  ","https://maps.app.goo.gl/Kf7cNZnaNEVmDbcx5",,
        "ACMNN","Medellin Ciudad del rio el poblado","Cra 49, #17-36, cerca la estación de industriales ","https://maps.app.goo.gl/aQ2r34dZ42sscCiC7",,
        "AAMDL","Medellin Aeropuerto","Aeropuerto Jose Maria Córdova Llegadas Internacionales Local 1028, Punto de atención para traslado hasta la rentadora","https://maps.app.goo.gl/oDW78mD25Xt6b7jf6","Glorieta José María Córdova - Vía Guarne al lado de asados exquisitos ","https://maps.app.goo.gl/9EhLZ8dYprerfM6R6"
        "ACMJM","Rionegro","Glorieta José María Córdova - Vía Guarne al lado de asados exquisitos ","https://maps.app.goo.gl/9EhLZ8dYprerfM6R6",,
        "AAMTR","Montería Aeropuerto","Aeropuerto Los Garzones Local 111 al lado de la salida de pasajeros ","https://maps.app.goo.gl/7j6W95iaCmd28cDd9",,
        "ACMTR","Monteria C.Cial Buenavista ","Cra 6 #68- 72 Sótano B  ","https://maps.app.goo.gl/NY75KeZ4SrRCrmTY8",,
        "AANVA","Neiva Aeropuerto","Aeropuerto Benito Salas Local 12","https://maps.app.goo.gl/JXwBRfXwrDDNREKQ8",,
        "AAPEI","Pereira Aeropuerto","Aeropuerto Matecaña, Hall Público Piso 1 Local 50","https://maps.app.goo.gl/Rqtxu5UzfuoojMA96","Km 4 Vía Cerritos, Parqueadero Sociedad De Mejoras  ","https://maps.app.goo.gl/xQnAN6rV3RTToxn78"
        "ACPMC","Pereira Antiguo Zoológico Matecaña","Km 4 Vía Cerritos, Parqueadero Sociedad De Mejoras  ","https://maps.app.goo.gl/xQnAN6rV3RTToxn78",,
        "AASMR","Santa Marta Aeropuerto","Aeropuerto Simón Bolívar Local 11, 012A Salidas Nacionales","https://maps.app.goo.gl/Rq1uHdpYyzQfRJbo6",,
        "ACSMR","Santa Marta Centro","Cll. 24 # 03-04","https://maps.app.goo.gl/H1jYo4xCmTMbG9Vn6",,
        "AAVAL","Valledupar Aeropuerto","Aeropuerto Alfonso López local 111","https://maps.app.goo.gl/B92NBR7nLuEe7yN56",,
        "ACVLL","Villavicencio C.Cial Llano Centro ","Cll. 15 #38-40 Sotano 1 Local 5 ","https://maps.app.goo.gl/RS36zckMhDUn79BL8",,
        EOT;

        $lines = explode("\n", $csvData);
        $header = str_getcsv(array_shift($lines)); // Extrae y elimina la primera fila (encabezados)

        foreach ($lines as $line) {
            $row = array_combine($header, str_getcsv($line));
            $branchCode = $row['SIGLA'];
            $branchData = [
                "name" => $row['AGENCIA'],
                "pickup_address" => Str::of($row['DIRECCION RECOGIDA'])->trim()->isNotEmpty() ? $row['DIRECCION RECOGIDA'] : null,
                "return_address" => Str::of($row['DIRECCION DEVOLUCION'])->trim()->isNotEmpty() ? $row['DIRECCION DEVOLUCION'] : null,
                "pickup_map" => Str::of($row['MAPA RECOGIDA'])->trim()->isNotEmpty() ? $row['MAPA RECOGIDA'] : null,
                "return_map" => Str::of($row['MAPA DEVOLUCION'])->trim()->isNotEmpty() ? $row['MAPA DEVOLUCION'] : null,
            ];

            DB::table('branches')->where('code', $branchCode)->update($branchData);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
