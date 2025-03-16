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
        <h3 class="form-title">Perfil de nuevo usuario</h3>
        <form action="{{ route('users.store') }}" method="POST" id="userForm">
            @csrf
            
            <div class="form-group">
                <input type="text" name="name" id="name" placeholder="Nombre del usuario*" required oninput="validateText(this)">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <input type="text" name="last_name" id="last_name" placeholder="Apellido Paterno*" required oninput="validateText(this)">
                @error('last_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <input type="text" name="second_last_name" id="second_last_name" placeholder="Apellido Materno*" oninput="validateText(this)">
                @error('second_last_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="email" name="email" id="email" placeholder="Correo electrónico*" required>
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group password-container">
                <input type="password" name="password" id="password" placeholder="Contraseña*" required>
                <button type="button" class="eye-button" onclick="togglePassword('password')">
                    <i class="fas fa-eye-slash"></i> <!-- Ícono inicial: ojo cerrado -->
                </button>
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <select name="roles" id="roles" required onchange="checkAdmin()">
                    <option value="">Asigne un privilegio*</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('roles')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <select name="school_id" id="school_id" required>
                    <option value="">Escuela*</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
                <select name="grade_id" id="grade_id" required>
                    <option value="">Grado*</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
                <select name="group_id" id="group_id" required>
                    <option value="">Grupo*</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
                <select name="turno_id" id="turno_id" required>
                    <option value="">Turno*</option>
                    @foreach ($turnos as $turno)
                        <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-buttons">
                <a href="{{ route('users.index') }}" class="btn back-btn">Atrás</a>
                <button type="submit" class="btn submit-btn" id="submitButton" disabled>Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        let passwordInput = document.getElementById("password");
        let eyeIcon = document.querySelector(".eye-button i");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }

    function validateText(input) {
        input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '');
        input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1);
        validateForm();
    }

    function checkAdmin() {
        var isAdmin = document.getElementById('roles').options[document.getElementById('roles').selectedIndex].text.toLowerCase() === 'admin';
        document.getElementById('school_id').disabled = isAdmin;
        document.getElementById('grade_id').disabled = isAdmin;
        document.getElementById('group_id').disabled = isAdmin;
        document.getElementById('turno_id').disabled = isAdmin;
        validateForm();
    }

    function validateForm() {
        var isAdmin = document.getElementById('roles').options[document.getElementById('roles').selectedIndex].text.toLowerCase() === 'admin';
        var inputs = document.querySelectorAll('input[required], select[required]');
        var allFilled = true;
        
        inputs.forEach(function(input) {
            if (input.disabled === false && input.value.trim() === '') {
                allFilled = false;
            }
        });
        
        document.getElementById('submitButton').disabled = !(allFilled || isAdmin);
    }
</script>

@stop