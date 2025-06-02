<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'si_cat_notas';
    protected $fillable = ['contenido'];
}
