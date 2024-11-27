<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    // Definindo a tabela associada à model
    protected $table = 'queues';

    // Definindo os campos que podem ser preenchidos (mass assignment)
    protected $fillable = [
        'patient_name',
        'priority',
        'nivel',
        'status',
        'arrival_time',
        'start_time',
        'end_time',
        'notes',
        'order'
    ];

    // Definindo os tipos de dados dos campos (caso necessário)
    protected $casts = [
        'arrival_time' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Definindo valores padrão para a model (opcional)
    protected $attributes = [
        'priority' => 50, // Valor padrão de prioridade
        'nivel' => 'baixo', // Valor padrão de nível
        'status' => 'waiting', // Valor padrão de status
    ];
}
