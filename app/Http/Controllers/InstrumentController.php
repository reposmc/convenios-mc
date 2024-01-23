<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instrument;
use App\Models\Archivo;
use App\Models\Prorroga;
use App\Models\TypeInstrument;
use App\Models\Entity;
use App\Models\Sector;
use App\Models\InstrumentsDependeciesDetail;
use App\Models\UsersDependenciesDetail;
use App\Models\Exoneration;
use App\Models\Dependence;
use App\Models\NationalDirection;
use Encrypt;
use DB;
use Str;

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
    if ($request->itemsPerPage == -1) {
        $itemsPerPage =  Instrument::count();
        $skip = 0;
    }
    
    $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
    $sort = (isset($request->sortDesc[0])) ? "asc" : "desc";

    $search = (isset($request->search)) ? "%$request->search%" : '%%';

    if (auth()->user()->hasRole('Usuario')) {
        
        $filter = $request->filter;

        // User with dependencies
        $userDependencies = UsersDependenciesDetail::where('user_id', auth()->user()->id)
            ->pluck('dependency_id')
            ->toArray();

        $instruments = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'entities.entity_name', 'sectors.sector_name', 'national_directions.national_direction_name')
            ->join('instruments_dependecies_detail', 'instruments.id', '=', 'instruments_dependecies_detail.instrument_id')
            ->join('dependences', 'instruments_dependecies_detail.dependency_id', '=', 'dependences.id')
            ->whereIn('dependences.id', $userDependencies)
            ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
            ->join('entities', 'instruments.entity_id', '=', 'entities.id')
            ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
            ->join('national_directions', 'instruments.direction_id', '=', 'national_directions.id') 
            ->where(function ($query) use ($search) {
                $query->where('instruments.instrument_name', 'LIKE', $search)
                    ->orWhere('instruments.description', 'LIKE', $search);
            })
            ->where('instruments.state', $filter)
            ->orderBy($sortBy, $sort)
            ->skip($skip)
            ->take($itemsPerPage)
            ->get();
            
        // Load exonerations for each instrument
        foreach ($instruments as $item) {
            $item->assignedDependencies = InstrumentsDependeciesDetail::select(
                'instruments_dependecies_detail.*',
                'd.dependence_name'
            )
                ->join('dependences as d', 'instruments_dependecies_detail.dependency_id', '=', 'd.id')
                ->where('instrument_id', $item->instrument_id)->get()->pluck('dependence_name');
            
            $item->archivo = Archivo::select('archivos.*')
                                ->where('instrument_id', $item->instrument_id)->get();
            
            $item->archivo = $item->archivo ? $item->archivo->map(function ($archivo) {
                return [
                    "url" => $archivo->documento,
                    "id" => Str::uuid()
                ];
                }) : [];

                $item->prorroga = Prorroga::select('prorrogas.*')
                        ->where('instrument_id', $item->instrument_id)->get();

                $item->prorroga = $item->prorroga ? $item->prorroga->map(function ($prorroga) {
                return [
                    "url" => $prorroga->documento,
                    "nombre" => $prorroga->nombre,
                    "id" => Str::uuid()
                ];
                }) : [];

            foreach ($instruments as $item) {
                $item->assignedExonerations = Exoneration::select('exonerations.*')
                    ->where('instrument_id', $item->id)
                    ->get();

                foreach ($item->assignedExonerations as $value) {
                    if (isset($value->is_tariffed) && $value->is_tariffed == 0) {
                        $value->is_tariffed = "Sí";
                    } elseif (isset($value->is_tariffed) && $value->is_tariffed == 1) {
                        $value->is_tariffed = "No";
                    }
                }
            }
        }

    } else {
        $filter = $request->filter;
        // All records
        $instruments = Instrument::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage, $filter);

        // Load exonerations for each instrument
        foreach ($instruments as $item) {
            $item->assignedDependencies = InstrumentsDependeciesDetail::select(
                'instruments_dependecies_detail.*',
                'd.dependence_name'
            )
                ->join('dependences as d', 'instruments_dependecies_detail.dependency_id', '=', 'd.id')
                ->where('instrument_id', $item->instrument_id)->get()->pluck('dependence_name');

            $item->archivo = Archivo::select('archivos.*')
                                ->where('instrument_id', $item->instrument_id)->get();
            
            $item->archivo = $item->archivo ? $item->archivo->map(function ($archivo) {
                return [
                   "url" => $archivo->documento,
                   "id" => Str::uuid()
                ];
             }) : [];

             $item->prorroga = Prorroga::select('prorrogas.*')
                        ->where('instrument_id', $item->instrument_id)->get();

                $item->prorroga = $item->prorroga ? $item->prorroga->map(function ($prorroga) {
                return [
                    "url" => $prorroga->documento,
                    "nombre" => $prorroga->nombre,
                    "id" => Str::uuid()
                ];
                }) : [];

            foreach ($instruments as $item) {
                $item->assignedExonerations = Exoneration::select('exonerations.*')
                    ->where('instrument_id', $item->id)
                    ->get();

                foreach ($item->assignedExonerations as $value) {
                    if (isset($value->is_tariffed) && $value->is_tariffed == 0) {
                        $value->is_tariffed = "Sí";
                    } elseif (isset($value->is_tariffed) && $value->is_tariffed == 1) {
                        $value->is_tariffed = "No";
                    }
                }
            }
        }
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

    public function getInstrument(Request $request){
        
        $idInstrument = Encrypt::decryptValue($request->id);

        $instruments = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'entities.entity_name', 'sectors.sector_name', 'national_directions.national_direction_name')
            ->join('instruments_dependecies_detail', 'instruments.id', '=', 'instruments_dependecies_detail.instrument_id')
            ->join('dependences', 'instruments_dependecies_detail.dependency_id', '=', 'dependences.id')
            ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
            ->join('entities', 'instruments.entity_id', '=', 'entities.id')
            ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
            ->join('national_directions', 'instruments.direction_id', '=', 'national_directions.id')
            ->where('instruments.id', $idInstrument)
            ->distinct() 
            ->get();
        
            // Load exonerations for each instrument

            foreach ($instruments as $item) {
                $item->assignedExonerations = Exoneration::select('exonerations.*')
                    ->where('instrument_id', $item->id)
                    ->get();
                
                    $item->archivo = Archivo::select('archivos.*')
                    ->where('instrument_id', $item->id)->get();

                    $item->archivo = $item->archivo ? $item->archivo->map(function ($archivo) {
                        return [
                        "url" => $archivo->documento,
                        "nombre" => $archivo->nombre,
                        "id" => Str::uuid()
                        ];
                    }) : [];

                    $item->prorroga = Prorroga::select('prorrogas.*')
                                ->where('instrument_id', $item->id)->get();

                        $item->prorroga = $item->prorroga ? $item->prorroga->map(function ($prorroga) {
                        return [
                            "url" => $prorroga->documento,
                            "nombre" => $prorroga->nombre,
                            "id" => Str::uuid()
                        ];
                        }) : [];

                foreach ($item->assignedExonerations as $value) {
                    if (isset($value->is_tariffed) && $value->is_tariffed == 0) {
                        $value->is_tariffed = "Sí";
                    } elseif (isset($value->is_tariffed) && $value->is_tariffed == 1) {
                        $value->is_tariffed = "No";
                    }
                    $item->total += $value->total_amount;
                }
            }
           /* dd($instruments); */ 
        $instruments = Encrypt::encryptObject($instruments, "id");

        return $instruments;
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
        $instruments->date = $request->date;
        $instruments->dateStart = $request->dateStart;
        $instruments->dateFinish = $request->dateFinish;
        $instruments->state = Instrument::VIGENTE;
        $instruments->sector_id = Sector::where('sector_name', $request->sector_name)->first()->id;
        $instruments->entity_id = Entity::where('entity_name', $request->entity_name)->first()->id;
        $instruments->direction_id = NationalDirection::where('national_direction_name', $request->national_direction_name)->first()->id;

        $instruments->save();

        foreach ($request->assignedDependencies as $key => $dependence_name) {

            $dependency = Dependence::where(['dependence_name' => $dependence_name])->first();

            InstrumentsDependeciesDetail::create([
                'instrument_id' => $instruments->id,
                'dependency_id' => $dependency->id,
            ]);
        }

        $file = request()->archivo;
        $nom_archivo = request()->nom_archivo;
        $nom_archivo = substr($nom_archivo,0,-4);

         if ($file != null) {
            if (substr($file, 0, 20) == "data:application/pdf") {

              $archivo = FileController::base64ToFile($file, $nom_archivo . '-' . date("Y-m-d"), "Comprobantes");

               $file = $archivo; 

               $portfolio = Archivo::create([
                  'documento' => $file,
                  'nombre' => $nom_archivo,
                  'instrument_id' =>  $instruments->id,
               ]);

               $portfolio->save();
            }
         }

        return response()->json([
            "status" => "success",
            "message" => "Registro creado correctamente.",
            "success" => true,
        ]);
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
        if($request->state == Instrument::PRORROGA){
            
            $id = Encrypt::decryptValue($request->id);
            $instruments = Instrument::find($id);

            $instruments->type_instrument_id = TypeInstrument::where('type_instrument_name', $request->type_instrument_name)->first()->id;
            $instruments->instrument_name = $request->instrument_name;
            $instruments->date = $request->date;
            $instruments->dateStart = $request->dateStart;
            $instruments->dateFinish = $request->dateFinish;
            $instruments->state = Instrument::PRORROGA;
            $instruments->sector_id = Sector::where('sector_name', $request->sector_name)->first()->id;
            $instruments->entity_id = Entity::where('entity_name', $request->entity_name)->first()->id;
            $instruments->direction_id = NationalDirection::where('national_direction_name', $request->national_direction_name)->first()->id;
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
      
            //DOCUMENTO OFICIAL ACTUALIZADO
            $file = request()->archivo;
            $nom_archivo = request()->nom_archivo;
            $nom_archivo = substr($nom_archivo,0,-4);

            $bdFile = Archivo::select('archivos.documento')->where('instrument_id', $instruments->id)->get();
            $idArchivo = Archivo::select('archivos.instrument_id')->where('instrument_id', $instruments->id)->first();
            
            if ($idArchivo == null) {
                if ($file != null) {
                    if (substr($file, 0, 20) == "data:application/pdf") {
        
                      $archivo = FileController::base64ToFile($file, $nom_archivo . '-' . date("Y-m-d"), "Comprobantes");
        
                       $file = $archivo; 
        
                       $portfolio = Archivo::create([
                          'documento' => $file,
                          'nombre' => $nom_archivo,
                          'instrument_id' =>  $instruments->id,
                       ]);
        
                       $portfolio->save();
                    }
                 }
            }else if(is_array($file) == 1){
                if($file[0]['url'] === $bdFile[0]['documento'] && $request->nom_archivo == '' || $request->nom_archivo == null){

                    Archivo::where('instrument_id', $instruments->id)->update([
                        'nombre' => $bdFile[0]['nombre'],
                        'documento' => $bdFile[0]['documento'],
                        'instrument_id' =>  $instruments->id,
                    ]);
                }
            }else {
                if ($file != null) {
                    if (substr($file, 0, 20) == "data:application/pdf") {
    
                        $archivo = FileController::base64ToFile($file, $nom_archivo . '-' . date("Y-m-d"), "Comprobantes");
        
                        $file = $archivo;
        
                        Archivo::where('instrument_id', $instruments->id)->update([
                            'documento' => $file,
                            'nombre' => $nom_archivo,
                            'instrument_id' =>  $instruments->id,
                        ]);
                    }
                }
            } 

            //DOCUMENTOS DE PRORROGA
            $fileP = $request->prorroga;
            $nom_prorroga = request()->nom_prorroga;
            $nom_prorroga = substr($nom_prorroga,0,-4);

            if ($fileP != null) {
               if (substr($fileP, 0, 20) == "data:application/pdf") {

                  $prorroga = FileController::base64ToFile($fileP, $nom_prorroga . '-' . date("Y-m-d"), "Comprobantes");

                  $fileP = $prorroga; 

                  $portfolio = Prorroga::create([
                    'documento' => $fileP,
                    'nombre' => $nom_prorroga,
                    'dateStartExtension' => request()->dateStartExtension, 
                    'dateFinishExtension' => request()->dateFinishExtension,
                    'instrument_id' =>  $instruments->id,
                  ]);

                $portfolio->save();
               }
            }

        }elseif($request->state == Instrument::FINALIZADO){
            $id = Encrypt::decryptValue($request->id);
            $instruments = Instrument::find($id);

            $instruments->type_instrument_id = TypeInstrument::where('type_instrument_name', $request->type_instrument_name)->first()->id;
            $instruments->instrument_name = $request->instrument_name;
            $instruments->date = $request->date;
            $instruments->dateStart = $request->dateStart;
            $instruments->dateFinish = $request->dateFinish;
            $instruments->state = Instrument::FINALIZADO;
            $instruments->sector_id = Sector::where('sector_name', $request->sector_name)->first()->id;
            $instruments->entity_id = Entity::where('entity_name', $request->entity_name)->first()->id;
            $instruments->direction_id = NationalDirection::where('national_direction_name', $request->national_direction_name)->first()->id;
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

            //DOCUMENTO OFICIAL ACTUALIZADO PARA FINALIZAR
            $file = request()->archivo;
            $nom_archivo = request()->nom_archivo;
            $nom_archivo = substr($nom_archivo,0,-4);

            $bdFile = Archivo::select('archivos.documento')->where('instrument_id', $instruments->id)->get();
            $idArchivo = Archivo::select('archivos.instrument_id')->where('instrument_id', $instruments->id)->first();
            
            if ($idArchivo == null) {
                if ($file != null) {
                    if (substr($file, 0, 20) == "data:application/pdf") {
        
                      $archivo = FileController::base64ToFile($file, $nom_archivo . '-' . date("Y-m-d"), "Comprobantes");
        
                       $file = $archivo; 
        
                       $portfolio = Archivo::create([
                          'documento' => $file,
                          'nombre' => $nom_archivo,
                          'instrument_id' =>  $instruments->id,
                       ]);
        
                       $portfolio->save();
                    }
                }
            }else if(is_array($file) == 1){
                if($file[0]['url'] === $bdFile[0]['documento'] && $request->nom_archivo == '' || $request->nom_archivo == null){

                    Archivo::where('instrument_id', $instruments->id)->update([
                        'nombre' => $bdFile[0]['nombre'],
                        'documento' => $bdFile[0]['documento'],
                        'instrument_id' =>  $instruments->id,
                    ]);
                }
            }else {
                if ($file != null) {
                    if (substr($file, 0, 20) == "data:application/pdf") {
    
                        $archivo = FileController::base64ToFile($file, $nom_archivo . '-' . date("Y-m-d"), "Comprobantes");
        
                        $file = $archivo;
        
                        Archivo::where('instrument_id', $instruments->id)->update([
                            'documento' => $file,
                            'nombre' => $nom_archivo,
                            'instrument_id' =>  $instruments->id,
                        ]);
                    }
                }
            }
        }else{
            $id = Encrypt::decryptValue($request->id);
            /* $instruments = Instrument::find($id); */
            $instruments = Instrument::with("exoneration")->where('id', $id)->first();

            if(sizeof($instruments->exoneration) > 0){
                /* abort(403, "No se puede eliminar este registro porque ya ha sido utilizado."); */
                return response()->json([
                    "status"=>"fail",
                    "message"=>"El registro no puede modificarse porque ya contiene exoneraciones."
                ]);
            }

            $instruments->type_instrument_id = TypeInstrument::where('type_instrument_name', $request->type_instrument_name)->first()->id;
            $instruments->instrument_name = $request->instrument_name;
            $instruments->date = $request->date;
            $instruments->dateStart = $request->dateStart;
            $instruments->dateFinish = $request->dateFinish;
            $instruments->state = Instrument::VIGENTE;
            $instruments->sector_id = Sector::where('sector_name', $request->sector_name)->first()->id;
            $instruments->entity_id = Entity::where('entity_name', $request->entity_name)->first()->id;
            $instruments->direction_id = NationalDirection::where('national_direction_name', $request->national_direction_name)->first()->id;
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
            
            $file = request()->archivo;
            $nom_archivo = request()->nom_archivo;
            $nom_archivo = substr($nom_archivo,0,-4);

            $bdFile = Archivo::select('archivos.documento')->where('instrument_id', $instruments->id)->get();
            $idArchivo = Archivo::select('archivos.instrument_id')->where('instrument_id', $instruments->id)->first();

            if ($idArchivo == null) {
                if ($file != null) {
                    if (substr($file, 0, 20) == "data:application/pdf") {
        
                      $archivo = FileController::base64ToFile($file, $nom_archivo . '-' . date("Y-m-d"), "Comprobantes");
        
                       $file = $archivo; 
        
                       $portfolio = Archivo::create([
                          'documento' => $file,
                          'nombre' => $nom_archivo,
                          'instrument_id' =>  $instruments->id,
                       ]);
        
                       $portfolio->save();
                    }
                 }
            }else if(is_array($file) == 1){
                if($file[0]['url'] === $bdFile[0]['documento'] && $request->nom_archivo == '' || $request->nom_archivo == null){

                    Archivo::where('instrument_id', $instruments->id)->update([
                        'nombre' => $bdFile[0]['nombre'],
                        'documento' => $bdFile[0]['documento'],
                        'instrument_id' =>  $instruments->id,
                    ]);
                }
            }else {
                if ($file != null) {
                    if (substr($file, 0, 20) == "data:application/pdf") {
    
                        $archivo = FileController::base64ToFile($file, $nom_archivo . '-' . date("Y-m-d"), "Comprobantes");
        
                        $file = $archivo;
        
                        Archivo::where('instrument_id', $instruments->id)->update([
                            'documento' => $file,
                            'nombre' => $nom_archivo,
                            'instrument_id' =>  $instruments->id,
                        ]);
                    }
                }
        }

        return response()->json([
            "status" => "success",
            "message" => "Registro modificado correctamente."
        ]);
    }
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
