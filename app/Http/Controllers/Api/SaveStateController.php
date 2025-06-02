<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SaveState;

class SaveStateController extends Controller
{
    
    public function get($carrera)
    {
        $save = SaveState::where('carrera', $carrera)->first();
        return response()->json($save ? $save->state : null);
    }

    public function save(Request $request)
    {
        $request->validate([
            'carrera' => 'required',
            'state' => 'required|array',
        ]);

        $save = SaveState::updateOrCreate(
            ['carrera' => $request->carrera],
            ['state' => $request->state]
        );

        return response()->json(['message' => 'Guardado exitosamente.']);
    }
}
