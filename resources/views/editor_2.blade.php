<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con React</title>

    {{-- Vite: para incluir CSS y JS con React --}}
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<style>
    body{
        width: 100vw;
        height: 100vh;
    }
    .app{
        width: 100%;
        height: 100%;
    }
</style>
<body>
    {{-- Div contenedor donde React montará el componente --}}
    <div id="app" class="app" data-carrera="{{ $carrera }}"></div>


    </div>
</body>
</html>
