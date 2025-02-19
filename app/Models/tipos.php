<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos extends Model
{
    use HasFactory;


    protected $fillable = [
        'tipo',
    ];


    public function tareas()
    {
        return $this->hasMany(tareas::class, 'idtipo');
    }
}
