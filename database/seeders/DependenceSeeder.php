<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Dependence;

class DependenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dependence::insert([
        [
            "id" => 1,
            "dependence_name" => "Compañía Nacional de Danza",
            "national_direction_id" => 1,
        ],
        [
            "id" => 2,
            "dependence_name" => "Ballet Folklórico Nacional",
            "national_direction_id" => 1,
        ],
        [
            "id" => 3,
            "dependence_name" => "Ballet Nacional de El Salvador",
            "national_direction_id" => 1,
        ],
        [
            "id" => 4,
            "dependence_name" => "Orquesta Sinfónica de El Salvador",
            "national_direction_id" => 1,
        ],
        [
            "id" => 5,
            "dependence_name" => "Coro Nacional de El Salvador",
            "national_direction_id" => 1,
        ],
        [
            "id" => 6,
            "dependence_name" => "Dirección de Teatros Nacionales",
            "national_direction_id" => 1,
        ],
        [
            "id" => 7,
            "dependence_name" => "Teatro Nacional de San Salvador",
            "national_direction_id" => 1,
        ],
        [
            "id" => 8,
            "dependence_name" => "Teatro Presidente",
            "national_direction_id" => 1,
        ],
        [
            "id" => 9,
            "dependence_name" => "Teatro Nacional de Santa Ana",
            "national_direction_id" => 1,
        ],
        [
            "id" => 10,
            "dependence_name" => "Teatro Nacional Francisco Gavidia de San Miguel",
            "national_direction_id" => 1,
        ],
        [
            "id" => 11,
            "dependence_name" => "Dirección de Cine",
            "national_direction_id" => 1,
        ],
        [
            "id" => 12,
            "dependence_name" => "Proyectos y Producción Audiovisual",
            "national_direction_id" => 1,
        ],
        [
            "id" => 13,
            "dependence_name" => "Dirección de Arte X",
            "national_direction_id" => 1,
        ],
        [
            "id" => 14,
            "dependence_name" => "Centro Nacional de Artes (CENAR)",
            "national_direction_id" => 2,
        ],
        [
            "id" => 15,
            "dependence_name" => "Escuela Nacional de Danza Morena Celarié",
            "national_direction_id" => 2,
        ],
        [
            "id" => 16,
            "dependence_name" => "Sistemas de Coros y Orquestas Juveniles",
            "national_direction_id" => 2,
        ],
        [
            "id" => 17,
            "dependence_name" => "Dirección de Parques Culturales",
            "national_direction_id" => 3,
        ],
        [
            "id" => 18,
            "dependence_name" => "Parque Saburo Hirao",
            "national_direction_id" => 3,
        ],
        [
            "id" => 19,
            "dependence_name" => "Parque Infantil de Diversiones",
            "national_direction_id" => 3,
        ],
        [
            "id" => 20,
            "dependence_name" => "Parque Zoológico Nacional -Resguardo y Conservación de Fauna Silvestre-",
            "national_direction_id" => 3,
        ],
        [
            "id" => 21,
            "dependence_name" => "Dirección de Casas de la Cultura",
            "national_direction_id" => 3,
        ],
        [
            "id" => 22,
            "dependence_name" => "Casas de la Cultura Regional de Oriente",
            "national_direction_id" => 3,
        ],
        [
            "id" => 23,
            "dependence_name" => "Casas de la Cultura Regional de Occidente",
            "national_direction_id" => 3,
        ],
        [
            "id" => 24,
            "dependence_name" => "Casas de la Cultura Regional Central",
            "national_direction_id" => 3,
        ],
        [
            "id" => 25,
            "dependence_name" => "Casas de la Cultura Regional Paracentral",
            "national_direction_id" => 3,
        ],
        [
            "id" => 26,
            "dependence_name" => "Red de Bibliotecas",
            "national_direction_id" => 4,
        ],
        [
            "id" => 27,
            "dependence_name" => "Biblioteca Nacional de El Salvador Francisco Gavidia",
            "national_direction_id" => 4,
        ],
        [
            "id" => 28,
            "dependence_name" => "Archivo General de la Nación",
            "national_direction_id" => 4,
        ],
        [
            "id" => 29,
            "dependence_name" => "Dirección de Publicaciones e Impresos",
            "national_direction_id" => 4,
        ],
        [
            "id" => 30,
            "dependence_name" => "Museo Nacional de Antropología Dr. David J. Guzmán",
            "national_direction_id" => 5,
        ],
        [
            "id" => 31,
            "dependence_name" => "Museo de Historia Natural de El Salvador",
            "national_direction_id" => 5,
        ],
        [
            "id" => 32,
            "dependence_name" => "Museo Regional de Occidente",
            "national_direction_id" => 5,
        ],
        [
            "id" => 33,
            "dependence_name" => "Museo Regional de Oriente",
            "national_direction_id" => 5,
        ],
        [
            "id" => 34,
            "dependence_name" => "Sala Nacional de Exposiciones Salarrué",
            "national_direction_id" => 5,
        ],
        [
            "id" => 35,
            "dependence_name" => "Sala de Exposiciones San Jacinto",
            "national_direction_id" => 5,
        ],
        [
            "id" => 36,
            "dependence_name" => "Dirección de Arqueología",
            "national_direction_id" => 6,
        ],
        [
            "id" => 37,
            "dependence_name" => "Unidad de Inspección y Prospección Arqueológica",
            "national_direction_id" => 6,
        ],
        [
            "id" => 38,
            "dependence_name" => "Unidad de Evaluación e Investigaciones Arqueológicas",
            "national_direction_id" => 6,
        ],
        [
            "id" => 39,
            "dependence_name" => "Unidad de Conservación de Sitios y Parques Arqueológicos",
            "national_direction_id" => 6,
        ],
        [
            "id" => 40,
            "dependence_name" => "Dirección de Bienes Culturales, Inmuebles y Gestión Urbana",
            "national_direction_id" => 6,
        ],
        [
            "id" => 41,
            "dependence_name" => "Unidad de Inspecciones y Autorizaciones",
            "national_direction_id" => 6,
        ],
        [
            "id" => 42,
            "dependence_name" => "Unidad de Conservación y Restauración de Bienes Culturales Inmuebles",
            "national_direction_id" => 6,
        ],
        [
            "id" => 43,
            "dependence_name" => "Unidad de Protección e Inventarios de Bienes Culturales Inmuebles",
            "national_direction_id" => 6,
        ],
        [
            "id" => 44,
            "dependence_name" => "Dirección de Antropología Cultural",
            "national_direction_id" => 6,
        ],
        [
            "id" => 45,
            "dependence_name" => "Unidad de Educación y Divulgación",
            "national_direction_id" => 6,
        ],
        [
            "id" => 46,
            "dependence_name" => "Unidad de Inspección, Investigación y Valoración",
            "national_direction_id" => 6,
        ],
        [
            "id" => 47,
            "dependence_name" => "Dirección de Registro de Bienes Culturales",
            "national_direction_id" => 6,
        ],
        [
            "id" => 48,
            "dependence_name" => "Unidad de Registro de Bienes Culturales",
            "national_direction_id" => 6,
        ],
        [
            "id" => 49,
            "dependence_name" => "Unidad de Gestión de Inventarios de Bienes Culturales Muebles",
            "national_direction_id" => 6,
        ],
        [
            "id" => 50,
            "dependence_name" => "Unidad de Control de Colecciones Nacionales",
            "national_direction_id" => 6,
        ],
        [
            "id" => 51,
            "dependence_name" => "Unidad de Prevención de Tráfico Ilícito de Bienes Culturales",
            "national_direction_id" => 6,
        ],
        [
            "id" => 52,
            "dependence_name" => "Dirección de Conservación de Bienes Culturales Muebles",
            "national_direction_id" => 6,
        ],
        [
            "id" => 53,
            "dependence_name" => "Laboratorio Taller de Conservación de Bienes Culturales Muebles",
            "national_direction_id" => 6,
        ],
        [
            "id" => 54,
            "dependence_name" => "Dirección de Patrimonio Mundial",
            "national_direction_id" => 6,
        ],
        [
            "id" => 55,
            "dependence_name" => "Parque Arqueológico Joya de Cerén",
            "national_direction_id" => 6,
        ],
        ]);
    }
}
