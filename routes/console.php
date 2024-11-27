<?php

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

    $gpt = NEW ChatGPTService();
    $triagem = $gpt->preDiagnose(
        ['Dor aguda nas costas', 'Mão e pés inchados'], 
        23 ,
        "Homem",
        "Vitorio");
        

    $tri= json_decode($triagem);
    

    if($tri == null){
        // dd($triagem);
    }

    dd($tri);

    UserService::triagemUser($tri);

        // dd($triagem->doenças);
})->purpose('Display an inspiring quote')->hourly();
