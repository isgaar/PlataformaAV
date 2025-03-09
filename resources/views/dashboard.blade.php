@extends('layouts.app')

@section('content')


<div class="dashboard-container">
    <!-- Cabecera -->
    <div class="header">
        <img src="{{ asset('storage/imagen.png') }}" alt="Molécula" class="molecule-image">
        <h1>Bienvenido(a) a tu laboratorio virtual de química.</h1>
    </div>
    
    <div class="row justify-content-center">
        <!-- Sidebar (Solo si el usuario es Admin) -->
        @if(auth()->user()->role === 'admin')
            <div class="col-md-3">
                <div class="card">
                    
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="{{ route('users.index') }}" class="text-decoration-none">Gestionar Usuarios</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido Principal -->
        <div class="{{ auth()->user()->role === 'admin' ? 'col-md-9' : 'col-md-8' }}">
            <!-- Información del estudiante -->
            <div class="student-info">
                <p><strong>¡Hola, {{ auth()->user()->name }}!</strong></p>  <!-- Nombre del usuario -->
            </div>
            
            <!-- Menú de navegación -->
            <div class="menu">
                <span class="active">Cursos</span>
                <span>Progreso</span>
            </div>

            <!-- Sección de prácticas -->
            <div class="practices">
                <div class="practice-card">
                    <img src="{{ asset('storage/molecula1.png') }}" alt="Molécula">
                    <h3>Práctica 01</h3>
                    <p>Descripción</p>
                    <p><strong>Nivel:</strong> Fácil</p>
                    <button>▶ Iniciar</button>
                </div>
                <div class="practice-card">
                    <img src="{{ asset('storage/molecula2.png') }}" alt="Molécula">
                    <h3>Práctica 02</h3>
                    <p>Descripción</p>
                    <p><strong>Nivel:</strong> Fácil</p>
                    <button>▶ Iniciar</button>
                </div>
                <div class="practice-card">
                    <img src="{{ asset('storage/molecula3.png') }}" alt="Molécula">
                    <h3>Práctica 03</h3>
                    <p>Descripción</p>
                    <p><strong>Nivel:</strong> Fácil</p>
                    <button>▶ Iniciar</button>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection
