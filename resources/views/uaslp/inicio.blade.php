<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Carreras</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
    <!-- Estilos uaslp -->
    <link href="{{ asset('content/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('content/OwnStyles/fuentes.css') }}" rel="stylesheet">
    <link href="{{ asset('content/OwnStyles/Site.css') }}" rel="stylesheet">
    <link href="{{ asset('content/OwnStyles/cards.css') }}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    <script type="text/javascript">
 
        $(function () {
            window.onscroll = function () { scrollFunction() };
        });
    
    
        function scrollFunction() {
            if ($(window).scrollTop() > 100) {
                $(".logoUASLP").removeClass("d-block").addClass("d-none");
                $(".textoUASLP").removeClass("d-none").addClass("d-block");
                $(".divisorUASLP-ENTIDAD").removeClass("d-block").addClass("d-none");
                $(".divisorUASLP-ENTIDADScroll").removeClass("d-none").addClass("d-block");
                $(".header").css("height", "85px");
    
            }
            if ($(window).scrollTop() < 40) {
                $(".logoUASLP").removeClass("d-none").addClass("d-block");
                $(".textoUASLP").removeClass("d-block").addClass("d-none");
                $(".divisorUASLP-ENTIDAD").removeClass("d-none").addClass("d-block");
                $(".divisorUASLP-ENTIDADScroll").removeClass("d-block").addClass("d-none");
                $(".header").css("height", "120px");
            }
        }
    </script>

</head>
<body>
    @include('components.banner')

    <div class="container my-5">
        <h1 class="mb-4 text-center">Listado de Carreras - Licenciatura</h1>
        <!-- Barra de búsqueda -->
        <div class="mb-4">
            <input type="text" id="search-input" class="form-control" placeholder="Buscar carrera...">
        </div>
        <div class="row" id="carreras-container">
            @foreach($carreras as $carrera)
                <div class="col-lg-4 col-md-6 mb-4 card-item">
                    {{-- <a href="{{ route('escoger.carrera', ['carrera' => $carrera->cve_carrera]) }}" class="text-decoration-none text-dark"> --}}
                    <a  class="text-decoration-none text-dark">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <h5 class="card-title text-center">{{ $carrera->carrera }}</h5>
                                <p class="card-text text-center">CVE: {{ $carrera->cve_carrera }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 text-center">
                                <a href="{{ route('editor2', ['carrera' => $carrera->cve_carrera]) }}" class="btn btn-primary">Ver plan de estudios</a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
}    <script>
        $(document).ready(function() {
            $("#search-input").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".card-item").filter(function() {
                    var text = $(this).text().toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    $(this).toggle(text.indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>
