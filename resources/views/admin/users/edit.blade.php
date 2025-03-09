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
            <h4 class="card-title"><b>Editando Usuario</b> <i class="fas fa-user-pen"></i></h4>
        </div>
    </div>
@stop

@section('content')
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nombre del usuario</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" placeholder="Nombre del usuario" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="Correo electrónico" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="roles">Privilegio</label>
                <select name="roles" class="form-control">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @if($user->roles->contains($role->id)) selected @endif>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="school_id">Escuela</label>
                <select name="school_id" class="form-control" required>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @if($user->school_id == $school->id) selected @endif>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="grade_id">Grado</label>
                <select name="grade_id" class="form-control" required>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}" @if($user->grade_id == $grade->id) selected @endif>{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="group_id">Grupo</label>
                <select name="group_id" class="form-control" required>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" @if($user->group_id == $group->id) selected @endif>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="turno_id">Turno</label>
                <select name="turno_id" class="form-control" required>
                    @foreach ($turnos as $turno)
                        <option value="{{ $turno->id }}" @if($user->turno_id == $turno->id) selected @endif>{{ $turno->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-warning">Actualizar</button>
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
