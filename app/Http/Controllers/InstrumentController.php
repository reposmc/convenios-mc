<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instrument;
use App\Models\TypeInstrument;
use App\Models\Entity;
use App\Models\Sector;
use App\Models\InstrumentsDependeciesDetail;
use App\Models\Exoneration;
use App\Models\Dependence;
use App\Models\ServicePlace;
use App\Models\Tariff;
use Encrypt;
use DB;

class InstrumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        $skip = ($request->page - 1) * $request->itemsPerPage;

        // Getting all the records
        if (($request->itemsPerPage == -1)) {
            $itemsPerPage =  Instrument::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $instruments = Instrument::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);

        foreach ($instruments as $item) {
            $item->assignedDependencies = InstrumentsDependeciesDetail::select(
                'instruments_dependecies_detail.*',
                'd.dependence_name'
            )
                ->join('dependences as d', 'instruments_dependecies_detail.dependency_id', '=', 'd.id')
                ->where('instrument_id', $item->id)->get()->pluck('dependence_name');

            $item->assignedExonerations = [];
        }

        $instruments = Encrypt::encryptObject($instruments, "id");

        $total = Instrument::counterPagination($search);

        return response()->json([
            "status" => 200,
            "message" => "Registros obtenidos correctamente.",
            "records" => $instruments,
            "total" => $total,
            "success" => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $instruments = new Instrument;

        $instruments->type_instrument_id = TypeInstrument::where('type_instrument_name', $request->type_instrument_name)->first()->id;
        $instruments->instrument_name = $request->instrument_name;
        $instruments->description = $request->description;
        $instruments->sector_id = Sector::where('sector_name', $request->sector_name)->first()->id;
        $instruments->entity_id = Entity::where('entity_name', $request->entity_name)->first()->id;

        $instruments->save();

        foreach ($request->assignedDependencies as $key => $dependence_name) {

            $dependency = Dependence::where(['dependence_name' => $dependence_name])->first();

            InstrumentsDependeciesDetail::create([
                'instrument_id' => $instruments->id,
                'dependency_id' => $dependency->id,
            ]);
        }

        return response()->json([
            "status" => 200,
            "message" => "Registro creado correctamente.",
            "success" => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $type = TypeInstrument::where("type_instrument_name", $request->type_instrument_name)->first();
        $sector = Sector::where("sector_name", $request->sector_name)->first();
        $entity = Entity::where("entity_name", $request->entity_name)->first();

        $data = Encrypt::decryptArray($request->except(["type_instrument_name", "sector_name", "exonerations"]), "id");

        $data["type_instrument_id"] = $type->id;
        $data["sector_id"] = $sector->id;
        $data["entity_id"] = $entity->id;

        Instrument::where("id", $data)->update($data);

        return response()->json([
            "status" => "success",
            "message" => "Registro modificado correctamente."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
