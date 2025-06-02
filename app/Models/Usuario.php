<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public $timestamps = false;
    protected $connection = "mysql";
    protected $table = "usuarios";
    protected $primaryKey = "rpe";

    protected $fillable = [
        "rpe","nombre","cve_carrera","rol"
    ];
}
