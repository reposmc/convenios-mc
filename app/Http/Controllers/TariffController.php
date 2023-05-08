<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use App\Models\Dependence;
use Encrypt;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    public function index()
    {
        $tariffs = Tariff::all();

        foreach ($tariffs as $tariff) {
            $tariff->dependence_name = $tariff->dependences->dependence_name;
        }
        $tariffs->makeHidden(["dependences"]);

        $tariffs = Encrypt::encryptObject($tariffs, "id");

        return response()->json([
            "status" => "success",
            "message" => "Registro creado correctamente.",
            "tariffs" => $tariffs
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except("dependence_name");
        $dependences = Dependence::where("dependence_name", $request->dependence_name)->first();
        $data["dependence_id"] = $dependences->id;
        Tariff::insert($data);

        return response()->json([
            "status" => "success",
            "message" => "Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $dependences = Dependence::where("dependence_name", $request->dependence_name)->first();
        $data = Encrypt::decryptArray($request->except(["dependence_name"]), "id");

        $data["dependence_id"] = $dependences->id;

        tariff::where("id", $data)->update($data);
        return response()->json([
            "status" => "success",
            "message" => "Registro editado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        Tariff::where('id', $id)->delete();
        return response()->json([
            "status" => "success",
            "message" => "Registro eliminado correctamente."
        ]);
    }

    /**
     * Get tariff amount by tariff type charge
     *
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Http\Response
     */
    public function byDependencyName(Request $request)
    {
        $tariffs = Tariff::select("*")
            ->join("dependences", "tariffs.dependence_id", "=", "dependences.id")
            ->where("dependences.dependence_name", $request->dependencies)
            ->get();

        return response()->json([
            "message" => "success",
            "tariffs" => $tariffs
        ]);
    }

    /**
     * Get tariff amount by tariff type charge
     *
     * @param  \App\Models\Dependence  $dependence
     * @return \Illuminate\Http\Response
     */
    public function byTariffTypeCharge(Request $request)
    {
        $tariffAmount = Tariff::select('tariffs.amount')
            ->where('type_charge', $request->tariffTypeCharge)
            ->first();

        // dd($tariffAmount);

        return response()->json(['message' => 'success', 'tariff_amount' => $tariffAmount->amount]);
    }
}
