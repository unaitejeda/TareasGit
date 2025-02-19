<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lugares extends Model
{
    use HasFactory;


    protected $fillable = [
        'lugar',
    ];


    public function tareas()
    {
        return $this->hasMany(tareas::class, 'idlugar');
    }
}
