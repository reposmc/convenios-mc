<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exoneration;
use App\Models\Instrument;
use Encrypt;

class ExonerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $instrument_id = Encrypt::decryptValue($request->id);

        //Delete existing records
        Exoneration::where('instrument_id', $instrument_id)->delete();

        $exonerations = $request->assignedExonerations;

        foreach ($exonerations as $exoneration) {

            Exoneration::create([
                'exonerated_description' => $exoneration['exonerated_description'],
                'instrument_id' => $instrument_id,
                'service_place_name' => $exoneration["service_place_name"],
                'number_hour' => $exoneration["number_hour"],
                'number_people' => $exoneration["number_people"],
                'non_tariff_concept' => $exoneration["non_tariff_concept"],
                'tariff_type_charge' => $exoneration["tariff_type_charge"],
                'non_tariff_amount' => !empty($exoneration["non_tariff_amount"]) ? (float)$exoneration["non_tariff_amount"] : null, //
                'date_event' => $exoneration["date_event"],
                'is_tariffed' => ($exoneration["is_tariffed"] === "Sí") ? 0 : (!empty($exoneration["is_tariffed"]) ? 1 : null),
                'tariff_amount' => !empty($exoneration["tariff_amount"]) ? (float)$exoneration["tariff_amount"] : null, //
                'total_amount' => $exoneration["total_amount"],
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        Exoneration::where("id", $id)->delete();
        return response()->json([
            "status" => "success",
            "message" => "Registro eliminado correctamente."
        ]);
    }
}
