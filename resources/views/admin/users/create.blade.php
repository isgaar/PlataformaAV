@extends('layouts.app')

@section('content_header')
    @if (Session::has('status'))
        <div class="alert alert-{{ Session::get('status_type') }}" style="text-align: center; padding: 5px; margin-bottom: 5px;">
            <span style="font-size: 20px; font-weight: bold;">
                {{ Session::get('status') }}
                @php
                    Session::forget('status');
                @endphp
            </span>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><b>Crear Nuevo Usuario</b> <i class="fas fa-user-plus"></i></h4>
        </div>
    </div>
@stop

@section('content')
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" name="name" class="form-control" placeholder="Nombre del usuario" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Apellido Paterno</label>
                <input type="text" name="last_name" class="form-control" placeholder="Apellido Paterno" required>
                @error('last_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="second_last_name">Apellido Materno</label>
                <input type="text" name="second_last_name" class="form-control" placeholder="Apellido Materno">
                @error('second_last_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="roles">Asigne un privilegio</label>
                <select name="roles" class="form-control">
                    <option value="">Seleccione un privilegio</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="school_id">Escuela</label>
                <select name="school_id" class="form-control" required>
                    <option value="">Seleccione una escuela</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="grade_id">Grado</label>
                <select name="grade_id" class="form-control" required>
                    <option value="">Seleccione un grado</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="group_id">Grupo</label>
                <select name="group_id" class="form-control" required>
                    <option value="">Seleccione un grupo</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="turno_id">Turno</label>
                <select name="turno_id" class="form-control" required>
                    <option value="">Seleccione un turno</option>
                    @foreach ($turnos as $turno)
                        <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
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
