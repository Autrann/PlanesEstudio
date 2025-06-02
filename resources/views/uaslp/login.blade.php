@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div>
        <img src="{{ asset('/Images/encabezado_ingenieria.jpg') }}" alt="Encabezado" class="w-full">
    </div>

    <!-- formulario -->
    <div class="flex items-center justify-center mt-10">
        <div class="w-full max-w-md bg-white shadow-lg rounded-lg overflow-hidden p-8">
            <h2 class="text-2xl font-semibold text-gray-700 text-center">Inicio de Sesión</h2>
            <form method="POST" action="{{ route('login.submit') }}" class="mt-4">
                @csrf
            
                <!-- Mensaje de error -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif
            
                <div class="mb-4">
                    <label for="rpe" class="block text-gray-700 font-medium mb-2">RPE</label>
                    <input type="text" name="rpe" id="rpe" placeholder="Registro permanente de empleados"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Contraseña"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            
                <div class="flex justify-end">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                        Ingresar
                    </button>
                </div>
            </form>
            
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-200 text-center py-4 mt-10">
        <h6 class="text-sm text-gray-700">Facultad de Ingeniería, UASLP</h6>
        <h6 class="text-sm text-gray-700">Dr. Manuel Nava # 8, Zona Universitaria Poniente</h6>
        <h6 class="text-sm text-gray-700">Tels. (444) 826.3300 al 6000</h6>
        <h6 class="text-sm text-gray-700">
            <a href="http://www.ingenieria.uaslp.mx" class="text-blue-600 hover:underline">www.ingenieria.uaslp.mx</a>
        </h6>
    </div>
</div>
@endsection
