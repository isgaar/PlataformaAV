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
        <h3 class="form-title">{{ isset($user) ? 'Editando Usuario' : 'Perfil de nuevo usuario' }}</h3>
        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST" id="userForm">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="form-group">
                <input type="text" name="name" id="name" placeholder="Nombre del usuario*" value="{{ $user->name ?? '' }}" required oninput="validateText(this)">
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <input type="text" name="last_name" id="last_name" placeholder="Apellido Paterno*" value="{{ $user->last_name ?? '' }}" required oninput="validateText(this)">
                <input type="text" name="second_last_name" id="second_last_name" placeholder="Apellido Materno*" value="{{ $user->second_last_name ?? '' }}" oninput="validateText(this)">
            </div>

            <div class="form-group">
                <input type="email" name="email" id="email" placeholder="Correo electrónico*" value="{{ $user->email ?? '' }}" required>
                @error('email')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group password-container">
                <input type="password" name="password" id="password" placeholder="{{ isset($user) ? 'Dejar en blanco para no cambiar' : 'Contraseña*' }}" {{ isset($user) ? '' : 'required' }}>
                <button type="button" class="eye-button" onclick="togglePassword('password')"><i class="fas fa-eye-slash"></i></button>
            </div>

            <div class="form-group">
                <select name="roles" id="roles" required onchange="checkAdmin()">
                    <option value="">Asigne un privilegio*</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ isset($user) && $user->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <select name="school_id" id="school_id" required>
                    <option value="">Escuela*</option>
                    @foreach ($schools as $school)
                    <option value="{{ $school->id }}" {{ isset($user) && $user->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
                <select name="grade_id" id="grade_id" required>
                    <option value="">Grado*</option>
                    @foreach ($grades as $grade)
                    <option value="{{ $grade->id }}" {{ isset($user) && $user->grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                    @endforeach
                </select>
                <select name="group_id" id="group_id" required>
                    <option value="">Grupo*</option>
                    @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ isset($user) && $user->group_id == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
                <select name="turno_id" id="turno_id" required>
                    <option value="">Turno*</option>
                    @foreach ($turnos as $turno)
                    <option value="{{ $turno->id }}" {{ isset($user) && $user->turno_id == $turno->id ? 'selected' : '' }}>{{ $turno->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-buttons">
                <a href="{{ route('users.index') }}" class="back-btn">Atrás</a>
                <button type="submit" class="btn submit-btn">{{ isset($user) ? 'Actualizar' : 'Guardar' }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const passwordInput = document.getElementById("password");
        const eyeButton = document.querySelector(".eye-button");
        const eyeIcon = eyeButton.querySelector("i");
        const roleSelect = document.getElementById("roles");
        const submitButton = document.getElementById("submitButton");
        const requiredInputs = document.querySelectorAll('input[required], select[required]');
        const schoolFields = ['school_id', 'grade_id', 'group_id', 'turno_id'].map(id => document.getElementById(id));

        // Alternar visibilidad de la contraseña
        eyeButton.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            eyeIcon.classList.toggle("fa-eye", !isPassword);
            eyeIcon.classList.toggle("fa-eye-slash", isPassword);
        });

        // Validar campos de texto en tiempo real
        document.querySelectorAll('input[type="text"]').forEach(input => {
            input.addEventListener("input", () => {
                input.value = input.value
                    .replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')
                    .replace(/\b\w/g, char => char.toUpperCase()); // capitalizar cada palabra
                validateForm();
            });
        });

        // Manejar cambio en privilegios
        roleSelect.addEventListener("change", () => {
            const isAdmin = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase() === 'admin';
            schoolFields.forEach(field => field.disabled = isAdmin);
            validateForm();
        });

        // Validación del formulario para habilitar el botón de envío
        function validateForm() {
            const isAdmin = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase() === 'admin';
            const allFilled = Array.from(requiredInputs).every(input => {
                return input.disabled || input.value.trim() !== '';
            });
            submitButton.disabled = !(allFilled || isAdmin);
        }
    });
</script>


@stop