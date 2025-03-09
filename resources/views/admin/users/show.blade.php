@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Mensaje de estado -->
    @if (Session::has('status'))
    <div class="alert alert-{{ Session::get('status_type') }}" role="alert">
        <strong>{{ Session::get('status') }}</strong>
    </div>
    @php
    Session::forget('status');
    @endphp
    @endif

    <!-- Detalles del usuario -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Detalles del Usuario</h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nombre del usuario</label>
                <input type="text" class="form-control" value="{{ $user->name }}" readonly>
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>

            <div class="form-group">
                <label for="roles">Privilegio</label>
                <input type="text" class="form-control" value="{{ $user->roles->isNotEmpty() ? $user->roles->first()->name : 'Sin privilegio' }}" readonly>
            </div>

            <div class="form-group">
                <label for="school">Escuela</label>
                <input type="text" class="form-control" value="{{ $user->school->name ?? 'No asignada' }}" readonly>
            </div>

            <div class="form-group">
                <label for="grade">Grado</label>
                <input type="text" class="form-control" value="{{ $user->grade->name ?? 'No asignado' }}" readonly>
            </div>

            <div class="form-group">
                <label for="group">Grupo</label>
                <input type="text" class="form-control" value="{{ $user->group->name ?? 'No asignado' }}" readonly>
            </div>

            <div class="form-group">
                <label for="turno">Turno</label>
                <input type="text" class="form-control" value="{{ $user->turno->nombre ?? 'No asignado' }}" readonly>
            </div>


            <div class="form-group text-center">
                <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Regresar</a>
            </div>
        </div>
    </div>
</div>
@endsection