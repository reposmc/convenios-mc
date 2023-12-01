<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entity;
use Encrypt;

class EntityController extends Controller
{
    public function index()
    {
        $entities = Entity::select('entities.*')
                    ->orderBy('entity_name', 'asc')
                    ->get();
        $entities = Encrypt::encryptObject($entities, 'id');

        return response()->json([
            "status"=>"success",
            "message"=>"Registros obtenidos correctamente.",
            'entities'=>$entities
        ]);
    }

    public function store(Request $request)
    {
        Entity::insert($request->all());

        return response()->json([
            "status"=>"success",
            "message"=>"Registro creado correctamente."
        ]);
    }

    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        Entity::where('id', $data)->update($data);
        return response()->json([
            "status"=>"success",
            "message"=>"Registro modificado correctamente."
        ]);
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        Entity::where("id", $id)->delete();
        return response()->json(["status"=>"success", "message"=>"Registro eliminado correctamente."]);
    }
}
