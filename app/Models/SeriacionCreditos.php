<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeriacionCreditos extends Model
{
    public $timestamps = false;
    protected $connection = "mysql";
    protected $table = "si_cat_seriacion_creditos";
    protected $primaryKey = "id";

    protected $fillable = [
        "id","cve_carrera ","nota_academica","descripcion","creditos"
    ];
    
}
