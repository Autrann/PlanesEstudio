<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CRUD Materias Optativas</title>
</head>
<body>
    <h1>CRUD Materias Optativas</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    {{-- CREATE --}}
    <h2>Crear Nueva Materia Optativa</h2>
    <form action="{{ route('materias_optativas.store') }}" method="POST" style="margin-bottom: 2em;">
        @csrf
        <label>Nombre:</label>
        <input type="text" name="nombreMateria" value="{{ old('nombreMateria') }}" required>
        <label>Teoría:</label>
        <input type="number" name="horasTeoria" value="{{ old('horasTeoria') }}" required>
        <label>Práctica:</label>
        <input type="number" name="horasPractica" value="{{ old('horasPractica') }}" required>
        <label>Créditos:</label>
        <input type="number" name="creditos" value="{{ old('creditos') }}" required>
        <label>Clave Materia:</label>
        <input type="text" name="claveMateria" value="{{ old('claveMateria') }}" required>
        <label>Clave CACEI:</label>
        <input type="text" name="claveCacei" value="{{ old('claveCacei') }}" required>
        <label>Carrera (ID):</label>
        <input type="number" name="cve_carrera" value="{{ old('cve_carrera') }}" required>
        <button type="submit">Crear</button>
    </form>

    <h2>Listado de Materias Optativas</h2>
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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($optativas as $opt)
            <tr>
                {{-- UPDATE --}}
                <form action="{{ route('materias_optativas.update', $opt) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <td>
                        <input type="text" name="nombreMateria" value="{{ $opt->nombreMateria }}" required>
                    </td>
                    <td>
                        <input type="number" name="horasTeoria" value="{{ $opt->horasTeoria }}" required>
                    </td>
                    <td>
                        <input type="number" name="horasPractica" value="{{ $opt->horasPractica }}" required>
                    </td>
                    <td>
                        <input type="number" name="creditos" value="{{ $opt->creditos }}" required>
                    </td>
                    <td>
                        <input type="text" name="claveMateria" value="{{ $opt->claveMateria }}" required>
                    </td>
                    <td>
                        <input type="text" name="claveCacei" value="{{ $opt->claveCacei }}" required>
                    </td>
                    <td>
                        <input type="number" name="cve_carrera" value="{{ $opt->cve_carrera }}" required>
                    </td>
                    <td style="white-space: nowrap;">
                        <button type="submit">Actualizar</button>
                    </td>
                </form>
                {{-- dlete --}}
                <td>
                    <form action="{{ route('materias_optativas.destroy', $opt) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('¿Eliminar esta materia optativa?');">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9">No hay materias optativas aún.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
