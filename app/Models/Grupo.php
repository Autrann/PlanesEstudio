<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $fillable = [
        'color',
    ];

    public function materias()
    {
        return $this->hasMany(Materia::class, 'grupo_id');
    }
}
