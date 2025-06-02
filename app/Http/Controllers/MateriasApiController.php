<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MateriasOptativas;

class MateriasApiController extends Controller
{
    public function index()
    {
        return response()->json(MateriasOptativas::all());
    }
}
