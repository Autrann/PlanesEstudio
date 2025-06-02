<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Carrera;
use App\Models\Grupo;


class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';

    protected $fillable = [
        'nombreMateria',
        'horasTeoria',
        'horasPractica',
        'creditos',
        'claveMateria',
        'claveCacei',
        'cve_Carrera',
        'grupo_id',
        'tipoMateria',
    ];
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'cve_Carrera');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
}
