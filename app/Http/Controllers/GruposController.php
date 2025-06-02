<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;

class GruposController extends Controller
{

    public function getJSON()
    {
        $grupos = Grupo::all();
        return response()->json($grupos);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'color' => 'required|string|max:255',
        ]);

        Grupo::create($data);

        return redirect()->route('grupos.index')
                         ->with('success', 'Grupo creado correctamente.');
    }


    public function update(Request $request, Grupo $grupo)
    {
        $data = $request->validate([
            'color' => 'required|string|max:255',
        ]);

        $grupo->update($data);

        return redirect()->route('grupos.index')
                         ->with('success', 'Grupo actualizado correctamente.');
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return redirect()->route('grupos.index')
                         ->with('success', 'Grupo eliminado correctamente.');
    }
}
