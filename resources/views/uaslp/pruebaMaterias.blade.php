<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CRUD Materias</title>
</head>
<body>
    <h1>CRUD Materias</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    {{-- CRERA --}}
    <h2>Crear Nueva Materia</h2>
    <form action="{{ route('materias.store') }}" method="POST" style="margin-bottom: 2em;">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="nombreMateria" value="{{ old('nombreMateria') }}" required>
        <label>Teoría:</label>
        <input type="number" name="horasTeoria" value="{{ old('horasTeoria') }}" required>
        <label>Práctica:</label>
        <input type="number" name="horasPractica" value="{{ old('horasPractica') }}" required>
        <label>Créditos:</label>
        <input type="number" name="creditos" value="{{ old('creditos') }}" required>
        <label>Clave:</label>
        <input type="text" name="claveMateria" value="{{ old('claveMateria') }}" required>
        <label>CACEI:</label>
        <input type="text" name="claveCacei" value="{{ old('claveCacei') }}">
        <label>Carrera (ID):</label>
        <input type="number" name="cve_Carrera" value="{{ old('cve_Carrera') }}" required>
        <label>Grupo:</label>
        <input type="text" name="grupo" value="{{ old('grupo_id') }}">
        <button type="submit">Crear</button>
    </form>

    <h2>Listado de Materias</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Teoría</th>
                <th>Práctica</th>
                <th>Créditos</th>
                <th>Clave</th>
                <th>CACEI</th>
                <th>Carrera</th>
                <th>Grupo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($materias as $m)
            <tr>
                {{-- UPDATE --}}
                <form action="{{ route('materias.update', $m) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <td><input type="text" name="nombreMateria"    value="{{ $m->nombreMateria }}"   required></td>
                    <td><input type="number" name="horasTeoria"      value="{{ $m->horasTeoria }}"     required></td>
                    <td><input type="number" name="horasPractica"    value="{{ $m->horasPractica }}"   required></td>
                    <td><input type="number" name="creditos"         value="{{ $m->creditos }}"        required></td>
                    <td><input type="text" name="claveMateria"       value="{{ $m->claveMateria }}"    required></td>
                    <td><input type="text" name="claveCacei"         value="{{ $m->claveCacei }}"></td>
                    <td><input type="number" name="cve_Carrera"      value="{{ $m->cve_Carrera }}"     required></td>
                    <td><input type="text" name="grupo" value="{{ $m->grupo->id ?? '' }}"></td>


                    <td style="white-space: nowrap;">
                        <button type="submit">Actualizar</button>
                    </td>
                </form>
                {{-- DELETE --}}
                <td>
                    <form action="{{ route('materias.destroy', $m) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('¿Eliminar esta materia?');">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9">No hay materias aún.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
