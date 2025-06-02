<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carreras';
    protected $primaryKey = 'cve_carrera';
    protected $keyType = 'int';

    protected $fillable = [
        'area',
        'tipo',
        'carrera',
        'nivel_max',
        'columna_max',
    ];

    public function materias()
    {
        return $this->hasMany(Materia::class, 'cve_Carrera', 'cve_carrera');
    }

    public function materiasOptativas()
    {
        return $this->hasMany(MateriasOptativas::class, 'cve_carrera', 'cve_carrera');
    }
}