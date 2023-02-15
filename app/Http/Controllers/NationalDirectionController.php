<?php

namespace App\Http\Controllers;

use App\Models\NationalDirection;
use Encrypt;
use Illuminate\Http\Request;

class NationalDirectionController extends Controller
{
    public function index()
    {
        $directions = NationalDirection::all();
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

        NationalDirection::where('id', $id)->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."
        ]);
    }
}
