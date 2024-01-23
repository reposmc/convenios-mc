<?php

namespace App\Http\Controllers;

use App\Models\TypeInstrument;
use Encrypt;
use Illuminate\Http\Request;

class TypeInstrumentController extends Controller
{
    public function index()
    {
        $types = TypeInstrument::select('type_instruments.*')
                ->orderBy('type_instrument_name', 'asc')
                ->get();

        $types = Encrypt::encryptObject($types, 'id');

        return response()->json([
            "status"=>"success",
            "message"=>"Registros obtenidos correctamente.",
            'types'=>$types
        ]);
    }

    public function store(Request $request)
    {
        TypeInstrument::insert($request->all());

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        TypeInstrument::where('id', $data)->update($data);
        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        $typeInstrument = TypeInstrument::with("instruments")->where('id', $id)->first();

        if(sizeof($typeInstrument->instruments) > 0){
            /* abort(403, "No se puede eliminar este registro porque ya ha sido utilizado."); */
            return response()->json([
                "status"=>"fail",
                "message"=>"El registro no puede eliminarse porque ya ha sido utilizado."
            ]);
        }

        $typeInstrument->delete();

        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."
        ]);
    }
}
