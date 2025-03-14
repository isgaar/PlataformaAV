@extends('layouts.app')

@section('content_header')
@if (Session::has('status'))
<div class="col-md-12 alert-section">
    <div class="alert alert-{{ Session::get('status_type') }}" style="text-align: center; padding: 5px; margin-bottom: 5px;">
        <span style="font-size: 20px; font-weight: bold;">
            {{ Session::get('status') }}
            @php
            Session::forget('status');
            @endphp
        </span>
    </div>
</div>
@endif
@stop

@section('content')
<div class="card-body">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-12">
            <div class="card card-primary">
                <div class="card-header bg-danger">
                    <h3 class="card-title">Perfil de nuevo empleado</h3>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="name">Nombre del usuario</label>
                            <input type="text" name="name" id="name" class="form-control" oninput="capitalizeInput(this)" required>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Apellidos -->
                        <div class="form-group">
                            <label for="last_name">Apellido Paterno</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                            @error('last_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="second_last_name">Apellido Materno</label>
                            <input type="text" name="second_last_name" id="second_last_name" class="form-control">
                            @error('second_last_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Correo electrónico -->
                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password')">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password_confirmation')">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Asignación de Rol -->
                        <div class="form-group">
                            <label for="roles">Asigne un privilegio</label>
                            <select name="roles" id="roles" class="form-control">
                                <option value="">Seleccione un privilegio</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('roles')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Selección de Escuela -->
                        <div class="form-group">
                            <label for="school_id">Escuela</label>
                            <select name="school_id" id="school_id" class="form-control" required>
                                <option value="">Seleccione una escuela</option>
                                @foreach ($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selección de Grado -->
                        <div class="form-group">
                            <label for="grade_id">Grado</label>
                            <select name="grade_id" id="grade_id" class="form-control" required>
                                <option value="">Seleccione un grado</option>
                                @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selección de Grupo -->
                        <div class="form-group">
                            <label for="group_id">Grupo</label>
                            <select name="group_id" id="group_id" class="form-control" required>
                                <option value="">Seleccione un grupo</option>
                                @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selección de Turno -->
                        <div class="form-group">
                            <label for="turno_id">Turno</label>
                            <select name="turno_id" id="turno_id" class="form-control" required>
                                <option value="">Seleccione un turno</option>
                                @foreach ($turnos as $turno)
                                <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const roleSelect = document.getElementById("roles");
                            const schoolSelect = document.getElementById("school_id");
                            const gradeSelect = document.getElementById("grade_id");
                            const groupSelect = document.getElementById("group_id");
                            const turnoSelect = document.getElementById("turno_id");

                            function toggleFields() {
                                const selectedRole = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();
                                const isAdmin = selectedRole === "admin";

                                schoolSelect.disabled = isAdmin;
                                gradeSelect.disabled = isAdmin;
                                groupSelect.disabled = isAdmin;
                                turnoSelect.disabled = isAdmin;

                                if (isAdmin) {
                                    schoolSelect.value = "";
                                    gradeSelect.value = "";
                                    groupSelect.value = "";
                                    turnoSelect.value = "";
                                }
                            }

                            roleSelect.addEventListener("change", toggleFields);
                            toggleFields(); // Ejecutar la función al cargar la página
                        });


                        function togglePasswordVisibility(fieldId) {
                            var field = document.getElementById(fieldId);
                            if (field.type === "password") {
                                field.type = "text";
                            } else {
                                field.type = "password";
                            }
                        }

                        function capitalizeInput(input) {
                            var value = input.value;
                            input.value = value.replace(/\b\w/g, function(l) {
                                return l.toUpperCase();
                            });
                        }
                    </script>

                    <div class="card-footer text-center">
                        <div class="d-flex justify-content-between">
                            <a type="button" href="{{ route('users.index') }}" class="btn btn-outline-dark">Retroceder</a>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@stop