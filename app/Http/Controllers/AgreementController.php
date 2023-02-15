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
        if($request->national_direction_name == null){
            Agreement::create([
                'agreement_name' => $request->agreement_name,
                'description' => $request->description,
                'type_agreement_id' => TypeAgreement::where("type_agreement_name", $request->type_agreement_name)->first()->id,
                'entity_id' => Entity::where("entity_name", $request->entity_name)->first()->id,
            ]);

        }else{

            $place_id = ServicePlace::where("place_name", $request->place_name)->first()->id;

            if($request->type_charge == null || $request->type_charge == ""){
                $tariff_id = null;
                $not_charged = $request->not_charged;
            }else{
                $tariff_id = Tariff::where("type_charge", $request->type_charge)->first()->id;
                $not_charged = null;
            }

            Exoneration::create([
                'exonerated_description' => $request->exonerated_description,
                'agreement_id' => Agreement::where("agreement_name", $request->agreement_name)->first()->id,
                'dependence_id' => Dependence::where("dependence_name", $request->dependence_name)->first()->id,
                'hour'=> $request->hour,
                'people'=> $request->people,
                'date'=> $request->date,
                'exonerated_amount' => $request->exonerated_amount,
                'service_place_id' => $place_id,
                'tariff_id' => $tariff_id,
                'not_charged' => $not_charged,
            ]);
        }

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $entity = Entity::where("entity_name", $request->entity_name)->first();
        $type = TypeAgreement::where("type_agreement_name", $request->type_agreement_name)->first();

        $data = Encrypt::decryptArray($request->except(["entity_name", "type_agreement_name"]), "id");

        $data["entity_id"] = $entity->id;
        $data["type_agreement_id"] = $type->id;
        
        Agreement::where('id', $data)->update($data);
        
        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        Agreement::where("id", $id)->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."]);
    }
}
