<?php


namespace App\Services;

use App\Models\Queue;
use App\Models\User;
use App\Models\UserIllness;
use App\Models\UserSymptom;
Use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserService
{
    // Chave da API do ChatGPT (idealmente, configurada no .env)
    protected $apiKey;

    public function __construct()
    {
        // Pega a chave API configurada no .env
        $this->apiKey = env('OPENAI_API_KEY');
    }



    public static function triagemUser($triagem)
    {
    $user = User::create([
        'name' => $triagem->name,
        'hash' => uniqid(),
        'password' => '123456789',
        'age' => (int)$triagem->idade,
        'sector' => $triagem->setor,
        'level_urgency' => $triagem->nivel_urgencia,
        'priority'=> $triagem->priority,
    ]);

    foreach ($triagem->doenças as $key => $d) {
        UserIllness::create([
            'user_id' => $user->id, // Chave estrangeira para a tabela users
            'name' => $d->nome, // Nome da doença
            'probability' => $d->probabilidade, // Probabilidade (em % ou descrição)
        ]);      
    }

    foreach ($triagem->Sintomas as $key => $s) {
        UserSymptom::create(['symptom' => $s , 'user_id' => $user->id]);
    }

    return $user;
    }

    public static function updateUser($request)
    {
        $user = User::find($request->id);
        // $validatedData = $request->validate([
        //     'name'          => 'sometimes|string|max:255',
        //     'hash'          => 'sometimes|string|unique:users,hash,' . $user->id . '|max:255',
        //     'password'      => 'sometimes|string|min:8',
        //     'age'           => 'sometimes|integer|min:0',
        //     'sector'        => 'nullable|string|max:255',
        //     'level_urgency' => 'nullable|integer|min:0|max:10',
        //     'priority'      => 'nullable|integer|min:0|max:10',
        // ]);

        if (isset($request['password'])) {
            $request['password'] = bcrypt($request['password']);
        }

        $user = $user->update($request);

        return $user;
    }

    public static function getIllness(){
        $user = Auth::user();

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
