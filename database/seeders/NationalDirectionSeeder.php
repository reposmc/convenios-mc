<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\NationalDirection;


class NationalDirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NationalDirection::insert([
        [
            'id' => 1,
            'national_direction_name' => 'Dirección Nacional de Artes',
        ],
        [
            'id' =>2,
            'national_direction_name' => 'Dirección Nacional de Formación de Artes',
        ],
        [
            'id' => 3,
            'national_direction_name' => 'Dirección Nacional de Casas de la Cultura y Parques Culturales',
        ],
        [
            'id' => 4,
            'national_direction_name' => 'Dirección Nacional de Bibliotecas, Archivo y Publicaciones',
        ],
        [
            'id' => 5,
            'national_direction_name' => 'Dirección Nacional de Museos y Salas de Exposición',
        ],
        [
            'id' => 6,
            'national_direction_name' => 'Dirección Nacional de Patrimonio Cultural',
        ],
        ]);
    }
}
