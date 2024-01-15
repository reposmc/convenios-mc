<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\ServicePlace;

class ServicePlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            ServicePlace::insert([
            [
                "id" => 1,
                "place_name" => "TEATRO PRESIDENTE",
            ],
            [
                "id" => 2,
                "place_name" => "TEATRO NACIONAL SAN SALVADOR",
            ],
            [
                "id" => 3,
                "place_name" => "TEATRO NACIONAL “FRANCISCO GAVIDIA” SAN MIGUEL",
            ],
            [
                "id" => 4,
                "place_name" => "TEATRO NACIONAL DE SANTA ANA",
            ],
            [
                "id" => 5,
                "place_name" => "ESCUELA NACIONAL DE DANZA “MORENA CELARIÉ”",
            ],
            [
                "id" => 6,
                "place_name" => "CENTRO NACIONAL DE ARTES (CENAR)",
            ],
            [
                "id" => 7,
                "place_name" => "PARQUE RECREATIVO INFANTIL DE DIVERSIONES",
            ],
            [
                "id" => 8,
                "place_name" => "PARQUE SABURO HIRAO Y MUSEO DE HISTORIA NATURAL DE EL SALVADOR (MUHNES)",
            ],
            [
                "id" => 9,
                "place_name" => "PARQUE ZOOLÓGICO NACIONAL",
            ],
            [
                "id" => 10,
                "place_name" => "CASA DE LA CULTURA DE AGUILARES",
            ],
            [
                "id" => 11,
                "place_name" => "MUSEO NACIONAL DE ANTROPOLOGÍA “DR. DAVID J. GUZMÁN”",
            ],
            [
                "id" => 12,
                "place_name" => "MUSEO REGIONAL DE ORIENTE",
            ],
            [
                "id" => 13,
                "place_name" => "MUSEO REGIONAL DE OCCIDENTE",
            ],
            [
                "id" => 14,
                "place_name" => "MUSEO DE HISTORIA NATURAL",
            ],
            [
                "id" => 15,
                "place_name" => "PARQUES ARQUEOLÓGICOS CIHUATÁN",
            ],
            [
                "id" => 16,
                "place_name" => "PARQUES ARQUEOLÓGICOS TAZUMAL",
            ],
            [
                "id" => 17,
                "place_name" => "PARQUES ARQUEOLÓGICOS JOYA DE CEREN",
            ],
            [
                "id" => 18,
                "place_name" => "PARQUES ARQUEOLÓGICOS CASA BLANCA",
            ],
            [
                "id" => 19,
                "place_name" => "PARQUES ARQUEOLÓGICOS SAN ANDRES",
            ],
            [
                "id" => 20,
                "place_name" => "PALACIO NACIONAL",
            ],
            [
                "id" => 21,
                "place_name" => "Ex CASA PRESIDENCIAL (EX CAPRES)",
            ],
        ]);
    }
}
