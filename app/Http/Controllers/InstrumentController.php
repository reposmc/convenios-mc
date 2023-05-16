<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instrument;
use App\Models\TypeInstrument;
use App\Models\Entity;
use App\Models\Sector;
use App\Models\InstrumentsDependeciesDetail;
use App\Models\UsersDependenciesDetail;
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
        if($request->itemsPerPage == null || $request->itemsPerPage == ''){
            $instruments = Instrument::all();

            foreach ($instruments as $instrument)
            {
                $instrument->sector_name = Sector::find($instrument->sector_id)->sector_name;
                $instrument->entity_name = Entity::find($instrument->entity_id)->entity_name;
                $instrument->type_instrument_name = TypeInstrument::find($instrument->type_instrument_id)->type_instrument_name;
            }

            $instruments = Encrypt::encryptObject($instruments, "id");
            
            return response()->json([
                "status"=>"success", 
                "message"=>"Registro obtenido correctamente.", 
                "instruments"=>$instruments]);
        }
        else{
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
                    
                /* $item->assignedExonerations = Exoneration::select(
                    'exonerations.date_event',
                    'exonerations.service_place_name',
                    DB::raw('CASE WHEN exonerations.is_tariffed = 1 THEN "Sí" ELSE "No" END AS is_tariffed'),
                    'exonerations.concept',
                    'exonerations.quantity',
                    'exonerations.exonerated_description',
                    'exonerations.estimated_price',
                    'exonerations.tariff_type_charge',
                    'exonerations.non_tariff_concept',
                    'exonerations.tariff_amount',
                    'exonerations.non_tariff_amount',
                    'exonerations.number_hour',
                    'exonerations.number_people',
                    'exonerations.total_amount',
                )
                    ->join('exonerations as e', 'exonerations.instrument_id', '=', 'e.id')
                    //->join('tariffs as t', 'exonerations.tariff_type_charge', '=', 't.id')
                    ->where('exonerations.instrument_id', $item->id)->get(); */
            }
            //dd($item);

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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            "status" => "success",
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
    public function update(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);
        $instruments = Instrument::find($id);

        $instruments->type_instrument_id = TypeInstrument::where('type_instrument_name', $request->type_instrument_name)->first()->id;
        $instruments->instrument_name = $request->instrument_name;
        $instruments->sector_id = Sector::where('sector_name', $request->sector_name)->first()->id;
        $instruments->entity_id = Entity::where('entity_name', $request->entity_name)->first()->id;
        $instruments->description = $request->description;

        $instruments->save();

        InstrumentsDependeciesDetail::where('instrument_id', $instruments->id)->delete();
        foreach ($request->assignedDependencies as $key => $dependence_name) {

            $dependency = Dependence::where(['dependence_name' => $dependence_name])->first();

            InstrumentsDependeciesDetail::create([
                'instrument_id' => $instruments->id,
                'dependency_id' => $dependency->id,
            ]);
        }

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
