<!DOCTYPE html>
<html>
<head>
    <title>Diagrama PDF</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        img { max-width: 100%; }
    </style>
</head>
<body>
    <h1>Diagrama Generado</h1>

    @if($svgData)
        <img src="data:image/svg+xml;base64,{{ base64_encode($svgData) }}" />
    @else
        <p>Error: No se pudo cargar el diagrama.</p>
    @endif
</body>
</html>
