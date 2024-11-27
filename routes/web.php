<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('user')->group(function () {
    
    Route::get('/', [UserController::class, 'index']);

});
