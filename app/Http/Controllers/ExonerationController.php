<?php

namespace App\Http\Controllers;

use App\Models\Exoneration;
use Illuminate\Http\Request;
use App\Models\Agreement;
use App\Models\ServicePlace;
use App\Models\Tariff;
use Encrypt;
use DB;

class ExonerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$exonerations = Exoneration::all();
        //$exonerations = Exoneration::where('agreement_id', $request->agreement_id)->get();

        /* foreach($exonerations as $exoneration)
        {
            $exoneration->place_name = ServicePlace::find(Exoneration::find($exoneration->id)->service_place_id)->place_name;
            $exoneration->people = Exoneration::find($exoneration->id)->people;
            $exoneration->date = Exoneration::find($exoneration->id)->date;
            $exoneration->hour = Exoneration::find($exoneration->id)->hour;
            $exoneration->exonerated_amount = Exoneration::find($exoneration->id)->exonerated_amount;
            $exonerationDetail = Exoneration::find($exoneration->id);
            if($exonerationDetail->tariff_id){
                $exoneration->charge = Tariff::find($exonerationDetail->tariff_id)->amount;
            }else{
                $exoneration->charge = $exonerationDetail->not_charged;
            } 
        } */

        /* $exonerations = Encrypt::encryptObject($exonerations,"id");

        return response()->json([
            "status"=>"success", 
            "message"=>"Registro obtenido correctamente.", 
            "exonerations"=>$exonerations]); */
    }

    public function store(Request $request)
    {
        //dd($request);
        $super = $request->except("dependence_name");
        $dependence = Dependence::where("dependence_name", $request->dependence_name)->first();
        $agreement = DB::table('agreements')->latest('id')->first();
        //dd($agreement);
        $super["dependence_id"] = $dependence->id;
        $super["agreement_id"] = $agreement->id;
        Exoneration::insert($super);

        return response()->json(["status"=>"success", "message"=>"Registro creado correctamente."]);
    }

    public function show($agreement_id)
    {
        $agreement = Agreement::find($agreement_id);
        $exonerations = $agreement->exonerations;

        foreach($exonerations as $exoneration)
        {
            $exoneration->place_name = ServicePlace::find($exoneration->id)->place_name;
            $exoneration->people = ExonerationDetail::find($exoneration->id)->people;
            $exoneration->date = ExonerationDetail::find($exoneration->id)->date;
            $exoneration->exonerated_amount = ExonerationDetail::find($exoneration->id)->exonerated_amount;
            $exonerationDetail = ExonerationDetail::find($exoneration->id);
            if($exonerationDetail->tariff_id){
                $exoneration->charge = Tariff::find($exonerationDetail->tariff_id)->type_charge;
            }else{
                $exoneration->charge = $exonerationDetail->not_charged;
            }
        }

        $exonerations = Encrypt::encryptObject($exonerations,"id");

        return response()->json([
            "status"=>"success", 
            "message"=>"Registro obtenido correctamente.", 
            "exonerations"=>$exonerations]);
            }

    public function update(Request $request, Exoneration $exoneration)
    {
        //
    }

    public function destroy($id)
    {
        $id = Encrypt::decryptValue($id);

        Exoneration::where("id", $id)->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Registro eliminado correctamente."]);
    }
}
