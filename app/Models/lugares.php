<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lugares extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar de manera masiva.
     */
    protected $fillable = [
        'lugar',
    ];

    /**
     * Relación con las tareas.
     */
    public function tareas()
    {
        return $this->hasMany(tareas::class, 'idlugar');
    }
}
