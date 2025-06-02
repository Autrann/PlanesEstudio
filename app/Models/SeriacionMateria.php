<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeriacionMateria extends Model
{
    public $timestamps = false;
    protected $connection = "mysql";
    protected $table = "si_cat_seriacion_materia";
    protected $primaryKey = "cve_carrera";

    protected $fillable = [
        "cve_carrera","cve_materia ","cve_materia_requerida"
    ];

    public function phone()
    {
        return $this->hasOne('App\Planes');
    }

}
