<?php

use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('/user/'); // Substitua 'dashboard' pelo nome da rota desejada.
});

Route::prefix('user')->group(function () { 
    Route::get('/', [UserController::class, 'index']);
    Route::get('/illness', [UserController::class, 'getIllness']);
    Route::get('/symptoms', [UserController::class, 'getSymptoms']);
    Route::post('/store', [UserController::class, 'store'])->name('users.store');       // Rota para criar usuário
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');     // Rota para exibir usuário
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update'); // Rota para atualizar usuário
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // Rota para excluir usuário
});

Route::prefix('fila')->group(function() {
    Route::get('/', [QueueController::class, 'index']);
    Route::post('/store', [QueueController::class, 'store']);
    Route::post('/update-status', [QueueController::class, 'updateStatus']);
    Route::get('/order-queue', [QueueController::class, 'orderQueues']);
});

Route::prefix('atendimento')->group(function() {
    Route::post('/realizar-atendimento', [AtendimentoController::class, 'realizarAtendimento']);
});

