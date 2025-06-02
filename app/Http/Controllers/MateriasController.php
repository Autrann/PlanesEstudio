<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;

class MateriasController extends Controller
{
    public function index()
    {
        $materias = Materia::all();

         return view('uaslp.pruebaMaterias', compact('materias'));
    }

    public function getJSON()
    {
        $materias = Materia::all();
        return response()->json($materias);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'nombreMateria'  => 'required|string|max:255',
            'horasTeoria'    => 'required|integer|min:0',
            'horasPractica'  => 'required|integer|min:0',
            'creditos'       => 'required|integer|min:0',
            'claveMateria'   => 'required|string|max:100|unique:materias,claveMateria',
            'claveCacei'     => 'nullable|string|max:100',
            'cve_Carrera'    => 'required|exists:carreras,cve_carrera',
            'tipoMateria'    => 'required|string|in:carrera,opcion',
            'grupo_id'       => 'required|exists:grupos,id',
        ]);

        Materia::create($data);

        return redirect()->route('materias.index')
                         ->with('success', 'Materia creada correctamente.');
    }

    public function show(Materia $materia)
    {
        return view('materias.show', compact('materia'));
    }


    public function update(Request $request, Materia $materia)
    {
        $data = $request->validate([
            'nombreMateria'  => 'required|string|max:255',
            'horasTeoria'    => 'required|integer|min:0',
            'horasPractica'  => 'required|integer|min:0',
            'creditos'       => 'required|integer|min:0',
            'claveMateria'   => "required|string|max:100|unique:materias,claveMateria,{$materia->id}",
            'claveCacei'     => 'nullable|string|max:100',
            'cve_Carrera'    => 'required|exists:carreras,cve_carrera',
            'tipoMateria'    => 'required|string|in:carrera,opcion',
            'grupo_id'       => 'required|exists:grupos,id',
        ]);

        $materia->update($data);

        return redirect()->route('materias.index')
                         ->with('success', 'Materia actualizada correctamente.');
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();

        return redirect()->route('materias.index')
                         ->with('success', 'Materia eliminada correctamente.');
    }
}
