<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIllness extends Model
{
    


    protected $fillable = [
        'user_id', // Chave estrangeira para a tabela users
        'name', // Nome da doença
        'probability', // Probabilidade (em % ou descrição)
    ];

}
