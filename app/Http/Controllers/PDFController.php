<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;
use App\Models\Exoneration;
use App\Models\Agreement;

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
            $agreement_id = Agreement::where("agreement_name", $request->agreement_name)->first()->id;

            $data = Agreement::select('agreements.*', 'entities.entity_name', 'type_agreements.type_agreement_name')
                                ->join('entities', 'agreements.entity_id', '=', 'entities.id')
                                ->join('type_agreements', 'agreements.type_agreement_id', '=', 'type_agreements.id')
                                ->where('agreements.id', $agreement_id)
                                ->get();

           /*  $data = Exoneration::selectRaw('SUM(exonerations.exonerated_amount) AS Total')
                    ->where('exonerations.agreement_id', $agreement_id)
                    ->value('Total'); */

            foreach($data as $item){
                $item->exonerations = Exoneration::select('exonerations.*', 'service_places.place_name',
                        DB::raw('IFNULL(tariffs.amount, exonerations.not_charged) AS charge'))
                ->leftJoin('service_places', 'exonerations.service_place_id', '=', 'service_places.id')
                ->leftJoin('tariffs', 'exonerations.tariff_id', '=', 'tariffs.id') 
                ->where('exonerations.agreement_id', $agreement_id)
                ->get();

                $item->totalAmount = Exoneration::selectRaw('SUM(exonerations.exonerated_amount) AS Total')
                    ->where('exonerations.agreement_id', $agreement_id)
                    ->value('Total');
            } 

        }else{
            $agreement_id = Agreement::where("agreement_name", $request->agreement_name)->first()->id;

            $data = Agreement::select('agreements.*', 'entities.entity_name', 'type_agreements.type_agreement_name')
                                ->join('entities', 'agreements.entity_id', '=', 'entities.id')
                                ->join('type_agreements', 'agreements.type_agreement_id', '=', 'type_agreements.id')
                                ->where('agreements.id', $agreement_id)
                                ->get();

            foreach($data as $item){
                $item->exonerations = Exoneration::select('exonerations.*', 'service_places.place_name',
                        DB::raw('IFNULL(tariffs.amount, exonerations.not_charged) AS charge'))
                ->leftJoin('service_places', 'exonerations.service_place_id', '=', 'service_places.id')
                ->leftJoin('tariffs', 'exonerations.tariff_id', '=', 'tariffs.id')
                ->whereRaw("(exonerations.date >= ? AND exonerations.date <= ?)", [$date['dateOne'], $date['dateTwo']])
                ->where('exonerations.agreement_id', $agreement_id)
                ->get();
            }
        }
            
        $pdf = PDF::loadView('PDF.report', compact('data'));

        return $pdf->stream('report-'.now().'.pdf');
    }
}