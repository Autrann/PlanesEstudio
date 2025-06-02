<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\EditorHoja2QMController;
use App\Models\MateriasOptativas;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Http\Controllers\MateriasController;
use App\Http\Controllers\MateriasOptativasController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\Api\SaveStateController;

Route::get('/editor2/{carrera}', function ($carrera) {
    return view('editor_2', ['carrera' => $carrera]);
})->name('editor2');
Route::get('/', function () {
    return view('uaslp.login');
});

Route::get('/prueba', function () {
    $materias = MateriasOptativas::all();
    return view('editor', compact('materias'));
})->name('editor');

Route::get('/prueba', [EditorHoja2QMController::class, 'index'])->name('editor.index');

Route::post('/generate-pdf', function(Request $request) {
    $svgData = $request->input('svg');

    if (!$svgData) {
        return response()->json(['error' => 'No se recibió el SVG'], 400);
    }

    return SnappyPdf::loadHTML(view('pdf.diagram', compact('svgData')))
                    ->setOption('enable-javascript', true)
                    ->setOption('no-stop-slow-scripts', true)
                    ->setPaper('A4', 'portrait')
                    ->download('diagrama.pdf');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/inicio', [EditorController::class, 'inicio'])->name('inicio');

Route::post('/inicio', [EditorController::class, 'login'])->name('login.submit');


//New routes
Route::get('/materiasEditor', function () {
    return response()->json(MateriasOptativas::all());
});


Route::resource('materias', MateriasController::class); 
//Get materias
Route::get('/materiasGet', [MateriasController::class, 'getJSON']);


Route::resource('materias_optativas', MateriasOptativasController::class);
//Get materias optativas
Route::get('/materiasOptativasGet', [MateriasOptativasController::class, 'getJSON']);



Route::resource('grupos', GruposController::class);
//Get grupos
Route::get('/gruposGet', [GruposController::class, 'getJSON']);



//savestates

Route::get('/plan-estudios/{carrera}', [SaveStateController::class, 'get']);
Route::post('/plan-estudios', [SaveStateController::class, 'save']);


require __DIR__.'/auth.php';
