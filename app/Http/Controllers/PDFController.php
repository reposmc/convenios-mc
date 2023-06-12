<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;
use Encrypt;
use App\Models\Exoneration;
use App\Models\Instrument;
use App\Models\NationalDirection;
use App\Models\Sector;
use App\Models\Tariff;
use App\Models\Entity;

class PDFController extends Controller
{
    public function index()
    {
        $nationals = NationalDirection::all();

        $nationals = Encrypt::encryptObject($nationals, 'id');

        return response()->json([
            "status"=>"success",
            "message"=>"Registros obtenidos correctamente.",
            'nationals'=>$nationals
        ]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function generatePDF(Request $request)
    {
        //dd($request);
        //dd($data);
            $entity_id = Entity::where("entity_name", $request->entity_name)->first()->id;
            $entity_name = Entity::where("entity_name", $request->entity_name)->first()->entity_name;
            
            $date = $request->all();
            
            if($request->service_place_name == NULL || $request->service_place_name == ''){

                $national_direction_id = NationalDirection::where("national_direction_name", $request->national_direction_name)->first()->id;
                $national_direction_name = NationalDirection::where("national_direction_name", $request->national_direction_name)->first()->national_direction_name;

                $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'entities.entity_name')
                                ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
                                ->join('instruments_dependecies_detail', 'instruments.id', '=', 'instruments_dependecies_detail.instrument_id')
                                ->join('dependences', 'instruments_dependecies_detail.dependency_id', '=', 'dependences.id')
                                ->join('national_directions', 'dependences.national_direction_id', '=', 'national_directions.id')
                                ->where('national_directions.id', $national_direction_id)
                                ->where('instruments.entity_id', $entity_id)
                                ->distinct()
                                ->get();

                foreach($data as $item){
                    
                    $item->exonerations = Exoneration::select('exonerations.*')
                                ->where('exonerations.instrument_id', $item->id)
                                ->whereRaw("(exonerations.date_event = ?)", [$date['dateOne']])
                                ->get(); 

                    $item->total = Exoneration::selectRaw('SUM(exonerations.total_amount) AS Total')
                                                ->where('exonerations.instrument_id', $item->id)
                                                ->value('Total');
                } 

                $pdf = PDF::loadView('PDF.report', compact('data','national_direction_name'));
                return $pdf->setPaper('a4', 'landscape')->stream('report-'.now().'.pdf');

        } else {
            $service_place_name = $request->service_place_name;
            $national_direction_name = NULL;

            $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'national_directions.national_direction_name')
                                ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
                                ->join('instruments_dependecies_detail', 'instruments.id', '=', 'instruments_dependecies_detail.instrument_id')
                                ->join('dependences', 'instruments_dependecies_detail.dependency_id', '=', 'dependences.id')
                                ->join('national_directions', 'dependences.national_direction_id', '=', 'national_directions.id')
                                ->join('exonerations', 'exonerations.instrument_id', '=', 'instruments.id')
                                ->where('instruments.entity_id', $entity_id)
                                ->where('exonerations.service_place_name', $service_place_name)
                                ->orWhere('exonerations.concept', $service_place_name)
                                ->distinct()
                                ->get();
     
            foreach($data as $item){
                
                $item->exonerations = Exoneration::select('exonerations.*')
                            ->where('exonerations.instrument_id', $item->id)
                            ->whereRaw("(exonerations.date_event = ?)", [$date['dateOne']])
                            ->get(); 
                
                $item->total = Exoneration::selectRaw('SUM(exonerations.total_amount) AS Total')
                                            ->where('exonerations.instrument_id', $item->id)
                                            ->value('Total');
            } 
            
            $pdf = PDF::loadView('PDF.report', compact('data','national_direction_name'));

            return $pdf->setPaper('a4', 'landscape')->stream('report-'.now().'.pdf');
        }
    }
}