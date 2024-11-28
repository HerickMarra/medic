<?php

namespace App\Http\Controllers;

use App\Services\ChatGPTService;
use App\Services\QueueService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AtendimentoController extends Controller
{
    public function realizarAtedimento($request){
      $gpt = NEW ChatGPTService();
      $triagem = $gpt->preDiagnose($request);

      $tri = json_decode($triagem);

      if($tri == null){
          return response()->json([
            'status' => false,
            'message' => 'Não foi possível realizar a triagem'
          ]);
      }
  
      DB::beginTransaction();
      
      try {
          // Delegando a criação do usuário a um serviço dedicado
          $userService = new UserService();
          $user = $userService->triagemUser($tri);

          // Adicionando o usuário à fila de atendimento
          $queueService = new QueueService();
          $fila = $queueService->store($user, $triagem);

          // Autenticando o usuário, se necessário
          if ($user && $fila) {
              Auth::login($user);
          }

          DB::commit();

          return response()->json(data: [
              'status' => true,
              'message' => 'Triagem finalizada',
              'data' => $triagem,
          ]);
      } catch (\Exception $e) {
          DB::rollBack();
          return response()->json([
              'status' => false,
              'message' => 'Erro ao realizar o atendimento: ' . $e->getMessage()
          ]);
      }
    }
}
