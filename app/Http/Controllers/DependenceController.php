<?php

namespace App\Http\Controllers;

use App\Models\Dependence;
use App\Models\NationalDirection;
use Encrypt;
use Illuminate\Http\Request;

class DependenceController extends Controller
{
    public function index()
    {
        $dependences = Dependence::all();

        foreach ($dependences as $dependence) {
            $dependence->national_direction_name = $dependence->national_directions->national_direction_name;
        }
        $dependences->makeHidden(["national_directions"]);

        $dependences = Encrypt::encryptObject($dependences, "id");

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente.",
            "dependences"=>$dependences]);
    }

    public function store(Request $request)
    {
        $data = $request->except("national_direction_name");
        $national_directions = NationalDirection::where("national_direction_name", $request->national_direction_name)->first();
        $data["national_direction_id"] = $national_directions->id;
        Dependence::insert($data);

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."]);
    }

    public function update(Request $request)
    {
        $national_directions = NationalDirection::where("national_direction_name", $request->national_direction_name)->first();
        $data = Encrypt::decryptArray($request->except(["national_direction_name"]), "id");

        $data["national_direction_id"] = $national_directions->id;

        Dependence::where("id", $data)->update($data);
        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        Dependence::where('id', $id)->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."]);
    }

    public function byDirectionName(Request $request)
    {
        $dependences = Dependence::select("*")
        ->join("national_directions", "dependences.national_direction_id", "=", "national_directions.id")
        ->where("national_directions.national_direction_name", $request->national_directions)
        ->get();

        return response()->json([
            "message" => "success",
            "dependences" => $dependences
        ]);
    }
}