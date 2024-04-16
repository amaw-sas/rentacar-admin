<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Branch;
use App\Models\CategoryModel;
use App\Models\CategoryMonthPrice;
use App\Models\City;
use App\Models\CityCategoryVisibility;
use App\Models\CityFranchiseWhatsappLink;
use App\Models\CityPage;
use App\Models\Franchise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentacarDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create franchises
        if(true){
            $alquilatucarro = Franchise::create([
                'name'      			=>  'alquilatucarro',
                'reserva_button'        =>  'https://www.reservatucarro.com',
                'masinfo_button'        =>  'https://bit.ly/2i828Zf',
                'masprecios_button'     =>  'https://www.alquilatucarro.com/precios.html',
                'url_mail_system'       =>  'http://correosalquilatucarro.com/',
                'ad'                    =>  [
                    'activo'    =>  true,
                    'imagen'    =>  "https://storage.googleapis.com/alquilatucarrocom/landing2020/images/animacion/ani1a.png",
                    'url'       =>  "https://alquilerdecarrosbogota.xyz/"
                ],
                'carousel'              =>  [
                    (Object) [
                        'imagen'            =>  "https://storage.googleapis.com/alquilatucarrocom/landing2020/images/animacion/ani1.png",
                        "imagen_movil"      =>  "https://storage.googleapis.com/alquilatucarrocom/landing2020/images/animacion/ani1a.png",
                        "imagen_url"        =>  "https://alquilerdecarrosbogota.xyz/",
                        "alt"               =>  "animacion_principal_alquiler",
                        "activo"            =>  true
                    ]
                ]
            ]);

            $alquilame = Franchise::create([
                'name'      			=>  'alquilame',
                'reserva_button'        =>  'https://www.alquilame.co/reservas/',
                'masinfo_button'        =>  'https://bit.ly/2Dv106V',
                'masprecios_button'     =>  'https://www.alquilame.co/precios.html',
                'url_mail_system'       =>  'https://alquilame.com/reservas/',
                'ad'                    =>  [
                    'activo'    =>  false,
                    'imagen'    =>  "https://www.alquilame.co/images/animacion/aviso.png",
                    'url'       =>  "https://www.alquilame.co/reservas/"
                ],
                'carousel'              =>  [
                    (Object) [
                        'imagen'            =>  "https://www.alquilame.co/images/animacion/ani3.png",
                        "imagen_movil"      =>  "https://www.alquilame.co/images/animacion/ani3a.png",
                        "imagen_url"        =>  "http://alquilerdecarrosbogota.club/promocion",
                        "alt"               =>  "descuentos1",
                        "activo"            =>  true
                    ]
                ]
            ]);

            $alquicarros = Franchise::create([
                'name'      			=>  'alquicarros',
                'reserva_button'        =>  'https://alquicarros.com/reservas/',
                'masinfo_button'        =>  'https://bit.ly/3FF5NUO',
                'masprecios_button'     =>  'https://www.alquicarros.com/precios.html',
                'url_mail_system'       =>  'https://www.alquicarros.com/',
                'ad'                    =>  [
                    'activo'    =>  true,
                    'imagen'    =>  "https://storage.googleapis.com/alquicarros/landing2022/images/aviso.webp",
                    'url'       =>  "https://alquicarros.com/"
                ],
                'carousel'              =>  [
                    (Object) [
                        'imagen'            =>  "https://www.alquilame.co/images/animacion/ani3.png",
                        "imagen_movil"      =>  "https://www.alquilame.co/images/animacion/ani3a.png",
                        "imagen_url"        =>  "https://alquilerdecarrosbogota.xyz/",
                        "alt"               =>  "descuentos1",
                        "activo"            =>  false
                    ]
                ]
            ]);
        }

        // create cities
        if(true){
            $armenia = City::create(['name'=>'Armenia']);
            $barranquilla = City::create(['name'=>'Barranquilla']);
            $bogota = City::create(['name'=>'Bogotá']);
            $bucaramanga = City::create(['name'=>'Bucaramanga']);
            $cali = City::create(['name'=>'Cali']);
            $cartagena = City::create(['name'=>'Cartagena']);
            $cucuta = City::create(['name'=>'Cúcuta']);
            $ibague = City::create(['name'=>'Ibagué']);
            $manizales = City::create(['name'=>'Manizales']);
            $medellin = City::create(['name'=>'Medellín']);
            $monteria = City::create(['name'=>'Montería']);
            $neiva = City::create(['name'=>'Neiva']);
            $pereira = City::create(['name'=>'Pereira']);
            $santamarta = City::create(['name'=>'Santa Marta']);
            $valledupar = City::create(['name'=>'Valledupar']);
            $villavicencio = City::create(['name'=>'Villavicencio']);
            $sabaneta = City::create(['name'=>'Sabaneta']);
            $chia = City::create(['name'=>'Chia']);
            $floridablanca = City::create(['name'=>'Floridablanca']);
            $pasto = City::create(['name'=>'Pasto']);
            $palmira = City::create(['name'=>'Palmira']);
            $soledad = City::create(['name'=>'Soledad']);
            $yopal = City::create(['name'=>'Yopal']);
        }

        // create city pages
        if(true){
            CityPage::insert([
                [
                    'city_id'      	=>  $armenia->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosarmenia.com/',
                ],

                [
                    'city_id'      	=>  $armenia->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenarmenia.com/',
                ],

                [
                    'city_id'      	=>  $armenia->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosarmenia.com/',
                ],

                [
                    'city_id'      	=>  $barranquilla->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosbarranquilla.com/',
                ],

                [
                    'city_id'      	=>  $barranquilla->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenbarranquilla.com/',
                ],

                [
                    'city_id'      	=>  $barranquilla->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosenbarranquilla.com/',
                ],

                [
                    'city_id'      	=>  $bogota->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilercarrosenbogota.co/',
                ],

                [
                    'city_id'      	=>  $bogota->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenbogota.info/',
                ],

                [
                    'city_id'      	=>  $bogota->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosenbogota.com/',
                ],

                [
                    'city_id'      	=>  $bogota->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquicarros.com',
                ],

                [
                    'city_id'      	=>  $bucaramanga->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilercarrosbucaramanga.com/',
                ],

                [
                    'city_id'      	=>  $bucaramanga->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenbucaramanga.com/',
                ],

                [
                    'city_id'      	=>  $bucaramanga->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilerdecarrosbucaramanga.com/',
                ],

                [
                    'city_id'      	=>  $cali->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarroscali.net/',
                ],

                [
                    'city_id'      	=>  $cali->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosencali.com/',
                ],

                [
                    'city_id'      	=>  $cali->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarroscali.com/',
                ],

                [
                    'city_id'      	=>  $cartagena->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarroscartagena.com/',
                ],

                [
                    'city_id'      	=>  $cartagena->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosencartagena.com/',
                ],

                [
                    'city_id'      	=>  $cartagena->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarroscartagena.com/',
                ],

                [
                    'city_id'      	=>  $cucuta->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarroscucuta.com/',
                ],

                [
                    'city_id'      	=>  $cucuta->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosencucuta.com/',
                ],

                [
                    'city_id'      	=>  $cucuta->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarroscucuta.com/',
                ],

                [
                    'city_id'      	=>  $ibague->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosibague.com/',
                ],

                [
                    'city_id'      	=>  $ibague->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenibague.com/',
                ],

                [
                    'city_id'      	=>  $ibague->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosibague.com/',
                ],

                [
                    'city_id'      	=>  $manizales->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosmanizales.com/',
                ],

                [
                    'city_id'      	=>  $manizales->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenmanizales.com/',
                ],

                [
                    'city_id'      	=>  $manizales->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosmanizales.com/',
                ],

                [
                    'city_id'      	=>  $medellin->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilercarrosmedellin.co/',
                ],

                [
                    'city_id'      	=>  $medellin->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenmedellin.info/',
                ],

                [
                    'city_id'      	=>  $medellin->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosenmedellin.com/',
                ],

                [
                    'city_id'      	=>  $monteria->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosmonteria.com/',
                ],

                [
                    'city_id'      	=>  $monteria->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenmonteria.com/',
                ],

                [
                    'city_id'      	=>  $monteria->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosmonteria.com/',
                ],

                [
                    'city_id'      	=>  $neiva->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosneiva.com/',
                ],

                [
                    'city_id'      	=>  $neiva->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenneiva.com/',
                ],

                [
                    'city_id'      	=>  $neiva->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosneiva.com/',
                ],

                [
                    'city_id'      	=>  $pereira->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrospereira.com/',
                ],

                [
                    'city_id'      	=>  $pereira->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenpereira.com/',
                ],

                [
                    'city_id'      	=>  $pereira->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrospereira.com/',
                ],



                [
                    'city_id'      	=>  $santamarta->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilercarrossantamarta.com/',
                ],

                [
                    'city_id'      	=>  $santamarta->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosensantamarta.com/',
                ],

                [
                    'city_id'      	=>  $santamarta->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilerdecarrossantamarta.com/',
                ],


                [
                    'city_id'      	=>  $valledupar->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosvalledupar.com/',
                ],

                [
                    'city_id'      	=>  $valledupar->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenvalledupar.com/',
                ],

                [
                    'city_id'      	=>  $valledupar->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosvalledupar.com/',
                ],

                [
                    'city_id'      	=>  $villavicencio->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilercarrosvillavicencio.com/',
                ],

                [
                    'city_id'      	=>  $villavicencio->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenvillavicencio.com/',
                ],

                [
                    'city_id'      	=>  $villavicencio->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilerdecarrosvillavicencio.com/',
                ],

                [
                    'city_id'      	=>  $chia->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarroschia.com/',
                ],

                [
                    'city_id'      	=>  $chia->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenchia.com/',
                ],

                [
                    'city_id'      	=>  $chia->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarroschia.com/',
                ],

                [
                    'city_id'      	=>  $sabaneta->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrossabaneta.com/',
                ],

                [
                    'city_id'      	=>  $sabaneta->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosensabaneta.com/',
                ],

                [
                    'city_id'      	=>  $sabaneta->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrossabaneta.com/',
                ],

                [
                    'city_id'      	=>  $floridablanca->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosfloridablanca.com/',
                ],

                [
                    'city_id'      	=>  $floridablanca->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenfloridablanca.com/',
                ],

                [
                    'city_id'      	=>  $floridablanca->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosfloridablanca.com/',
                ],

                [
                    'city_id'      	=>  $pasto->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrospasto.com/',
                ],

                [
                    'city_id'      	=>  $pasto->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenpasto.com/',
                ],

                [
                    'city_id'      	=>  $pasto->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrospasto.com/',
                ],

                [
                    'city_id'      	=>  $palmira->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrospalmira.com/',
                ],

                [
                    'city_id'      	=>  $palmira->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenpalmira.com/',
                ],

                [
                    'city_id'      	=>  $palmira->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrospalmira.com/',
                ],

                [
                    'city_id'      	=>  $soledad->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrossoledad.com/',
                ],

                [
                    'city_id'      	=>  $soledad->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosensoledad.com/',
                ],

                [
                    'city_id'      	=>  $soledad->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrossoledad.com/',
                ],

                [
                    'city_id'      	=>  $yopal->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilerdecarrosyopal.com/',
                ],

                [
                    'city_id'      	=>  $yopal->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilerdecarrosenyopal.com/',
                ],

                [
                    'city_id'      	=>  $yopal->id,
                    'franchise_id'  =>  $alquicarros->id,
                    'url'        	=>  'https://www.alquilercarrosyopal.com/',
                ],

                [
                    'city_id'      	=>  $bogota->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://www.alquilame.co',
                ],

                [
                    'city_id'      	=>  $bogota->id,
                    'franchise_id'  =>  $alquilatucarro->id,
                    'url'        	=>  'https://www.alquilatucarro.com',
                ],

                [
                    'city_id'      	=>  $bogota->id,
                    'franchise_id'  =>  $alquilame->id,
                    'url'        	=>  'https://storage.googleapis.com',
                ],

            ]);
        }

        // create city franquise whatsapp links
        if(true){
            CityFranchiseWhatsappLink::insert([
                [
                    'city_id'      		=>  $armenia->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3UAPEaC',
                ],

                [
                    'city_id'      		=>  $barranquilla->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KBtJM0',
                ],

                [
                    'city_id'      		=>  $bogota->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3L26U5N',
                ],

                [
                    'city_id'      		=>  $bucaramanga->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3UErIDe',
                ],

                [
                    'city_id'      		=>  $cali->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/41pz2oE',
                ],

                [
                    'city_id'      		=>  $cartagena->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KBvFnK',
                ],

                [
                    'city_id'      		=>  $cucuta->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3GHTusM',
                ],

                [
                    'city_id'      		=>  $ibague->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3A09sLi',
                ],

                [
                    'city_id'      		=>  $manizales->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KBiFyn',
                ],

                [
                    'city_id'      		=>  $medellin->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3mzSiB8',
                ],

                [
                    'city_id'      		=>  $neiva->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KY9k5k',
                ],

                [
                    'city_id'      		=>  $pereira->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KCOqHw',
                ],

                [
                    'city_id'      		=>  $santamarta->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/43AIQ0M',
                ],



                [
                    'city_id'      		=>  $villavicencio->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3UFVqYD',
                ],


                [
                    'city_id'      		=>  $valledupar->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/40pmKMh',
                ],

                [
                    'city_id'      		=>  $yopal->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/41rcG6i',
                ],

                [
                    'city_id'      		=>  $chia->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/41g5NFi',
                ],

                [
                    'city_id'      		=>  $sabaneta->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3L2ja6j',
                ],

                [
                    'city_id'      		=>  $soledad->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3A00jT6',
                ],

                [
                    'city_id'      		=>  $floridablanca->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/3mAdy9S',
                ],

                [
                    'city_id'      		=>  $palmira->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/41p7ctf',
                ],

                [
                    'city_id'      		=>  $pasto->id,
                    'franchise_id'  	=>  $alquilatucarro->id,
                    'whatsapp_link'     =>  'https://bit.ly/41qpBVY',
                ],

                [
                    'city_id'      		=>  $armenia->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3ociYZc',
                ],

                [
                    'city_id'      		=>  $barranquilla->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3ogJMHs',
                ],

                [
                    'city_id'      		=>  $bogota->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3o36Ycb',
                ],

                [
                    'city_id'      		=>  $bucaramanga->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3L0MY32',
                ],

                [
                    'city_id'      		=>  $cali->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3A1AHF8',
                ],

                [
                    'city_id'      		=>  $cartagena->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/40pJ1cR',
                ],

                [
                    'city_id'      		=>  $cucuta->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3GIkZ5u',
                ],

                [
                    'city_id'      		=>  $ibague->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3L1HeGn',
                ],

                [
                    'city_id'      		=>  $manizales->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KD55uC',
                ],

                [
                    'city_id'      		=>  $medellin->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3GImP6o',
                ],

                [
                    'city_id'      		=>  $monteria->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3ML0tVY',
                ],

                [
                    'city_id'      		=>  $neiva->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/41cUldm',
                ],

                [
                    'city_id'      		=>  $pereira->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3mAAkON',
                ],

                [
                    'city_id'      		=>  $santamarta->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3MGsePd',
                ],

                [
                    'city_id'      		=>  $villavicencio->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/41bibWO',
                ],



                [
                    'city_id'      		=>  $valledupar->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KF1Oe1',
                ],

                [
                    'city_id'      		=>  $yopal->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3GKyjqc',
                ],

                [
                    'city_id'      		=>  $chia->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3A0fAmL',
                ],

                [
                    'city_id'      		=>  $sabaneta->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3MG34At',
                ],

                [
                    'city_id'      		=>  $soledad->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3ojHMhC',
                ],

                [
                    'city_id'      		=>  $floridablanca->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3L1ySyw',
                ],

                [
                    'city_id'      		=>  $palmira->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3o52Rwg',
                ],

                [
                    'city_id'      		=>  $pasto->id,
                    'franchise_id'  	=>  $alquilame->id,
                    'whatsapp_link'     =>  'https://bit.ly/3GMc0R0',
                ],

                [
                    'city_id'      		=>  $armenia->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/41xawlF',
                ],



                [
                    'city_id'      		=>  $barranquilla->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/40pSmkV',
                ],

                [
                    'city_id'      		=>  $bogota->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/41b9k7J',
                ],

                [
                    'city_id'      		=>  $bucaramanga->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/41vyspy',
                ],

                [
                    'city_id'      		=>  $cali->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3MNOmr0',
                ],

                [
                    'city_id'      		=>  $cartagena->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3UEAKAn',
                ],

                [
                    'city_id'      		=>  $cucuta->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/40pVsFz',
                ],

                [
                    'city_id'      		=>  $ibague->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/41xesTt',
                ],

                [
                    'city_id'      		=>  $manizales->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/40bTI2v',
                ],

                [
                    'city_id'      		=>  $medellin->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3mAKcYR',
                ],

                [
                    'city_id'      		=>  $monteria->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KBqFzr',
                ],

                [
                    'city_id'      		=>  $neiva->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3MLeWkD',
                ],

                [
                    'city_id'      		=>  $pereira->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3mxBQ4n',
                ],

                [
                    'city_id'      		=>  $santamarta->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/40fGxNL',
                ],



                [
                    'city_id'      		=>  $villavicencio->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3GOI8Dt',
                ],



                [
                    'city_id'      		=>  $valledupar->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3mAnXSV',
                ],

                [
                    'city_id'      		=>  $yopal->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3MGdLD7',
                ],

                [
                    'city_id'      		=>  $chia->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3MK7pCL',
                ],

                [
                    'city_id'      		=>  $sabaneta->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3KYOTFo',
                ],

                [
                    'city_id'      		=>  $soledad->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3odz04S',
                ],

                [
                    'city_id'      		=>  $floridablanca->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3GMnFiK',
                ],

                [
                    'city_id'      		=>  $palmira->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/3zZHJu5',
                ],

                [
                    'city_id'      		=>  $pasto->id,
                    'franchise_id'  	=>  $alquicarros->id,
                    'whatsapp_link'     =>  'https://bit.ly/41l4wMG',
                ],

            ]);
        }

        // create categories
        if(true){
            $C = Category::create([
                'identification'        =>  'C',
                'name'                  =>  'Gama C',
                'category'              =>  'Gama C Económico Mecánico',
                'description'           =>  'Automóvil de Trasmisión Mecánica de 5 pasajeros, 2 equipajes grandes 1 de mano, 5 puertas (HB), Aire AC, Rádio, VÍdrios Eléctricos, Dirección EPS, Frenos ABS, AirBags',
                'image'                 =>  'carcategories/renault-kwid-10-o-similar.webp',
                'ad'    =>  '',
                'order'                 =>  2
            ]);

            $F = Category::create([
                'identification'        =>  'F',
                'name'                  =>  'Gama F',
                'category'              =>  'Gama F Sedán Mecánico',
                'description'           =>  'Automóvil tipo Sedán y HatchBack, 5 pasajeros, 2 equipajes grandes y 2 de mano, Aire AC, Radio, Vídrios Eléctricos, Dirección EPS, Frenos ABS, AirBags',
                'image'                 =>  'carcategories/suzuki-swift-dzire-12.webp',
                'ad'    =>  '',
                'order'                 =>  3
            ]);

            $FX = Category::create([
                'identification'        =>  'FX',
                'name'                  =>  'Gama FX',
                'category'              =>  'Gama FX Sedán Automático',
                'description'           =>  '5 personas +2 maletas grandes y 2 pequeñas +4 Puertas
                Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
                Dirección Asistida +Air Bag',
                'image'                 =>  'carcategories/hyundai-accent-advance-16.webp',
                'ad'    =>  '',
                'order'                 =>  4
            ]);

            // $FY = Category::create([
            //     'identification'        =>  'FY',
            //     'name'                  =>  'Gama FY',
            //     'category'              =>  'Gama FY Vehículo Híbrido Mecánico',
            //     'description'           =>  '5 personas +2 maletas grandes y 2 pequeñas +4 Puertas
            //     Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
            //     Dirección Asistida +Air Bag',
            //     'image'                 =>  'carcategories/suzuki-swift-hibrido.webp',
            //     'ad'    =>  '',
            //     'order'                 =>  5
            // ]);

            $LY = Category::create([
                'identification'        =>  'LY',
                'name'                  =>  'Gama LY',
                'category'              =>  'Gama LY Sedán Automático Eléctrico',
                'description'           =>  'Automóvil Electrico de 220km de Autonomia , 5 pasajeros, 2 equipajes grandes y 2 de mano, Aire AC, Radio, Vidrios electricos, Direccion EPS, Frenos ABS, AirBags',
                'image'                 =>  'carcategories/renault-zoe-o-similar.webp',
                'ad'    =>  '',
                'order'                 =>  6
            ]);

            // $H = Category::create([
            //     'identification'        =>  'H',
            //     'name'                  =>  'Gama H',
            //     'category'              =>  'Gama H Ejecutivo Automatico',
            //     'description'           =>  '5 pasajeros +2 equipajes grandes y 2 de mano, Aire AC, Radio, Cierre Central, Vidrios Eléctricos, Dirección Asistida, Frenos ABS, AirBag',
            //     'image'                 =>  'carcategories/nissan.webp',
            //     'ad'    =>  '',
            //     'order'                 =>  7
            // ]);

            $G = Category::create([
                'identification'        =>  'G',
                'name'                  =>  'Gama G',
                'category'              =>  'Gama G Camioneta Mecánica',
                'description'           =>  'Camioneta de Transmisión Mecanica 4x2 a Gasolina, 5 pasageros, 5 Puertas, Aire AC, Radio, Cierre central, Vídrios Eléctricos, Dirección Asistida, Frenos ABS, Air Bags.',
                'image'                 =>  'carcategories/arona.webp',
                'ad'    =>  '',
                'order'                 =>  8
            ]);

            $GC = Category::create([
                'identification'        =>  'GC',
                'name'                  =>  'Gama GC',
                'category'              =>  'Gama GC Camioneta Automática',
                'description'           =>  '5 personas +2 maletas grandes y 3 pequeñas +4 Puertas
                Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
                Dirección Asistida +Air Bag',
                'image'                 =>  'carcategories/seat-arona-16.webp',
                'ad'    =>  '',
                'order'                 =>  9
            ]);

            $G4 = Category::create([
                'identification'        =>  'G4',
                'name'                  =>  'Gama G4',
                'category'              =>  'Gama G4 Camioneta Mecánica 4X4',
                'description'           =>  '5 personas +2 maletas grandes y 3 pequeñas +4 Puertas
                Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
                Dirección Asistida +Air Bag',
                'image'                 =>  'carcategories/renault-duster-dynamiqe-20.webp',
                'ad'    =>  '',
                'order'                 =>  10
            ]);

            // $V = Category::create([
            //     'identification'        =>  'V',
            //     'name'                  =>  'Gama V',
            //     'category'              =>  'Gama V Pick-Up Mecanica 4X2',
            //     'description'           =>  'Camioneta 4X2 de Transmisión Mecanica, Doble Cabina, 650kg de Carga, 1.4L a Gasolina, Potencia 84HP, 5 pasageros, 4 Puertas, Aire AC, Radio, Cierre central, Vídrios Eléctricos, Dirección Asistida, Frenos ABS, Air Bags.',
            //     'image'                 =>  'carcategories/dodge-ram.webp',
            //     'ad'    =>  '',
            //     'order'                 =>  11
            // ]);

            $LP = Category::create([
                'identification'        =>  'LP',
                'name'                  =>  'Gama LP',
                'category'              =>  'Gama LP Sedán Automático Híbrido',
                'description'           =>  '5 personas +2 maletas grandes y 2 pequeñas +4 Puertas
                Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
                Dirección Asistida +Air Bag',
                'image'                 =>  'carcategories/toyota-corolla-hibrido.webp',
                'ad'    =>  '',
                'order'                 =>  12
            ]);

            $VP = Category::create([
                'identification'        =>  'VP',
                'name'                  =>  'Gama VP',
                'category'              =>  'Gama VP Camioneta Mecánica de Platón',
                'description'           =>  'Camioneta 4x4 de Transmisión Mecanica, Doble Cabina, 650kg de Carga, 1.3L Turbo a Gasolina, Potencia 154HP, 5 pasageros, 4 Puertas, Aire AC, Radio, Cierre central, Vídrios Eléctricos, Dirección Asistida, Frenos ABS, Air Bags.',
                'image'                 =>  'carcategories/renault-duster-oroch-4x4.webp',
                'ad'    =>  '',
                'order'                 =>  13
            ]);

            $GX = Category::create([
                'identification'        =>  'GX',
                'name'                  =>  'Gama GX',
                'category'              =>  'Gama GX Camioneta Automática 4x2',
                'description'           =>  'Camioneta de Transmisión Automatica 4x2 a Gasolina, 5 pasageros, 5 Puertas, Aire AC, Radio, Cierre central, Vídrios Eléctricos, Dirección Asistida, Frenos ABS, Air Bags.',
                'image'                 =>  'carcategories/susuki-vitara-15.webp',
                'ad'    =>  '',
                'order'                 =>  14
            ]);

            $LE = Category::create([
                'identification'        =>  'LE',
                'name'                  =>  'Gama LE',
                'category'              =>  'Gama LE Camioneta Automática Especial',
                'description'           =>  'Modelos 2022 2023 2024 ++ 5 personas +2 maletas grandes y 3 pequeñas +4 Puertas
                Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
                Dirección Asistida +Air Bag',
                'image'                 =>  'carcategories/renault-koleos-25.webp',
                'ad'    =>  '',
                'order'                 =>  15
            ]);

            $GR = Category::create([
                'identification'        =>  'GR',
                'name'                  =>  'Gama GR',
                'category'              =>  'Gama GR Camioneta Automática 7 puestos',
                'description'           =>  '7 Puestos  + 2 maletas grandes y 3 pequeñas +4 Puertas

                Dirección Asistida +Air Bag',
                'image'                 =>  'carcategories/mitsubishi-montero-sport-30.webp',
                'ad'    =>  '',
                'order'                 =>  16
            ]);

            // $P = Category::create([
            //     'identification'        =>  'P',
            //     'name'                  =>  'Gama P',
            //     'category'              =>  'Gama P Camioneta 4x4 Estandar Mecánica/Automático',
            //     'description'           =>  '5 Puestos +4 Puertas
            //     Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
            //     Dirección Asistida +Air Bag',
            //     'image'                 =>  'carcategories/toyota-hilux.webp',
            //     'ad'    =>  '',
            //     'order'                 =>  17
            // ]);

            $FL = Category::create([
                'identification'        =>  'FL',
                'name'                  =>  'Gama FL',
                'category'              =>  'Gama FL Compacto Mecánico Híbrido',
                'description'           =>  '5 Puestos +4 Puertas
                Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
                Dirección Asistida +Air Bag +Sin pico y placa',
                'image'                 =>  'carcategories/gama-fl-compacto-mecanico-hibirdo.webp',
                'ad'    =>  '',
                'order'                 =>  18
            ]);

            $GL = Category::create([
                'identification'        =>  'GL',
                'name'                  =>  'Gama GL',
                'category'              =>  'Gama GL Camioneta Automática',
                'description'           =>  '5 Puestos +4 Puertas
                Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
                Dirección Asistida +Air Bag +Sin pico y placa',
                'image'                 =>  'carcategories/gama-gl-camioneta-automatica.webp',
                'ad'    =>  '',
                'order'                 =>  20
            ]);

            $FU = Category::create([
                'identification'        =>  'FU',
                'name'                  =>  'Gama FU',
                'category'              =>  'Gama FU Sedán Automático',
                'description'           =>  '5 personas +2 maletas grandes y 2 pequeñas +4 Puertas
                Aire Acondicionado +Radio +Cierre central +Vidrios eléctrico
                Dirección Asistida +Air Bag +Sin pico y placa',
                'image'                 =>  'carcategories/hyundai-accent-advance-16.webp',
                'ad'    =>  '',
                'order'                 =>  19
            ]);
        }

        // create category models
        if(true){
            CategoryModel::insert([

                [
                    'category_id' 			=>  $C->id,
                    'name'              =>  'Renault Kwid 1.0',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/renault-kwid-10-o-similar.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $C->id,
                    'name'              =>  'Susuki S-Presso 1.0',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/susuki-s-presso-10.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $C->id,
                    'name'              =>  'Kia Picanto 1.0',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/chevrolet-spark-gt-12.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $C->id,
                    'name'              =>  'Fiat Mobi 1.0',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/fiat-mobi-10.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $F->id,
                    'name'              =>  'Hyundai Accent 1.5',
                    'description'       =>  'Sedán o similar',
                    'image'             =>  'carcategories/hyundai-accent-16.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $F->id,
                    'name'              =>  'Suzuki Baleno 1.4',
                    'description'       =>  'HatchBack o similar',
                    'image'             =>  'carcategories/suzuki-baleno-hb-13.webp',
                    'default'           =>  0,
                ],



                [
                    'category_id' 			=>  $F->id,
                    'name'              =>  'Chevrolet Joy 1.4',
                    'description'       =>  'Sedán o similar',
                    'image'             =>  'carcategories/chevrolet-joy-14-sedan.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $F->id,
                    'name'              =>  'Volkswagen Voyage 1.6',
                    'description'       =>  'Sedán o similar',
                    'image'             =>  'carcategories/volkswagen-voyage-16-sedan.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $F->id,
                    'name'              =>  'Renault Sandero 1.6',
                    'description'       =>  'HatchBack o similar',
                    'image'             =>  'carcategories/renault-logan-16.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $FX->id,
                    'name'              =>  'Renault Logan 1.6',
                    'description'       =>  'Sedán o similar',
                    'image'             =>  'carcategories/renault-logan-16.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $FX->id,
                    'name'              =>  'Volkswagen Gol 1.6',
                    'description'       =>  'HatchBack o similar',
                    'image'             =>  'carcategories/volkswagen-gol-16.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $FX->id,
                    'name'              =>  'Kia Rio 1.3',
                    'description'       =>  'HacthBack o similar',
                    'image'             =>  'carcategories/kia-rio-13.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $FX->id,
                    'name'              =>  'Suzuki Swift Dzire 1.2',
                    'description'       =>  'Sedán o similar',
                    'image'             =>  'carcategories/suzuki-swift-dzire-12.webp',
                    'default'           =>  1,
                ],

                // [
                //     'category_id' 			=>  $FY->id,
                //     'name'              =>  'Suzuki Swift Híbrido',
                //     'description'       =>  'o similar',
                //     'image'             =>  'carcategories/suzuki-swift-hibrido.webp',
                //     'default'           =>  1,
                // ],

                [
                    'category_id' 			=>  $G->id,
                    'name'              =>  'Hyundai Creta',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/hyundai-creta.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $G->id,
                    'name'              =>  'Seat Arona',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/seat-arona.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $G->id,
                    'name'              =>  'Suzuki Vitara 1.4',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/suzuki-vitara-1.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $G->id,
                    'name'              =>  'Fiat Pulse 1.3',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/fiat-pulse-13.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $G4->id,
                    'name'              =>  'Renault Duster Iconic 4x2',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/renault-duster-dynamiqe-20.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $GC->id,
                    'name'              =>  'Opel Crossland',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/opel-crossland.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $GC->id,
                    'name'              =>  'Kia Sonet',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/kia-sonet.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $GC->id,
                    'name'              =>  'Hyundai Creta 1.6',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/hyundai-creta-16.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $GR->id,
                    'name'              =>  'Chevrolet Trailblazer 2.8',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/chevrolet-trailblazer-28.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $GR->id,
                    'name'              =>  'Mitsubishi Montero Sport 3.0',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/mitsubishi-montero-sport-30.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $GX->id,
                    'name'              =>  'Susuki Vitara 1.5',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/susuki-vitara-15.webp',
                    'default'           =>  1,
                ],

                // [
                //     'category_id' 			=>  $H->id,
                //     'name'              =>  'Renault Stepway 1.6',
                //     'description'       =>  'HatchBack o similar',
                //     'image'             =>  'carcategories/renault-sandero-stepway-16.webp',
                //     'default'           =>  0,
                // ],

                // [
                //     'category_id' 			=>  $H->id,
                //     'name'              =>  'Hyinday Advance Premiun 1.6',
                //     'description'       =>  'Sedán o similar',
                //     'image'             =>  'carcategories/hyundai-accent-premium-16.webp',
                //     'default'           =>  1,
                // ],

                [
                    'category_id' 			=>  $LE->id,
                    'name'              =>  'Nissan Qashqai 2.0',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/nissan-qashqai-20.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $LE->id,
                    'name'              =>  'Mitsubishi Outlander 2.4',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/mitsubishi-outlander-24.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $LE->id,
                    'name'              =>  'Renault Koleos 2.5',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/renault-koleos-25.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $LP->id,
                    'name'              =>  'Toyota Corolla Híbrido',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/toyota-corolla-hibrido.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $LY->id,
                    'name'              =>  'Changan E-Star Electrico',
                    'description'       =>  'HatchBack o similar',
                    'image'             =>  'carcategories/e-star-electrico-automatico.webp',
                    'default'           =>  1,
                ],

                // [
                //     'category_id' 			=>  $V->id,
                //     'name'              =>  'RAM 700 SLT CrewCab 4X2',
                //     'description'       =>  'o similar',
                //     'image'             =>  'carcategories/ram-700-slt-crewcab-4x2.webp',
                //     'default'           =>  1,
                // ],

                [
                    'category_id' 			=>  $VP->id,
                    'name'              =>  'Renault Duster Oroch 4x4',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/prueva-1.webp',
                    'default'           =>  1,
                ],

                // [
                //     'category_id' 			=>  $P->id,
                //     'name'              =>  'Toyota Hilux 4x4',
                //     'description'       =>  'o similar',
                //     'image'             =>  'carcategories/toyota-hilux.webp',
                //     'default'           =>  1,
                // ],

                [
                    'category_id' 			=>  $FL->id,
                    'name'              =>  'Suzuki Swift Híbrido',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/gama-fl-compacto-mecanico-hibirdo.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $FU->id,
                    'name'              =>  'Suzuki Baleno 1.4',
                    'description'       =>  'HatchBack o similar',
                    'image'             =>  'carcategories/suzuki-baleno-hb-13.webp',
                    'default'           =>  1,
                ],

                [
                    'category_id' 			=>  $FU->id,
                    'name'              =>  'Hyundai Accent Advanced 1.6',
                    'description'       =>  'Sedán o similar',
                    'image'             =>  'carcategories/hyundai-accent-advance-16.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $FU->id,
                    'name'              =>  'Suzuki Swift Dzire 1.2',
                    'description'       =>  'Sedán o similar',
                    'image'             =>  'carcategories/suzuki-swift-dzire-12.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $GL->id,
                    'name'              =>  'Fiat Pulse Turbo 1.3',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/fiat-pulse-13.webp',
                    'default'           =>  0,
                ],

                [
                    'category_id' 			=>  $GL->id,
                    'name'              =>  'Renault Duster 1.3',
                    'description'       =>  'o similar',
                    'image'             =>  'carcategories/gama-gl-camioneta-automatica.webp',
                    'default'           =>  1,
                ],

            ]);
        }

        // create branches
        if(true){
            $AARME = Branch::create([
                'city_id'   =>  $armenia->id,
                'code'      =>  'AARME',
                'name'      =>  'Armenia Aeropuerto'
            ]);
            $AABAN = Branch::create([
                'city_id'   =>  $barranquilla->id,
                'code'      =>  'AABAN',
                'name'      =>  'Barranquilla Aeropuerto'
            ]);
            $ACBAN = Branch::create([
                'city_id'   =>  $barranquilla->id,
                'code'      =>  'ACBAN',
                'name'      =>  'Barranquilla Norte'
            ]);
            $ACBSD = Branch::create([
                'city_id'   =>  $soledad->id,
                'code'      =>  'ACBSD',
                'name'      =>  'Soledad'
            ]);
            $AABOT = Branch::create([
                'city_id'   =>  $bogota->id,
                'code'      =>  'AABOT',
                'name'      =>  'Bogotá Aeropuerto'
            ]);
            $ACBOT = Branch::create([
                'city_id'   =>  $bogota->id,
                'code'      =>  'ACBOT',
                'name'      =>  'Bogotá Av. Caracas con 72'
            ]);
            $ACBEX = Branch::create([
                'city_id'   =>  $bogota->id,
                'code'      =>  'ACBEX',
                'name'      =>  'Bogotá Almacen Éxito del Country'
            ]);
            $ACBOJ = Branch::create([
                'city_id'   =>  $bogota->id,
                'code'      =>  'ACBOJ',
                'name'      =>  'Bogotá Almacen Yumbo Calle 170'
            ]);
            $ACBBN = Branch::create([
                'city_id'   =>  $bogota->id,
                'code'      =>  'ACBBN',
                'name'      =>  'Bogotá C.Cial Bulevar Niza'
            ]);
            $ACBOF = Branch::create([
                'city_id'   =>  $bogota->id,
                'code'      =>  'ACBOF',
                'name'      =>  'Bogotá C.Cial Outlet Factory'
            ]);
            $ACCHI = Branch::create([
                'city_id'   =>  $chia->id,
                'code'      =>  'ACCHI',
                'name'      =>  'Chía C.Cial Bazzar'
            ]);
            $AABCR = Branch::create([
                'city_id'   =>  $bucaramanga->id,
                'code'      =>  'AABCR',
                'name'      =>  'Bucaramanga Aeropuerto'
            ]);
            $ACBCR = Branch::create([
                'city_id'   =>  $floridablanca->id,
                'code'      =>  'ACBCR',
                'name'      =>  'Floridablanca'
            ]);
            $AAKAL = Branch::create([
                'city_id'   =>  $cali->id,
                'code'      =>  'AAKAL',
                'name'      =>  'Cali Aeropuerto'
            ]);
            $ACKAL = Branch::create([
                'city_id'   =>  $cali->id,
                'code'      =>  'ACKAL',
                'name'      =>  'Cali Sur Camino Real'
            ]);
            $ACKJC = Branch::create([
                'city_id'   =>  $cali->id,
                'code'      =>  'ACKJC',
                'name'      =>  'Cali Norte Chipichape'
            ]);
            $ACKVL = Branch::create([
                'city_id'   =>  $cali->id,
                'code'      =>  'ACKVL',
                'name'      =>  'Cali Sur Valle de Lili'
            ]);
            $ACKPA = Branch::create([
                'city_id'   =>  $palmira->id,
                'code'      =>  'ACKPA',
                'name'      =>  'Palmira C.Cial Plaza Madero'
            ]);
            $AACTG = Branch::create([
                'city_id'   =>  $cartagena->id,
                'code'      =>  'AACTG',
                'name'      =>  'Cartagena Aeropuerto'
            ]);
            $AACUC = Branch::create([
                'city_id'   =>  $cucuta->id,
                'code'      =>  'AACUC',
                'name'      =>  'Cucuta Aeropuerto'
            ]);
            $ACIBG = Branch::create([
                'city_id'   =>  $ibague->id,
                'code'      =>  'ACIBG',
                'name'      =>  'Ibagué C.Cial Plazas del Bosque'
            ]);
            $ACMNZ = Branch::create([
                'city_id'   =>  $manizales->id,
                'code'      =>  'ACMNZ',
                'name'      =>  'Manizales C.Cial Mallplaza'
            ]);
            $ACMDL = Branch::create([
                'city_id'   =>  $medellin->id,
                'code'      =>  'ACMDL',
                'name'      =>  'Medellín Las Vegas el Poblado'
            ]);

            $ACMCL = Branch::create([
                'city_id'   =>  $medellin->id,
                'code'      =>  'ACMCL',
                'name'      =>  'Medellín Centro, Éxito Colombia'
            ]);
            $ACMNN = Branch::create([
                'city_id'   =>  $medellin->id,
                'code'      =>  'ACMNN',
                'name'      =>  'Medellín Ciudad del Rio El Poblado'
            ]);
            $AAMDL = Branch::create([
                'city_id'   =>  $medellin->id,
                'code'      =>  'AAMDL',
                'name'      =>  'Medellín Aeropuerto José María C.'
            ]);
            $ACMJM = Branch::create([
                'city_id'   =>  $medellin->id,
                'code'      =>  'ACMJM',
                'name'      =>  'Rionegro'
            ]);
            $ACMPN = Branch::create([
                'city_id'   =>  $medellin->id,
                'code'      =>  'ACMPN',
                'name'      =>  'Bello C.Cial Puerta del Norte'
            ]);
            $ACMAY = Branch::create([
                'city_id'   =>  $sabaneta->id,
                'code'      =>  'ACMAY',
                'name'      =>  'Sabaneta C.Cial Mayorca'
            ]);
            $AAMTR = Branch::create([
                'city_id'   =>  $monteria->id,
                'code'      =>  'AAMTR',
                'name'      =>  'Montería Aeropuerto'
            ]);
            $ACMTR = Branch::create([
                'city_id'   =>  $monteria->id,
                'code'      =>  'ACMTR',
                'name'      =>  'Montería C.Cial Buenavista'
            ]);
            $AANVA = Branch::create([
                'city_id'   =>  $neiva->id,
                'code'      =>  'AANVA',
                'name'      =>  'Neiva Aeropuerto'
            ]);
            $AAPEI = Branch::create([
                'city_id'   =>  $pereira->id,
                'code'      =>  'AAPEI',
                'name'      =>  'Pereira Aeropuerto'
            ]);
            $AASMR = Branch::create([
                'city_id'   =>  $santamarta->id,
                'code'      =>  'AASMR',
                'name'      =>  'Santa Marta Aeropuerto'
            ]);
            $ACSMR = Branch::create([
                'city_id'   =>  $santamarta->id,
                'code'      =>  'ACSMR',
                'name'      =>  'Santa Marta Barrio El prado'
            ]);

            $AAVAL = Branch::create([
                'city_id'   =>  $valledupar->id,
                'code'      =>  'AAVAL',
                'name'      =>  'Valledupar Aeropuerto'
            ]);
            $ACVLL = Branch::create([
                'city_id'   =>  $villavicencio->id,
                'code'      =>  'ACVLL',
                'name'      =>  'Villavicencio C.Cial Llano Centro'
            ]);
        }

        // category month prices
        if(true){
            // car month price 2023-12-01 - 2024-01-15
            CategoryMonthPrice::insert([
                [
                    'category_id'    =>  $C->id,
                    '1k_kms'   =>  4_304_990,
                    '2k_kms'   =>  4_304_990,
                    '3k_kms'   =>  4_893_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $F->id,
                    '1k_kms'   =>  4_768_990,
                    '2k_kms'   =>  4_768_990,
                    '3k_kms'   =>  5_420_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $FX->id,
                    '1k_kms'   =>  5_478_990,
                    '2k_kms'   =>  5_478_990,
                    '3k_kms'   =>  6_226_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $FL->id,
                    '1k_kms'   =>  5_695_990,
                    '2k_kms'   =>  5_695_990,
                    '3k_kms'   =>  6_473_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $LY->id,
                    '1k_kms'   =>  5_788_990,
                    '2k_kms'   =>  5_788_990,
                    '3k_kms'   =>  6_579_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                // [
                //     'category_id'    =>  $H->id,
                //     '1k_kms'   =>  6_159_990,
                //     '2k_kms'   =>  6_159_990,
                //     '3k_kms'   =>  7_000_990,
                //     'init_date' =>  '2024-01-15',
                //     'end_date' =>  '2024-01-30',
                // ],
                [
                    'category_id'    =>  $LP->id,
                    '1k_kms'   =>  9_232_990,
                    '2k_kms'   =>  9_232_990,
                    '3k_kms'   =>  10_243_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $G->id,
                    '1k_kms'   =>  7_199_990,
                    '2k_kms'   =>  7_199_990,
                    '3k_kms'   =>  8_230_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $GC->id,
                    '1k_kms'   =>  7_640_990,
                    '2k_kms'   =>  7_640_990,
                    '3k_kms'   =>  8_734_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $G4->id,
                    '1k_kms'   =>  7_958_990,
                    '2k_kms'   =>  7_958_990,
                    '3k_kms'   =>  9_098_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $GX->id,
                    '1k_kms'   =>  8_868_990,
                    '2k_kms'   =>  8_868_990,
                    '3k_kms'   =>  9_839_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $LE->id,
                    '1k_kms'   =>  10_143_990,
                    '2k_kms'   =>  10_143_990,
                    '3k_kms'   =>  11_253_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                [
                    'category_id'    =>  $GR->id,
                    '1k_kms'   =>  11_508_990,
                    '2k_kms'   =>  11_508_990,
                    '3k_kms'   =>  12_768_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                // [
                //     'category_id'    =>  $V->id,
                //     '1k_kms'   =>  8_231_990,
                //     '2k_kms'   =>  8_231_990,
                //     '3k_kms'   =>  9_410_990,
                //     'init_date' =>  '2024-01-15',
                //     'end_date' =>  '2024-01-30',
                // ],
                [
                    'category_id'    =>  $VP->id,
                    '1k_kms'   =>  8_595_990,
                    '2k_kms'   =>  8_595_990,
                    '3k_kms'   =>  9_826_990,
                    'init_date' =>  '2024-01-15',
                    'end_date' =>  '2024-01-30',
                ],
                // [
                //     'category_id'    =>  $P->id,
                //     '1k_kms'   =>  10_143_990,
                //     '2k_kms'   =>  10_143_990,
                //     '3k_kms'   =>  11_758_990,
                //     'init_date' =>  '2024-01-15',
                //     'end_date' =>  '2024-01-30',
                // ],


            ]);

        }

        if(true){
            CityCategoryVisibility::insert([
                // category FY
                // [
                //     'city_id'   =>  $apartado->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $armenia->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $barranquilla->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $bucaramanga->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $cali->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $cartagena->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $cucuta->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $ibague->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $manizales->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $monteria->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $neiva->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $pereira->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $popayan->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $santamarta->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $tunja->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $valledupar->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $villavicencio->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $yopal->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $pasto->id,
                //     'category_id'    =>  $FY->id,
                //     'visible'   =>  false,
                // ],
                // // category V
                // [
                //     'city_id'   =>  $apartado->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $armenia->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $barranquilla->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $bucaramanga->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $cali->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $cartagena->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $cucuta->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $ibague->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $manizales->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $monteria->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $neiva->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $pereira->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $popayan->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $santamarta->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $tunja->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $valledupar->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $villavicencio->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $yopal->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // [
                //     'city_id'   =>  $pasto->id,
                //     'category_id'    =>  $V->id,
                //     'visible'   =>  false,
                // ],
                // category VP

                [
                    'city_id'   =>  $armenia->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $barranquilla->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $bogota->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  true,
                ],
                [
                    'city_id'   =>  $bucaramanga->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  true,
                ],
                [
                    'city_id'   =>  $cali->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  true,
                ],
                [
                    'city_id'   =>  $cartagena->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $cucuta->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $ibague->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $manizales->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $monteria->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  true,
                ],
                [
                    'city_id'   =>  $neiva->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $pereira->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],

                [
                    'city_id'   =>  $santamarta->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],

                [
                    'city_id'   =>  $valledupar->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $villavicencio->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $yopal->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $pasto->id,
                    'category_id'    =>  $VP->id,
                    'visible'   =>  false,
                ],
                // category LP

                [
                    'city_id'   =>  $armenia->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $barranquilla->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $bucaramanga->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $cali->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $cartagena->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $cucuta->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $ibague->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $manizales->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $monteria->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $neiva->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $pereira->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],

                [
                    'city_id'   =>  $santamarta->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],

                [
                    'city_id'   =>  $valledupar->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $villavicencio->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $yopal->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $pasto->id,
                    'category_id'    =>  $LP->id,
                    'visible'   =>  false,
                ],
                // category LY


                [
                    'city_id'   =>  $barranquilla->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $bucaramanga->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $cali->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $cartagena->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $cucuta->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $ibague->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $manizales->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $monteria->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $neiva->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $pereira->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],

                [
                    'city_id'   =>  $santamarta->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],

                [
                    'city_id'   =>  $valledupar->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $villavicencio->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $yopal->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],
                [
                    'city_id'   =>  $pasto->id,
                    'category_id'    =>  $LY->id,
                    'visible'   =>  false,
                ],

            ]);

            // no visibility anywhere for categories G,GX temporaly

            City::each(fn($city) => CityCategoryVisibility::updateOrCreate([
                'city_id'=>$city->id,
                'category_id'=>$G->id,
                'visible'=>false
            ]));

            City::each(fn($city) => CityCategoryVisibility::updateOrCreate([
                'city_id'=>$city->id,
                'category_id'=>$GX->id,
                'visible'=>false
            ]));

            if(true){
                // no visibility temporarely
                // armenia
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$armenia->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);
                // bogota
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$bogota->id,
                    'category_id'=>$GC->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$bogota->id,
                    'category_id'=>$VP->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$bogota->id,
                    'category_id'=>$VP->id,
                    'visible'=>false
                ]);
                // bucaramanga
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$bucaramanga->id,
                    'category_id'=>$GC->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$bucaramanga->id,
                    'category_id'=>$LP->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$bucaramanga->id,
                    'category_id'=>$VP->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$bucaramanga->id,
                    'category_id'=>$LY->id,
                    'visible'=>false
                ]);
                // cucuta
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$cucuta->id,
                    'category_id'=>$LE->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$cucuta->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);
                // ibague
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$ibague->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);
                // manizales
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$manizales->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);
                // medellin
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$medellin->id,
                    'category_id'=>$LP->id,
                    'visible'=>false
                ]);
                // monteria
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$monteria->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);
                // neiva
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$neiva->id,
                    'category_id'=>$LE->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$neiva->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);
                // pereira
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$pereira->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);
                // valledupar
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$valledupar->id,
                    'category_id'=>$LE->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$valledupar->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);
                // villavicencio
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$villavicencio->id,
                    'category_id'=>$LE->id,
                    'visible'=>false
                ]);
                CityCategoryVisibility::updateOrCreate([
                    'city_id'=>$villavicencio->id,
                    'category_id'=>$GR->id,
                    'visible'=>false
                ]);


            }

        }
    }
}
