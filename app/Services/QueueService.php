<?php

namespace App\Services;

use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QueueService
{
  // Cria um novo registro na fila
  public static function store($user, $triagem)
  {
    $triagem = json_decode($triagem, true);
      // Valida os dados recebidos

      // $triagem->validate([
      //     'patient_name' => 'required|string|max:255',
      //     'priority' => 'required|integer',
      //     'nivel' => 'required|in:baixo,medio,alto,emergencia',
      //     'notes' => 'nullable|string|max:500',
      //     'order' => 'nullable|integer',
      // ]);
      // Cria um novo registro na fila

      if($triagem['nivel_urgencia'] == 'baixa') $triagem['nivel_urgencia'] = 'baixo'; 

      $queue = Queue::create([
        'patient_name' => $user->name,
        'priority' => $triagem['priority'],
        'nivel' => $triagem['nivel_urgencia'],
        'status' => 'waiting',
        'notes' => $triagem['observacoes_adicionais'],
        'order' => null,
        'arrival_time' => Carbon::now()
      ]); // Cria o registro
      return response()->json(['message' => 'Paciente adicionado à fila', 'queue' => $queue], 201);
  }

  // Atualiza um registro específico na fila
  public static function updateStatus(Request $request)
  {
      $queue = Queue::findOrFail($request->id);

      // $request->validate([
      //     'status' => 'required|in:waiting,in_progress,completed,cancelled',
      // ]);

      // Atualiza os dados com base no status
      $newStatus = $request->status;

      switch ($newStatus) {
          case 'in_progress':
              $queue->start_time = Carbon::now(); // Define o horário atual como início
              break;

          case 'completed':
              $queue->end_time = Carbon::now(); // Define o horário atual como fim
              break;

          case 'cancelled':
              // Não há alterações além do status
              break;

          default:
              // Se necessário, ações adicionais para outros status
              break;
      }

      // Atualiza o status e salva no banco
      $queue->status = $newStatus;
      $queue->save();
  }

  // Deleta um registro específico da fila
  public static function destroy($id)
  {
      $queue = Queue::findOrFail($id);
      $queue->delete();
  }

}
