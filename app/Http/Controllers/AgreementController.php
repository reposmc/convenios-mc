<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\TypeAgreement;
use App\Models\Entity;
use App\Models\Exoneration;
use App\Models\Dependence;
use App\Models\ServicePlace;
use App\Models\Tariff;
use Encrypt;
use Illuminate\Http\Request;
use DB;

class AgreementController extends Controller
{
    public function index()
    {
        $agreements = Agreement::all();

        foreach ($agreements as $agreement)
        {
            $agreement->exonerations = Exoneration::select('exonerations.*', 'service_places.place_name', DB::raw('IFNULL(tariffs.amount, exonerations.not_charged) AS charge'))
            ->leftJoin('service_places', 'exonerations.service_place_id', '=', 'service_places.id')
            ->leftJoin('tariffs', 'exonerations.tariff_id', '=', 'tariffs.id')
            ->where('exonerations.agreement_id', $agreement->id)
            ->get();
            $agreement->entity_name = Entity::find($agreement->entity_id)->entity_name;
            $agreement->type_agreement_name = TypeAgreement::find($agreement->type_agreement_id)->type_agreement_name;
        }

        $agreements = Encrypt::encryptObject($agreements, "id");
        
        return response()->json([
            "status"=>"success", 
            "message"=>"Registro obtenido correctamente.", 
            "agreements"=>$agreements]);
    }


    public function store(Request $request)
    {
        Agreement::create([
            'agreement_name' => $request->agreement_name,
            'description' => $request->description,
            'type_agreement_id' => TypeAgreement::where("type_agreement_name", $request->type_agreement_name)->first()->id,
            'entity_id' => Entity::where("entity_name", $request->entity_name)->first()->id,
        ]);

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $type = TypeAgreement::where("type_agreement_name", $request->type_agreement_name)->first();
        $entity = Entity::where("entity_name", $request->entity_name)->first();

        $data = Encrypt::decryptArray($request->except(["entity_name", "type_agreement_name", "exonerations"]), "id");

        $data["type_agreement_id"] = $type->id;
        $data["entity_id"] = $entity->id;
        
        Agreement::where("id", $data)->update($data);

        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        //
    }
}
