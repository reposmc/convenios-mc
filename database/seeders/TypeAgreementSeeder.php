<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\TypeAgreement;

class TypeAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeAgreement::insert([
        [
            "id" => 1,
            "type_agreement_name" => "Marcos",
        ],
        [
            "id" => 2,
            "type_agreement_name" => "Específicos",
        ],    
    ]);
    }
}
