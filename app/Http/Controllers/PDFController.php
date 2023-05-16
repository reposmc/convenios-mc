<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;
use App\Models\Exoneration;
use App\Models\Instrument;
use App\Models\Tariff;

class PDFController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function generatePDF(Request $request)
    {
        //dd($request);
        //dd($data);
        $date = $request->all();

        if($request->dateOne == null || $request->dateOne == ''){
            $instrument_id = Instrument::where("instrument_name", $request->instrument_name)->first()->id;

            $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'entities.entity_name')
                                ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
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

        }else{
            $instrument_id = Instrument::where("instrument_name", $request->instrument_name)->first()->id;

            $data = Instrument::select('instruments.*', 'type_instruments.type_instrument_name', 'sectors.sector_name', 'entities.entity_name')
                                ->join('type_instruments', 'instruments.type_instrument_id', '=', 'type_instruments.id')
                                ->join('entities', 'instruments.entity_id', '=', 'entities.id')
                                ->join('sectors', 'instruments.sector_id', '=', 'sectors.id')
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
        }
            
        $pdf = PDF::loadView('PDF.report', compact('data'));

        return $pdf->stream('report-'.now().'.pdf');
    }
}