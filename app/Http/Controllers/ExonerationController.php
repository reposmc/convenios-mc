<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicePlace;
use App\Models\Exoneration;
use App\Models\Instrument;
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
        $instrument_id = Instrument::where("instrument_name", $request->instrument_name)->first()->id;

        $exonerations = $request->assignedExonerations;

        foreach($exonerations as $exoneration){

            $is_tariffed_response = $exoneration['is_tariffed'];

            if($is_tariffed_response == "Sí"){
                $is_tariffed = 1;
            }else{
                $is_tariffed = 0;
            }
            
            Exoneration::create([
                'exonerated_description' => $exoneration['exonerated_description'],
                'instrument_id' => $instrument_id,
                'service_place_name' => $exoneration["service_place_name"],
                //'dependence_id' => $dependence_id,
                'number_hour'=> $exoneration["number_hour"],
                'number_people'=> $exoneration["number_people"],
                'non_tariff_concept' => $exoneration["non_tariff_concept"],
                'non_tariff_amount' => $exoneration["non_tariff_amount"],
                'date_event'=> $exoneration["date_event"],
                'is_tariffed' => $is_tariffed,
                'tariff_amount' => $exoneration["tariff_amount"],
                'total_amount' => $exoneration["total_amount"],
                //'total_value' => $exoneration["total_value"],
                'concept' => $exoneration["concept"],
                'quantity' => $exoneration["quantity"],
                'estimated_price' => $exoneration["estimated_price"], 
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