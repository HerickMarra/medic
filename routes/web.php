<?php

use App\Http\Controllers\QueueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('user')->group(function () { 
    Route::get('/', [UserController::class, 'index']);
    Route::get('/illness', [UserController::class, 'getIllness']);
    Route::get('/symptoms', [UserController::class, 'getSymptoms']);
    Route::post('/', [UserController::class, 'store'])->name('users.store');       // Rota para criar usuário
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');     // Rota para exibir usuário
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update'); // Rota para atualizar usuário
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // Rota para excluir usuário
});

Route::prefix('fila')->group(function() {
    Route::get('/', [QueueController::class, 'index']);
    Route::get('/store', [QueueController::class, 'store']);
    Route::get('/update-status', [QueueController::class, 'updateStatus']);
});

