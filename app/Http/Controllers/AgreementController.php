<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\TypeAgreement;
use App\Models\Entity;
use App\Models\Exoneration;
use App\Models\Dependence;
use App\Models\ServicePlace;
use App\Models\ExonerationDetail;
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
            $agreement->type_agreement_name = TypeAgreement::find($agreement->type_agreement_id)->type_agreement_name;
            //$agreement->place_name = ServicePlace::find(ExonerationDetail::find($agreement->id)->service_place_id)->place_name;
            $agreement->entity_name = Entity::find($agreement->entity_id)->entity_name;
            //$agreement->hour = ExonerationDetail::find($agreement->id)->hour;
            //$agreement->people = ExonerationDetail::find($agreement->id)->people;
            //$agreement->date = ExonerationDetail::find($agreement->id)->date;
            //$agreement->exonerated_amount = ExonerationDetail::find($agreement->id)->exonerated_amount;
        }

        $agreements = Encrypt::encryptObject($agreements, "id");

        return response()->json([
            "status"=>"success", 
            "message"=>"Registro cargado correctamente.", 
            "agreements"=>$agreements]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->national_direction_name == null){
            //dd($request);
            Agreement::create([
                'agreement_name' => $request->agreement_name,
                'description' => $request->description,
                'type_agreement_id' => TypeAgreement::where("type_agreement_name", $request->type_agreement_name)->first()->id,
                'entity_id' => Entity::where("entity_name", $request->entity_name)->first()->id,
            ]);

        }else{ 
            //dd($agreement);
            Exoneration::create([
                'exonerated_description' => $request->exonerated_description,
                'agreement_id' => Agreement::where("agreement_name", $request->agreement_name)->first()->id,
                'dependence_id' => Dependence::where("dependence_name", $request->dependence_name)->first()->id,
            ]);

            $exoneration = DB::table('exonerations')->latest('id')->first();
            $place_id = ServicePlace::where("place_name", $request->place_name)->first()->id;

            if($request->type_charge == null || $request->type_charge == ""){
                $tariff_id = null;
                $not_charged = $request->not_charged;
            }else{
                $tariff_id = Tariff::where("type_charge", $request->type_charge)->first()->id;
                $not_charged = null;
            }

            ExonerationDetail::create([
                'hour'=> $request->hour,
                'people'=> $request->people,
                'date'=> $request->date,
                'exonerated_amount' => $request->exonerated_amount,
                'exoneration_id' => $exoneration->id,
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
        $type_agreement = Entity::where("type_agreement_name", $request->type_agreement_name)->first();
        $data = Encrypt::decryptArray($request->except(["entity_name", "type_agreement_name"]), 'id');

        $data["type_agreement_id"] = $type_agreement->id;
        $data["entity_name"] = $entity->id;
        
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
