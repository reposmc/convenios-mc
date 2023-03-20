<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicePlace;
use App\Models\Exoneration;
use App\Models\Agreement;
use App\Models\Dependence;
use App\Models\Tariff;
use Encrypt;
use DB;

class ExonerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        $agreement_id = Agreement::where("agreement_name", $request->agreement_name)->first()->id;
        $dependence_id = Dependence::where("dependence_name", $request->dependence_name)->first()->id;
        $place_id = ServicePlace::where("place_name", $request->place_name)->first()->id;

        $exonerations = $request->exonerations;

        foreach ($exonerations as $exoneration) {
            $tariff_id = null;
            $not_charged = null;
            if ($exoneration["type_charge"] != null && $exoneration["type_charge"] != "") {
                $tariff_id = Tariff::where("type_charge", $exoneration["type_charge"])->first()->id;
            } else {
                $not_charged = $exoneration["not_charged"];
            }

            Exoneration::create([
                'exonerated_description' => $exoneration["exonerated_description"],
                'agreement_id' => $agreement_id,
                'dependence_id' => $dependence_id,
                'hour'=> $exoneration["hour"],
                'people'=> $exoneration["people"],
                'date'=> $exoneration["date"],
                'exonerated_amount' => $exoneration["exonerated_amount"],
                'service_place_id' => $place_id,
                'tariff_id' => $tariff_id,
                'not_charged' => $not_charged,
            ]);
    }

    return response()->json([
        "status" => "success",
        "message" => "Registros creados correctamente."
    ]);
    }

    public function show($agreement_id)
    {
       //
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        Exoneration::where("id", $id)->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."
        ]);
    }
}
