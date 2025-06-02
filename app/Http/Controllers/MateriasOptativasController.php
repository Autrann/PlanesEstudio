<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;
use App\Models\Carrera;
use App\Models\MateriasOptativas;

class MateriasOptativasController extends Controller
{
    
    public function index()
    {
        $optativas = MateriasOptativas::all();
        return view('uaslp.pruebaMateriasOpt', compact('optativas'));
    }
    

    public function getJSON()
    {
        $materias = MateriasOptativas::all();
        return response()->json($materias);
    }


    public function create()
    {
        return view('materias_optativas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombreMateria'  => 'required|string|max:255',
            'horasTeoria'    => 'required|integer|min:0',
            'horasPractica'  => 'required|integer|min:0',
            'creditos'       => 'required|integer|min:0',
            'claveMateria'   => 'required|string|max:50|unique:materias_optativas,claveMateria',
            'claveCacei'     => 'required|string|max:50',
            'cve_carrera'    => 'required|exists:carreras,cve_carrera',
        ]);

        MateriasOptativas::create($data);

        return redirect()->route('materias_optativas.index')
                         ->with('success', 'Materia optativa creada correctamente.');
    }

    public function show(MateriasOptativas $materias_optativa)
    {
        return view('materias_optativas.show', ['optativa' => $materias_optativa]);
    }

    public function edit(MateriasOptativas $materias_optativa)
    {
        return view('materias_optativas.edit', ['optativa' => $materias_optativa]);
    }

    public function update(Request $request, MateriasOptativas $materias_optativa)
    {
        $data = $request->validate([
            'nombreMateria'  => 'required|string|max:255',
            'horasTeoria'    => 'required|integer|min:0',
            'horasPractica'  => 'required|integer|min:0',
            'creditos'       => 'required|integer|min:0',
            'claveMateria'   => "required|string|max:50|unique:materias_optativas,claveMateria,{$materias_optativa->id}",
            'claveCacei'     => 'required|string|max:50',
            'cve_carrera'    => 'required|exists:carreras,cve_carrera',
        ]);

        $materias_optativa->update($data);

        return redirect()->route('materias_optativas.index')
                         ->with('success', 'Materia optativa actualizada correctamente.');
    }

    public function destroy(MateriasOptativas $materias_optativa)
    {
        $materias_optativa->delete();

        return redirect()->route('materias_optativas.index')
                         ->with('success', 'Materia optativa eliminada correctamente.');
    }
}
