<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Tariff;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tariff::insert([
        [
            "id" => 1,
            "service_place_id" => 1,
            "type_charge" => "TP. GS. Salvadoreños",
            "amount" => "1.75",
        ],
        [
            "id" => 2,
            "service_place_id" => 1,
            "type_charge" => "TP. GS. Centroamericanos y extranjeros residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 3,
            "service_place_id" => 1,
            "type_charge" => "TP. GS. Extranjeros no residentes",
            "amount" => "10.00",
        ],
        [
            "id" => 4,
            "service_place_id" => 1,
            "type_charge" => "TP. GS. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [ 
            "id" => 5,
            "service_place_id" => 1,
            "type_charge" => "TP. GS. Entrada general de promoción cultural de accesabilidad de la población",
            "amount" => "1.00",
        ],
        [
            "id" => 6,
            "service_place_id" => 2,
            "type_charge" => "TN. GS. Salvadoreños",
            "amount" => "1.75",
        ],
        [
            "id" => 7,
            "service_place_id" => 2,
            "type_charge" => "TN. GS. Centroamericanos y extranjeros residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 8,
            "service_place_id" => 2,
            "type_charge" => "TN. GS. Extranjeros no residentes",
            "amount" => "10.00",
        ],
        [
            "id" => 9,
            "service_place_id" => 2,
            "type_charge" => "TN. GS. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 10,
            "service_place_id" => 3,
            "type_charge" => "TN. PS. Salvadoreños",
            "amount" => "1.00",
        ],
        [
            "id" => 11,
            "service_place_id" => 3,
            "type_charge" => "TN. PS. Centroamericanos y extranjeros residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 12,
            "service_place_id" => 3,
            "type_charge" => "TN. PS. Extranjeros no residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 13,
            "service_place_id" => 3,
            "type_charge" => "TN. PS. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 14,
            "service_place_id" => 4,
            "type_charge" => "TSA. GS. Salvadoreños",
            "amount" => "1.00",
        ],
        [
            "id" => 15,
            "service_place_id" => 4,
            "type_charge" => "TSA. GS. Centroamericanos y extranjeros residentes",
            "amount" => "2.00",
        ],
        [
            "id" => 16,
            "service_place_id" => 4,
            "type_charge" => "TSA. GS. Extranjeros no residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 17,
            "service_place_id" => 4,
            "type_charge" => "TSA. GS. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 18,
            "service_place_id" => 4,
            "type_charge" => "TSA. GS. Entrada general de promoción cultural de accesibilidad de la población al Teatro",
            "amount" => "1.00",
        ],
        [
            "id" => 19,
            "service_place_id" => 5,
            "type_charge" => "TNFG. SM. Salvadoreños",
            "amount" => "1.00",
        ],
        [
            "id" => 20,
            "service_place_id" => 5,
            "type_charge" => "TNFG. SM. Centroamericanos y extranjeros residentes",
            "amount" => "2.00",
        ],
        [
            "id" => 21,
            "service_place_id" => 5,
            "type_charge" => "TNFG. SM. Extranjeros no residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 22,
            "service_place_id" => 5,
            "type_charge" => "TNFG. SM. Estudiantes con carné vigente, personas con discapacidad adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 23,
            "service_place_id" => 5,
            "type_charge" => "TNFG. SM. Entrada general de promoción cultural de accesibilidad de la población al Teatro",
            "amount" => "1.00",
        ],
        [
            "id" => 24,
            "service_place_id" => 6,
            "type_charge" => "TN. SS. (con acceso restringido para toma de fotografías) Salvadoreños",
            "amount" => "1.00",//
        ],
        [
            "id" => 25,
            "service_place_id" => 6,
            "type_charge" => "TN. SS. (con acceso restringido para toma de fotografías) Centroamericanos y extranjeros residentes",
            "amount" => "2.00",
        ],
        [
            "id" => 26,
            "service_place_id" => 6,
            "type_charge" => "TN. SS. (con acceso restringido para toma de fotografías) Extranjeros no residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 27,
            "service_place_id" => 6,
            "type_charge" => "TN. SS. (con acceso restringido para toma de fotografías) Estudiantes con carné vigente, personas con discapacidad adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 28,
            "service_place_id" => 6,
            "type_charge" => "TN. SS. (sin acceso restringido para toma de fotografías sin flash) Salvadoreños",
            "amount" => "3.00",
        ],
        [
            "id" => 29,
            "service_place_id" => 6,
            "type_charge" => "TN. SS. (sin acceso restringido para toma de fotografías sin flash) Centroamericanos y extranjeros residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 30,
            "service_place_id" => 6,
            "type_charge" => "TN. SS. (sin acceso restringido para toma de fotografías sin flash) Extranjeros no residentes",
            "amount" => "7.00",
        ],
        [
            "id" => 31,
            "service_place_id" => 6,
            "type_charge" => "TN. SS. (sin acceso restringido para toma de fotografías sin flash) Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "2.00",
        ],
        [
            "id" => 32,
            "service_place_id" => 7,
            "type_charge" => "TN. SA. (con acceso restringido para toma de fotografías) Salvadoreños",
            "amount" => "1.00",//
        ],
        [
            "id" => 33,
            "service_place_id" => 7,
            "type_charge" => "TN. SA. (con acceso restringido para toma de fotografías) Centroamericanos y extranjeros residentes",
            "amount" => "2.00",
        ],
        [
            "id" => 34,
            "service_place_id" => 7,
            "type_charge" => "TN. SA. (con acceso restringido para toma de fotografías) Extranjeros no residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 35,
            "service_place_id" => 7,
            "type_charge" => "TN. SA. (con acceso restringido para toma de fotografías) Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 36,
            "service_place_id" => 7,
            "type_charge" => "TN. SA. (sin acceso restringido para toma de fotografías sin flash) Salvadoreños",
            "amount" => "3.00",
        ],
        [
            "id" => 37,
            "service_place_id" => 7,
            "type_charge" => "TN. SA. (sin acceso restringido para toma de fotografías sin flash) Centroamericanos y extranjeros residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 38,
            "service_place_id" => 7,
            "type_charge" => "TN. SA. (sin acceso restringido para toma de fotografías sin flash) Extranjeros no residentes",
            "amount" => "7.00",
        ],
        [
            "id" => 39,
            "service_place_id" => 7,
            "type_charge" => "TN. SA. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "2.00",
        ],
        [
            "id" => 40,
            "service_place_id" => 8,
            "type_charge" => "PR. ID. Salvadoreños",
            "amount" => "0.75",
        ],
        [
            "id" => 41,
            "service_place_id" => 8,
            "type_charge" => "PR. ID. Centroamericanos y extranjeros residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 42,
            "service_place_id" => 8,
            "type_charge" => "PR. ID. Extranjeros no residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 43,
            "service_place_id" => 8,
            "type_charge" => "PR. ID. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 44,
            "service_place_id" => 9,
            "type_charge" => "PSH. MHN. SV. Salvadoreños",
            "amount" => "1.00",
        ],
        [
            "id" => 45,
            "service_place_id" => 9,
            "type_charge" => "PSH. MHN. SV. Centroamericanos y extranjeros residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 46,
            "service_place_id" => 9,
            "type_charge" => "PSH. MHN. SV. Extranjeros no residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 47,
            "service_place_id" => 9,
            "type_charge" => "PSH. MHN. SV. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 48,
            "service_place_id" => 10,
            "type_charge" => "P. ZN. Salvadoreños",
            "amount" => "1.00",
        ],
        [
            "id" => 49,
            "service_place_id" => 10,
            "type_charge" => "P. ZN. Centroamericanos y extranjeros residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 50,
            "service_place_id" => 10,
            "type_charge" => "P. ZN. Extranjeros no residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 51,
            "service_place_id" => 10,
            "type_charge" => "P. ZN. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 52,
            "service_place_id" => 11,
            "type_charge" => "PNA. DR. DJG. Salvadoreños",
            "amount" => "1.00",
        ],
        [
            "id" => 53,
            "service_place_id" => 11,
            "type_charge" => "PNA. DR. DJG. Centroamericanos y extranjeros residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 54,
            "service_place_id" => 11,
            "type_charge" => "PNA. DR. DJG. Extranjeros no residentes",
            "amount" => "10.00",
        ],
        [
            "id" => 55,
            "service_place_id" => 11,
            "type_charge" => "PNA. DR. DJG. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 56,
            "service_place_id" => 11,
            "type_charge" => "PNA. DR. DJG. Uso de audioguía",
            "amount" => "3.00",
        ],
        [
            "id" => 57,
            "service_place_id" => 12,
            "type_charge" => "MR. ORI. OCI. Salvadoreños",
            "amount" => "1.00",
        ],
        [
            "id" => 58,
            "service_place_id" => 12,
            "type_charge" => "MR. ORI. OCI. Centroamericanos y extranjeros residentes",
            "amount" => "2.00",
        ],
        [
            "id" => 59,
            "service_place_id" => 12,
            "type_charge" => "MR. ORI. OCI. Extranjeros no residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 60,
            "service_place_id" => 12,
            "type_charge" => "MR. ORI. OCI. Estudiantes con carné",
            "amount" => "0.00",
        ],
        [
            "id" => 61,
            "service_place_id" => 13,
            "type_charge" => "PA. SA. JC. TZM. CIHT. Salvadoreños",
            "amount" => "1.00",
        ],
        [
            "id" => 62,
            "service_place_id" => 13,
            "type_charge" => "PA. SA. JC. TZM. CIHT. Centroamericanos y extranjeros residentes",
            "amount" => "3.00",
        ],
        [
            "id" => 63,
            "service_place_id" => 13,
            "type_charge" => "PA. SA. JC. TZM. CIHT. Extranjeros no residentes",
            "amount" => "5.00",
        ],
        [
            "id" => 64,
            "service_place_id" => 13,
            "type_charge" => "PA. SA. JC. TZM. CIHT. Extranjeros no residentes (JOYA DE CERÉN, Patrimonio de la Humanidad)",
            "amount" => "10.00",
        ],
        [
            "id" => 65,
            "service_place_id" => 13,
            "type_charge" => "PA. SA. JC. TZM. CIHT. Extranjeros residentes Joya de Cerén, Patrimonio de la Humanidad",
            "amount" => "7.00",
        ],
        [
            "id" => 66,
            "service_place_id" => 13,
            "type_charge" => "PA. SA. JC. TZM. CIHT. Estudiantes con carné vigente, personas con discapacidad, adultos mayores y niños menores de 12 años",
            "amount" => "0.00",
        ],
        [
            "id" => 67,
            "service_place_id" => 13,
            "type_charge" => "PA. SA. JC. TZM. CIHT. Residentes de los municipios en los cuales se encuentran los parques arqueológicos",
            "amount" => "0.00",
        ],
        ]);
    }
}
