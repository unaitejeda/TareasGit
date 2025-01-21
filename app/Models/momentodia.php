<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MomentoDia extends Model
{
    use HasFactory;
    protected $table = 'momentodia'; 

    /**
     * Los atributos que se pueden asignar de manera masiva.
     */
    protected $fillable = [
        'momento',
    ];

    /**
     * Relación con las tareas.
     */
    public function tareas()
    {
        return $this->hasMany(tareas::class, 'idmomento');
    }
}
