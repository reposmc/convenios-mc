<?php

namespace Database\Seeders;


use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Entity;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entity::insert([
        [
            "id" => 1,
            "entity_name" => "Embajada de Japón",
            "num_ent" => "01",
        ],
        [
            "id" => 2,
            "entity_name" => "50 años de Jhosse Lora",
            "num_ent" => "02",
        ],
        [
            "id" => 3,
            "entity_name" => "Pro Arte",
            "num_ent" => "03",
        ],
        [
            "id" => 4,
            "entity_name" => "Fundación Ballet de El Salvador",
            "num_ent" => "04",
        ],
        [
            "id" => 5,
            "entity_name" => "Alcaldía Municipal de Santa Ana",
            "num_ent" => "05",
        ],
        [
            "id" => 6,
            "entity_name" => "Asart y Colectivo Danzario Istak Shuchit",
            "num_ent" => "06",
        ],
        [
            "id" => 7,
            "entity_name" => "Centro Cultural de España en El Salvador",
            "num_ent" => "07",
        ],
        [
            "id" => 8,
            "entity_name" => "René Lovo, La Galera Teatro",
            "num_ent" => "08",
        ],
        [
            "id" => 9,
            "entity_name" => "Asamblea Legislativa",
            "num_ent" => "09",
        ],
        [
            "id" => 10,
            "entity_name" => "Farandularte",
            "num_ent" => "10",
        ],
        [
            "id" => 11,
            "entity_name" => "TNT",
            "num_ent" => "11",
        ],
        [
            "id" => 12,
            "entity_name" => "Alcaldía Municipal de San Salvador",
            "num_ent" => "12",
        ],
        [
            "id" =>13,
            "entity_name" => "Fundación Padre Vito Guarato",
            "num_ent" => "13",
        ],
        [
            "id" => 14,
            "entity_name" => "Cubos",
            "num_ent" => "14",
        ],
        [
            "id" =>15,
            "entity_name" => "Embajada de la India",
            "num_ent" => "15",
        ],
        [
            "id" => 16,
            "entity_name" => "INDES",
            "num_ent" => "16",
        ],
        [
            "id" => 17,
            "entity_name" => "Embajada de México",
            "num_ent" => "17",
        ],
        [
            "id" => 18,
            "entity_name" => "Festival Nómada",
            "num_ent" => "18",
        ],
        [
            "id" => 19,
            "entity_name" => "Funiber",
            "num_ent" => "19",
        ],
        ]);
    }
}
