<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use Encrypt;

class SectorController extends Controller
{
    public function index()
    {
        $sectors = Sector::select('sectors.*')
                    ->orderBy('sector_name', 'asc')
                    ->get();

        $sectors = Encrypt::encryptObject($sectors, 'id');

        return response()->json([
            "status"=>"success",
            "message"=>"Registros obtenidos correctamente.",
            'sectors'=>$sectors
        ]);
    }

    public function store(Request $request)
    {
        Sector::insert($request->all());

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        Sector::where('id', $data)->update($data);
        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        $sector = Sector::with("instruments")->where('id', $id)->first();
        
        if(sizeof($sector->instruments) > 0){
            /* abort(403, "No se puede eliminar este registro porque ya ha sido utilizado."); */
            return response()->json([
                "status"=>"fail",
                "message"=>"El registro no puede eliminarse porque ya ha sido utilizado."
            ]);
        }

        $sector->delete();

        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."
        ]);
    }
}
