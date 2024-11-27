<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserIllness;
use App\Models\UserSymptom;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){

        return view('user.index');
    }

    public static function store($request)
    {
        $user = UserService::triagemUser($request);

        return $user;
    }

    public function update(Request $request)
    {
        $user = UserService::updateUser($request->id);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'data'    => $user,
        ]);
    }

    /**
     * Show a specific user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
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

    public static function getSymptoms(){
        $user =  Auth::user();

        if(!isset($user)){
            return response()->json([
                'status' => false,
            ]);
        }

        $itens = UserSymptom::where('user_id', $user->id)->get();

        return response()->json([
            'status' => true,
            'itens' => $itens,
        ]);
    }
}
