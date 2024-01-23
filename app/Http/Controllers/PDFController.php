<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exoneration;
use App\Models\Instrument;
use App\Models\NationalDirection;
use App\Models\Sector;
use App\Models\Tariff;
use App\Models\Entity;
use Encrypt;
use PDF;
use DB;

class PDFController extends Controller
{
    public function index()
    {
        /* $nationals = NationalDirection::all();

        $nationals = Encrypt::encryptObject($nationals, 'id');

        return response()->json([
            "status"=>"success",
            "message"=>"Registros obtenidos correctamente.",
            'nationals'=>$nationals
        ]); */
        $nationals = NationalDirection::all();
        $instruments = Instrument::all();
        $entities = Entity::all();

        $nationals = Encrypt::encryptObject($nationals, 'id');
        $instruments = Encrypt::encryptObject($instruments, 'id');
        $entities = Encrypt::encryptObject($entities, 'id');

        $response = [
            "status" => "success",
            "message" => "Registros obtenidos correctamente.",
            'nationals' => $nationals,
            'instruments' => $instruments,
            'entities' => $entities
        ];

        return response()->json($response);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function generatePDF(Request $request)
    {

        //dd($request);
        
        if($request->kind_of_report == "General"){
            if($request->dateOne == null || $request->dateOne == ''){
                $instrument_name = NULL;
                $national_direction_name = NULL;
                $date = $request->all();
                $kind_of_report = $request->kind_of_report;

                $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'national_directions.national_direction_name', 'entities.entity_name')
                                ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
                                ->join('instruments_dependecies_detail', 'instruments.id', '=', 'instruments_dependecies_detail.instrument_id')
                                ->join('dependences', 'instruments_dependecies_detail.dependency_id', '=', 'dependences.id')
                                ->join('national_directions', 'dependences.national_direction_id', '=', 'national_directions.id')
                                ->distinct()
                                ->orderBy('national_directions.national_direction_name', 'asc')
                                ->groupBy('national_directions.national_direction_name', 'entities.entity_name')
                                ->get();

                foreach($data as $item){
                    $item->exonerations = Exoneration::select('exonerations.*')
                                ->where('exonerations.instrument_id', $item->id)
                                ->distinct()
                                ->get(); 

                    $item->totalAmount = Exoneration::selectRaw('SUM(exonerations.total_amount) AS Total')
                                ->where('exonerations.instrument_id', $item->id)
                                ->value('Total'); 
                } 

                $pdf = PDF::loadView('PDF.report', compact('data','instrument_name','national_direction_name','kind_of_report'));
                return $pdf->setPaper('a4', 'landscape')->stream('report-'.now().'.pdf');
            } 
            else {
                $instrument_name = NULL;
                $national_direction_name = NULL;
                $date = $request->all();
                $kind_of_report = $request->kind_of_report;

                $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'national_directions.national_direction_name', 'entities.entity_name')
                                ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
                                ->join('instruments_dependecies_detail', 'instruments.id', '=', 'instruments_dependecies_detail.instrument_id')
                                ->join('dependences', 'instruments_dependecies_detail.dependency_id', '=', 'dependences.id')
                                ->join('national_directions', 'dependences.national_direction_id', '=', 'national_directions.id')
                                ->distinct()
                                ->orderBy('national_directions.national_direction_name', 'asc')
                                ->groupBy('national_directions.national_direction_name', 'entities.entity_name')
                                ->get();

                foreach($data as $item){
                    $item->exonerations = Exoneration::select('exonerations.*')
                                ->where('exonerations.instrument_id', $item->id)
                                ->whereRaw("(exonerations.date_event >= ? AND exonerations.date_event <= ?)", [$date['dateOne'], $date['dateTwo']])
                                ->distinct()
                                ->get(); 

                    $item->totalAmount = Exoneration::selectRaw('SUM(exonerations.total_amount) AS Total')
                                ->where('exonerations.instrument_id', $item->id)
                                ->whereRaw("(exonerations.date_event >= ? AND exonerations.date_event <= ?)", [$date['dateOne'], $date['dateTwo']])
                                ->value('Total'); 
                } 

                $pdf = PDF::loadView('PDF.report', compact('data','instrument_name','national_direction_name','kind_of_report'));
                return $pdf->setPaper('a4', 'landscape')->stream('report-'.now().'.pdf');
            }

        } else if($request->instrument_name == NULL || $request->instrument_name == ''){

            if($request->dateOne == null || $request->dateOne == ''){
                $instrument_name = NULL;
                $entity_name = NULL;
                $date = $request->all();
                $national_id = NationalDirection::where("national_direction_name", $request->national_direction_name)->first()->id;
                $national_direction_name = $request->national_direction_name;
                $kind_of_report = NULL;

                $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'national_directions.national_direction_name', 'entities.entity_name')
                                    ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                    ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                    ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
                                    ->join('instruments_dependecies_detail', 'instruments.id', '=', 'instruments_dependecies_detail.instrument_id')
                                    ->join('dependences', 'instruments_dependecies_detail.dependency_id', '=', 'dependences.id')
                                    ->join('national_directions', 'dependences.national_direction_id', '=', 'national_directions.id')
                                    ->where('national_directions.id', $national_id)
                                    ->groupBy('instruments.id','instruments.entity_id','instruments.type_instrument_id','instruments.sector_id','type_instruments.type_instrument_name', 
                                    'sectors.sector_name', 'national_directions.national_direction_name','instruments.instrument_name','instruments.description','instruments.deleted_at',
                                    'instruments.created_at','instruments.updated_at', 'entities.entity_name')
                                    ->distinct()
                                    ->orderBy('instruments.instrument_name', 'asc')
                                    ->get();

                foreach($data as $item){
                    $item->exonerations = Exoneration::select('exonerations.*')
                                ->where('exonerations.instrument_id', $item->id)
                                ->get(); 
                    
                    $item->totalAmount = Exoneration::selectRaw('SUM(exonerations.total_amount) AS Total')
                                ->where('exonerations.instrument_id', $item->id)
                                ->value('Total');
                } 
                $pdf = PDF::loadView('PDF.report', compact('data','instrument_name','national_direction_name','kind_of_report'));

                return $pdf->setPaper('a4', 'landscape')->stream('report-'.now().'.pdf');
            } else {
                $instrument_name = NULL;
                $entity_name = NULL;
                $date = $request->all();
                $national_id = NationalDirection::where("national_direction_name", $request->national_direction_name)->first()->id;
                $national_direction_name = $request->national_direction_name;
                $kind_of_report = NULL;

                $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'national_directions.national_direction_name', 'entities.entity_name')
                                    ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                    ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                    ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
                                    ->join('instruments_dependecies_detail', 'instruments.id', '=', 'instruments_dependecies_detail.instrument_id')
                                    ->join('dependences', 'instruments_dependecies_detail.dependency_id', '=', 'dependences.id')
                                    ->join('national_directions', 'dependences.national_direction_id', '=', 'national_directions.id')
                                    ->where('national_directions.id', $national_id)
                                    ->groupBy('instruments.id','instruments.entity_id','instruments.type_instrument_id','instruments.sector_id','type_instruments.type_instrument_name', 
                                    'sectors.sector_name', 'national_directions.national_direction_name','instruments.instrument_name','instruments.description','instruments.deleted_at',
                                    'instruments.created_at','instruments.updated_at', 'entities.entity_name')
                                    ->distinct()
                                    ->orderBy('instruments.instrument_name', 'asc')
                                    ->get();

                foreach($data as $item){
                    $item->exonerations = Exoneration::select('exonerations.*')
                                ->where('exonerations.instrument_id', $item->id)
                                ->whereRaw("(exonerations.date_event >= ? AND exonerations.date_event <= ?)", [$date['dateOne'], $date['dateTwo']])
                                ->get(); 
                    
                    $item->totalAmount = Exoneration::selectRaw('SUM(exonerations.total_amount) AS Total')
                                ->where('exonerations.instrument_id', $item->id)
                                ->whereRaw("(exonerations.date_event >= ? AND exonerations.date_event <= ?)", [$date['dateOne'], $date['dateTwo']])
                                ->value('Total');
                } 
                $pdf = PDF::loadView('PDF.report', compact('data','instrument_name','national_direction_name','kind_of_report'));

                return $pdf->setPaper('a4', 'landscape')->stream('report-'.now().'.pdf');
            }  
                
            } else if($request->national_direction_name == NULL || $request->national_direction_name == '' ){
                if($request->dateOne == null || $request->dateOne == ''){
                    $national_direction_name = NULL;
                    $entity_name = NULL;
                    $date = $request->all();
                    $instrument_id = Instrument::where("instrument_name", $request->instrument_name)->first()->id;
                    $kind_of_report = NULL;

                    $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'entities.entity_name', 'national_directions.national_direction_name')
                                        ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                        ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                        ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
                                        ->join('national_directions', 'instruments.direction_id', '=', 'national_directions.id')
                                        ->where('instruments.id', $instrument_id)
                                        ->get();

                    foreach($data as $item){
                        $item->exonerations = Exoneration::select('exonerations.*')
                        ->where('exonerations.instrument_id', $instrument_id)
                        ->get();

                        $item->totalAmount = Exoneration::selectRaw('SUM(exonerations.total_amount) AS Total')
                        ->where('exonerations.instrument_id', $instrument_id)
                        ->value('Total');
                    } 

                    $pdf = PDF::loadView('PDF.report', compact('data','national_direction_name', 'kind_of_report'));
                    return $pdf->setPaper('letter', 'portrait')->stream('report-'.now().'.pdf');
                } else{
                    $national_direction_name = NULL;
                    $entity_name = NULL;
                    $date = $request->all();
                    $instrument_id = Instrument::where("instrument_name", $request->instrument_name)->first()->id;
                    $kind_of_report = NULL;

                    $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'entities.entity_name', 'national_directions.national_direction_name')
                                        ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                        ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                        ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
                                        ->join('national_directions', 'instruments.direction_id', '=', 'national_directions.id')
                                        ->where('instruments.id', $instrument_id)
                                        ->get();
        
                    foreach($data as $item){
                        $item->exonerations = Exoneration::select('exonerations.*')
                        ->where('exonerations.instrument_id', $instrument_id)
                        ->whereRaw("(exonerations.date_event >= ? AND exonerations.date_event <= ?)", [$date['dateOne'], $date['dateTwo']])
                        ->get();

                        $item->totalAmount = Exoneration::selectRaw('SUM(exonerations.total_amount) AS Total')
                            ->where('exonerations.instrument_id', $instrument_id)
                            ->whereRaw("(exonerations.date_event >= ? AND exonerations.date_event <= ?)", [$date['dateOne'], $date['dateTwo']])
                            ->value('Total');
                    } 

                    $pdf = PDF::loadView('PDF.report', compact('data','national_direction_name', 'kind_of_report'));
                    return $pdf->setPaper('letter', 'portrait')->stream('report-'.now().'.pdf');
                }
                
        }else{}
    }
}