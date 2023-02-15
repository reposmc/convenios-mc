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
                "place_name" => "TEATRO PRESIDENTE, GRAN SALA",
            ],
            [
                "id" => 2,
                "place_name" => "TEATRO NACIONAL, GRAN SALA",
            ],
            [
                "id" => 3,
                "place_name" => "TEATRO NACIONAL, PEQUEÑA SALA",
            ],
            [
                "id" => 4,
                "place_name" => "TEATRO DE SANTA ANA, GRAN SALA",
            ],
            [
                "id" => 5,
                "place_name" => "TEATRO NACIONAL FRANCISCO GAVIDIA, SAN MIGUEL",
            ],
            [
                "id" => 6,
                "place_name" => "TEATRO NACIONAL DE SAN SALVADOR",
            ],
            [
                "id" => 7,
                "place_name" => "TEATRO NACIONAL DE SANTA ANA",
            ],
            [
                "id" => 8,
                "place_name" => "PARQUE RECREATIVO INFANTIL DE DIVERSIONES",
            ],
            [
                "id" => 9,
                "place_name" => "PARQUE SABURO HIRAO Y MUSEO DE HISTORIA NATURAL DE EL SALVADOR",
            ],
            [
                "id" => 10,
                "place_name" => "PARQUE ZOOLÓGICO NACIONAL",
            ],
            [
                "id" => 11,
                "place_name" => "MUSEO NACIONAL DE ANTROPOLOGÍA “DR. DAVID J. GUZMÁN”",
            ],
            [
                "id" => 12,
                "place_name" => "MUSEOS REGIONALES DE ORIENTE Y OCCIDENTE",
            ],
            [
                "id" => 13,
                "place_name" => "PARQUES ARQUEOLÓGICOS (SAN ANDRES, CASA BLANCA, JOYA DE CEREN, TAZUMAL Y CIHUATÁN)",
            ],
        ]);
    }
}
