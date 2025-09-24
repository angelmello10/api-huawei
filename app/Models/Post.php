<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'tipo',
        'mensaje',
        'foto_url',
        'latitud',
        'longitud',
        'expira_en',
        'estado',
        'user_id',
    ];

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;
}
