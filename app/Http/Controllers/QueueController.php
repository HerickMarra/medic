<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\QueueService;

class QueueController extends Controller
{
  public function index()
  {
      $queues = $this->orderQueues(); // Recupera todos os registros da tabela 'queues'
      return response()->json($queues);
  }

  // Exibe um registro específico da fila
  public function show($id)
  {
      $queue = Queue::findOrFail($id); // Busca pelo ID ou lança erro 404
      return response()->json($queue);
  }

  // Cria um novo registro na fila
  public static function store($user, $triagem)
  {
      $queue = QueueService::store($user, $triagem);
      return response()->json(['message' => 'Paciente adicionado à fila', 'queue' => $queue], 201);
  }

  public function adjustPriority()
  {
      $queues = Queue::where('status', 'waiting') // Filtra as filas em espera
          ->get();

      foreach ($queues as $queue) {
          // Calcula o tempo de espera (em minutos)
          $waitingTime = Carbon::now()->diffInMinutes($queue->arrival_time);

          // Define a regra para o aumento de prioridade
          $increment = 0;

          switch ($queue->nivel) {
            case 'baixo':
                // A cada 60 minutos de espera, aumenta 5 pontos
                $increment = intdiv($waitingTime, 60) * 5;
                break;
            case 'medio':
                // A cada 45 minutos de espera, aumenta 10 pontos
                $increment = intdiv($waitingTime, 45) * 10;
                break;
            case 'alto':
                // A cada 30 minutos de espera, aumenta 15 pontos
                $increment = intdiv($waitingTime, 30) * 15;
                break;
            case 'emergencia':
                // A cada 15 minutos de espera, aumenta 20 pontos
                $increment = intdiv($waitingTime, 15) * 20;
                break;
        }

          // Aumenta a prioridade, garantindo que a prioridade máxima seja 100
          $newPriority = min($queue->priority + $increment, 100);

          // Atualiza a prioridade do paciente
          $queue->priority = $newPriority;
          $queue->save();
      }
  }
  
  public function orderQueues()
  {
    $this->adjustPriority();

    $queues = Queue::where('status', 'waiting') // Filtra apenas os pacientes em espera
    ->orderByDesc('priority') // Ordena pela prioridade (em ordem decrescente, ou seja, maior prioridade primeiro)
    ->orderBy('arrival_time') // Ordena pelo tempo de chegada (primeiro a chegar, primeiro a ser atendido)
    ->get();

    return response()->json($queues);
  }

  // Atualiza um registro específico na fila
  public static function updateStatus(Request $request)
  {
      $queue = QueueService::updateStatus($request);
      return response()->json(['message' => 'Registro atualizado com sucesso', 'queue' => $queue], 200);
  }

  // Deleta um registro específico da fila
  public static function destroy($id)
  {
      QueueService::destroy($id);
      return response()->json(['message' => 'Registro removido com sucesso'], 200);
  }

}
