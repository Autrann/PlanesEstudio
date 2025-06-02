<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveState extends Model
{
    protected $fillable = ['carrera', 'state'];

    protected $casts = [
        'state' => 'array',
    ];
}
