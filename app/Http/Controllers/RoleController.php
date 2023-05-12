<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Encrypt;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $roles = Encrypt::encryptObject($roles, "id");

        return response()->json([
            "status" => "success",
            "message" => "Registro creado correctamente.",
            "roles" => $roles
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
     * Gets the specified rol from user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getActualUserRoles()
    {
        $roles = auth()->user()->getRoleNames();

        return response()->json(['message' => 'success', 'roles' => $roles]);
    }

    public static function getAllowedRoles()
    {
        $roles = auth()->user()->getRoleNames();

        return $roles;
    }
}
