<?php

use App\Http\Controllers\QueueController;
use App\Http\Controllers\UserController;
use App\Models\Queue;
use App\Models\User;
use App\Models\UserIllness;
use App\Models\UserSymptom;
use App\Services\ChatGPTService;
use App\Services\UserService;
use GuzzleHttp\Client;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {

    $gpt = new ChatGPTService();
    $triagem = $gpt->preDiagnose(
        ['palpebra caida', 'Acima do peso', ], 
        49 ,
        "mulher",
        "Valéria");
        
    
    $tri = json_decode($triagem);
        dd($tri);
    if($tri == null){
        // dd($triagem);
    }

    $user = UserController::store($tri);
    $fila = QueueController::store($user, $triagem);

        // dd($triagem->doenças);
})->purpose('Display an inspiring quote')->hourly();
