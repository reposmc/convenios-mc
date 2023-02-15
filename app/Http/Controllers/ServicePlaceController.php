<?php

namespace App\Http\Controllers;

use App\Models\ServicePlace;
use Encrypt;
use Illuminate\Http\Request;

class ServicePlaceController extends Controller
{
    public function index()
    {
        $places = ServicePlace::all();
        $places = Encrypt::encryptObject($places, 'id');

        return response()->json([
            "status"=>"success",
            "message"=>"Registros obtenidos correctamente.",
            'places'=>$places
        ]);
    }

    public function store(Request $request)
    {
        ServicePlace::insert($request->all());

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        ServicePlace::where('id', $data)->update($data);
        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        ServicePlace::where('id', $id)->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."
        ]);
    }
}
