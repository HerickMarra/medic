<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSymptom extends Model
{
    protected $fillable = [
        'user_id', // Chave estrangeira para a tabela users
        'symptom', // Nome do sintoma
    ];
}
