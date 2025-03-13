@extends('layouts.app')

@section('content')
<div class="container">
    @if (Session::has('status'))
        <div class="alert alert-{{ Session::get('status_type') }}" role="alert">
            <strong>{{ Session::get('status') }}</strong>
        </div>
        @php
            Session::forget('status');
        @endphp
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Detalles del Usuario</h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" value="{{ $user->name }}" readonly>
            </div>

            <div class="form-group">
                <label>Apellido Paterno</label>
                <input type="text" class="form-control" value="{{ $user->last_name }}" readonly>
            </div>

            <div class="form-group">
                <label>Apellido Materno</label>
                <input type="text" class="form-control" value="{{ $user->second_last_name ?? 'No registrado' }}" readonly>
            </div>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>

            <div class="form-group">
                <label>Privilegio</label>
                <input type="text" class="form-control" value="{{ $user->roles->isNotEmpty() ? $user->roles->first()->name : 'Sin privilegio' }}" readonly>
            </div>

            <div class="form-group">
                <label>Escuela</label>
                <input type="text" class="form-control" value="{{ $user->school->name ?? 'No asignada' }}" readonly>
            </div>

            <div class="form-group">
                <label>Grado</label>
                <input type="text" class="form-control" value="{{ $user->grade->name ?? 'No asignado' }}" readonly>
            </div>

            <div class="form-group">
                <label>Grupo</label>
                <input type="text" class="form-control" value="{{ $user->group->name ?? 'No asignado' }}" readonly>
            </div>

            <div class="form-group">
                <label>Turno</label>
                <input type="text" class="form-control" value="{{ $user->turno->nombre ?? 'No asignado' }}" readonly>
            </div>

            <div class="form-group text-center">
                <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Regresar</a>
            </div>
        </div>
    </div>
</div>
@endsection
