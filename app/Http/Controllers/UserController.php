<?php

namespace App\Http\Controllers;

use App\Models\UserIllness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){

        return view('user.index');
    }



    public static function getIllness(){
        $user =  Auth::user();

        if(!isset($user)){
            return response()->json([
                'status' => false,
            ]);
        }

        $itens = UserIllness::where('user_id', $user->id)->get();

        return response()->json([
            'status' => true,
            'itens' => $itens,
        ]);
    }
}
