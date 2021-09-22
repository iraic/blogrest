<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $req){
        if($req->user()->rol != 'A') return response()->json(['status'=>'failed'], 401);
        return User::all();
    }

    public function get(Request $req, $user){
        if($req->user()->rol != 'A') return response()->json(['status'=>'failed'], 401);
        $result = User::find($user);
        //$result = DB::table('users')->where('user', '=', $user)->get();
        if($result)
            return $result;
        else
            return response()->json(['status'=>'failed'], 404);
    }

    public function create(Request $req){
        if($req->user()->rol != 'A') return response()->json(['status'=>'failed'], 401);
        $this->validate($req, [
            'user'=>'required', 
            'nombre'=>'required',
            'pass'=>'required',
            'rol'=>'required']);

        $datos = new User;
        // $datos->user = $req->user;
        $datos->pass = Hash::make( $req->pass );
        // $datos->nombre = $req->nombre;
        // $datos->rol = $req->rol;
        // $datos->save();
        $result = $datos->fill($req->all())->save();
        if($result)
            return response()->json(['status'=>'success'], 200);
        else
            return response()->json(['status'=>'failed'], 404);
    }

    public function update(Request $req, $user){
        if($req->user()->rol != 'A') return response()->json(['status'=>'failed'], 401);
        $this->validate($req, [
            'user'=>'filled', 
            'nombre'=>'filled',
            'pass'=>'filled',
            'rol'=>'filled']);

        $datos = User::find($user);
        $datos->pass = Hash::make( $req->pass );
        if(!$datos) return response()->json(['status'=>'failed'], 404);
        $result = $datos->fill($req->all())->save();
        if($result)
            return response()->json(['status'=>'success'], 200);
        else
            return response()->json(['status'=>'failed'], 404);
    }

    public function destroy(Request $req, $user){
        if($req->user()->rol != 'A') return response()->json(['status'=>'failed'], 401);
        $datos = User::find($user);
        if(!$datos) return response()->json(['status'=>'failed'], 404);
        $result = $datos->delete();
        if($result)
            return response()->json(['status'=>'success'], 200);
        else
            return response()->json(['status'=>'failed'], 404);
    }

}
