<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MomentoDia extends Model
{
    use HasFactory;
    protected $table = 'momentodia'; 


    protected $fillable = [
        'momento',
    ];


    public function tareas()
    {
        return $this->hasMany(tareas::class, 'idmomento');
    }
}
