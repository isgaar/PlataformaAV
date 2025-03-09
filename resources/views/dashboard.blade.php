@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <!-- Sidebar (Solo si el usuario es Admin) -->
        @if(auth()->user()->role === 'admin')
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        {{ __('Admin Panel') }}
                    </div>
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
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <br><br>
                    
                    <!-- Mostrar el rol del usuario -->
                    <strong>Tu rol es: </strong> 
                    @if(auth()->user()->roles->isNotEmpty())
                        {{ auth()->user()->getRoleNames()->first() }} 
                    @else
                        Sin rol asignado
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript para console.log -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(auth()->user()->role === 'admin')
            console.log("El usuario autenticado es un ADMIN.");
        @endif
    });
</script>

@endsection
