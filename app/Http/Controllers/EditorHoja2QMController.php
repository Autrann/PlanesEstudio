<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MateriasOptativas;

class EditorHoja2QMController extends Controller
{
    public function index()
    {
        $materias = MateriasOptativas::all();
        return view('editor', compact('materias'));
    }
}
