<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuarios extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar de manera masiva.
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'sexo',
        'mail',
        'password',
        'cantidad_tareas',
    ];

    /**
     * Los atributos que se deben ocultar en las respuestas JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
