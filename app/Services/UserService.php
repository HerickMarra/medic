<?php


namespace App\Services;

use App\Models\Queue;
use App\Models\User;
use App\Models\UserIllness;
use App\Models\UserSymptom;
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




    public static function  triagemUser($triagem){
        
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
    }

}
