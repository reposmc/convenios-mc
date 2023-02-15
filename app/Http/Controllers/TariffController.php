<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use App\Models\ServicePlace;
use Encrypt;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    public function index()
    {
        $tariffs = Tariff::all();

        foreach ($tariffs as $tariff) {
            $tariff->place_name = $tariff->places->place_name;
        }
        $tariffs->makeHidden(["places"]);

        $tariffs = Encrypt::encryptObject($tariffs, "id");

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente.",
            "tariffs"=>$tariffs]);
    }

    public function store(Request $request)
    {
        $data = $request->except("place_name");
        $places = ServicePlace::where("place_name", $request->place_name)->first();
        $data["service_place_id"] = $places->id;
        Tariff::insert($data);

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."]);
    }

    public function update(Request $request)
    {
        $places = ServicePlace::where("place_name", $request->place_name)->first();
        $data = Encrypt::decryptArray($request->except(["place_name"]), "id");

        $data["service_place_id"] = $places->id;

        tariff::where("id", $data)->update($data);
        return response()->json([
            "status"=>"success",
            "message"=>"Registro editado correctamente."]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        Tariff::where('id', $id)->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."
        ]);
    }

    public function byPlaceName(Request $request)
    {
        $tariffs = Tariff::select("*")
        ->join("service_places", "tariffs.service_place_id", "=", "service_places.id")
        ->where("service_places.place_name", $request->service_places)
        ->get();

        return response()->json([
            "message" => "success",
            "tariffs" => $tariffs
        ]);
    }
}
