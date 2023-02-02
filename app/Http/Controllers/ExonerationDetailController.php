<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Entity;
use App\Models\Exoneration;
use App\Models\Dependence;
use App\Models\ServicePlace;
use App\Models\ExonerationDetail;
use Illuminate\Http\Request;

class ExonerationDetailController extends Controller
{
    public function index()
    {
        $exonerations = ExonerationDetail::all();

        $exonerations = Encrypt::encryptObject($exonerations, "id");

        return response()->json([
            "status"=>"success", 
            "message"=>"Registro creado correctamente.", 
            "exonerations"=>$exonerations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exoneration_detail  $exoneration_detail
     * @return \Illuminate\Http\Response
     */
    public function show(Exoneration_detail $exoneration_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exoneration_detail  $exoneration_detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exoneration_detail $exoneration_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exoneration_detail  $exoneration_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exoneration_detail $exoneration_detail)
    {
        //
    }
}
