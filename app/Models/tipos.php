<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar de manera masiva.
     */
    protected $fillable = [
        'tipo',
    ];

    /**
     * Relación con las tareas.
     */
    public function tareas()
    {
        return $this->hasMany(tareas::class, 'idtipo');
    }
}
