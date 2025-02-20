<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tareas extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarea',
        'descripcion',
        'fecha',
        'idlugar',
        'idmomento',
        'idtipo',
        'idestado',
        'likes',
        'dislikes',
        'user_id',
        'ubicacion'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Relación con el lugar.
     */
    public function lugar()
    {
        return $this->belongsTo(lugares::class, 'idlugar');
    }

    /**
     * Relación con el momento del día.
     */
    public function momento()
    {
        return $this->belongsTo(momentodia::class, 'idmomento');
    }

    /**
     * Relación con el tipo.
     */
    public function tipo()
    {
        return $this->belongsTo(tipos::class, 'idtipo');
    }

    /**
     * Relación con el estado.
     */
    public function estado()
    {
        return $this->belongsTo(estados::class, 'idestado');
    }
}
