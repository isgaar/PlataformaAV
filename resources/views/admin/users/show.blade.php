@extends('layouts.app')

@section('content_header')
@include('layouts.edit')

@if (Session::has('status'))
<div class="alert-section">
    <div class="alert alert-{{ Session::get('status_type') }}">
        <span>{{ Session::get('status') }}</span>
        @php
        Session::forget('status');
        @endphp
    </div>
</div>
@endif
@stop

@section('content')
<div class="form-container">
    <div class="form-card">
        <h3 class="form-title">Detalles del Usuario</h3>

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" value="{{ $user->name }}" readonly>
        </div>

        <div class="form-group">
            <label>Apellido Paterno</label>

            <input type="text" value="{{ $user->last_name }}" readonly>

            <label>Apellido Materno</label>
            <input type="text" value="{{ $user->second_last_name ?? 'No registrado' }}" readonly>
        </div>

        <div class="form-group">
            <label>Correo</label>
            <input type="email" value="{{ $user->email }}" readonly>

            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="showPasswordBtn" onclick="togglePasswordVisibility('password')">
                    <i class="fa fa-eye"></i>
                </button>
            </div>

        </div>



        <div class="form-group">
            <label>Privilegio</label>
            <input type="text" value="{{ $user->roles->isNotEmpty() ? $user->roles->first()->name : 'Sin privilegio' }}" readonly>
        </div>

        <div class="form-group">
            <label>Escuela</label>
            <input type="text" value="{{ $user->school->name ?? 'No asignada' }}" readonly>
            <label>Grado</label>
            <input type="text" value="{{ $user->grade->name ?? 'No asignado' }}" readonly>
        </div>

        <div class="form-group">
            <label>Grupo</label>
            <input type="text" value="{{ $user->group->name ?? 'No asignado' }}" readonly>
            <label>Turno</label>
            <input type="text" value="{{ $user->turno->nombre ?? 'No asignado' }}" readonly>
        </div>


        <div class="form-buttons">
            <button onclick="window.location.href='{{ route('users.index') }}'" class="btn back-btn">Atrás</button>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(fieldId) {
        var field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text";
        } else {
            field.type = "password";
        }
    }
</script>
@stop