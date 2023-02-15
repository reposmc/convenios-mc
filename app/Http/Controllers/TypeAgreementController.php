<?php

namespace App\Http\Controllers;

use App\Models\TypeAgreement;
use Encrypt;
use Illuminate\Http\Request;

class TypeAgreementController extends Controller
{
    public function index()
    {
        $types = TypeAgreement::all();
        $types = Encrypt::encryptObject($types, 'id');

        return response()->json([
            "status"=>"success",
            "message"=>"Registros obtenidos correctamente.",
            'types'=>$types
        ]);
    }

    public function store(Request $request)
    {
        TypeAgreement::insert($request->all());

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        TypeAgreement::where('id', $data)->update($data);
        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        TypeAgreement::where('id', $id)->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."
        ]);
    }
}
