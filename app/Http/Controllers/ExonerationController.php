<?php

namespace App\Http\Controllers;

use App\Models\Exoneration;
use Illuminate\Http\Request;
use App\Models\Dependence;
use App\Models\Agreement;

class ExonerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $data = $request->except("dependence_name");
        $dependence = Dependence::where("dependence_name", $request->dependence_name)->first();
        $agreement = DB::table('agreements')->latest('id')->first();
        //dd($agreement);
        $data["dependence_id"] = $dependence->id;
        $data["agreement_id"] = $agreement->id;
        Exoneration::insert($data);

        return response()->json(["status"=>"success", "message"=>"Registro creado correctamente."]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exoneration  $exoneration
     * @return \Illuminate\Http\Response
     */
    public function show(Exoneration $exoneration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exoneration  $exoneration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exoneration $exoneration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exoneration  $exoneration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exoneration $exoneration)
    {
        //
    }
}
