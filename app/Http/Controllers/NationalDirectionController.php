<?php

namespace App\Http\Controllers;

use App\Models\NationalDirection;
use Encrypt;
use Illuminate\Http\Request;

class NationalDirectionController extends Controller
{
    public function index()
    {
        $directions = NationalDirection::select("national_directions.*")
                        ->orderBy('national_direction_name', 'asc')
                        ->get();
                        
        $directions = Encrypt::encryptObject($directions, 'id');

        return response()->json([
            "status"=>"success",
            "message"=>"Registros obtenidos correctamente.",
            'directions'=>$directions
        ]);
    }

    public function store(Request $request)
    {
        NationalDirection::insert($request->all());

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        NationalDirection::where('id', $data)->update($data);
        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);
        
        $nationalDirection = NationalDirection::with("dependence")->where('id', $id)->first();

        if(sizeof($nationalDirection->dependence) > 0) {
            /* abort(403, "No se puede eliminar este registro porque ya ha sido utilizado."); */
            return response()->json([
                "status"=>"fail",
                "message"=>"El registro no puede eliminarse porque ya ha sido utilizado."
            ]);
        }

        if(sizeof($nationalDirection->instruments) > 0){
            /* abort(403, "No se puede eliminar este registro porque ya ha sido utilizado."); */
            return response()->json([
                "status"=>"fail",
                "message"=>"El registro no puede eliminarse porque ya ha sido utilizado."
            ]);
        }

        $nationalDirection->delete();

        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."
        ]);
    }
}