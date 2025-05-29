@extends('layouts.app')

@section('content_header')
@include('layouts.edit')

@if (Session::has('status'))
<div class="alert-section">
    <div class="alert alert-{{ Session::get('status_type') }}">
        <span>{{ Session::get('status') }}</span>
        @php Session::forget('status'); @endphp
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

            {{-- Nombre --}}
            <div class="form-group">
                <input type="text" name="name" id="name" placeholder="Nombre del usuario*" required oninput="validateText(this)">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Apellidos --}}
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

            {{-- Email --}}
            <div class="form-group">
                <input type="email" name="email" id="email" placeholder="Correo electrónico*" required>
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Contraseña --}}
            <div class="form-group password-container">
                <input type="password" name="password" id="password" placeholder="Contraseña*" required>
                <div class="password-buttons">
                    <button type="button" class="eye-button" onclick="togglePassword()">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                    <button
                        type="button"
                        class="generate-button"
                        onclick="generatePassword()"
                        title="Generar una contraseña aleatoria de forma segura">
                        <i class="fas fa-key"></i> Generar
                    </button>

                </div>
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Rol --}}
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

            {{-- Escuela, Grado, Grupo, Turno --}}
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

            {{-- Botones --}}
            <div class="form-buttons">
                <a href="{{ route('users.index') }}" class="back-btn">Atrás</a>
                <button type="submit" class="btn submit-btn" id="submitButton" disabled>Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("userForm");
        const submitButton = document.getElementById("submitButton");
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.querySelector(".eye-button i");
        const roleSelect = document.getElementById("roles");
        const schoolSelect = document.getElementById("school_id");
        const gradeSelect = document.getElementById("grade_id");
        const groupSelect = document.getElementById("group_id");
        const turnoSelect = document.getElementById("turno_id");
        const requiredInputs = form.querySelectorAll("input[required], select[required]");

        const isAdmin = () =>
            roleSelect.options[roleSelect.selectedIndex]?.text.toLowerCase() === 'admin';

        window.togglePassword = function() {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            eyeIcon.classList.toggle("fa-eye");
            eyeIcon.classList.toggle("fa-eye-slash");
        }

        window.generatePassword = function() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            let password = getRandomChar("0123456789");

            for (let i = 1; i < length; i++) {
                password += getRandomChar(charset);
            }

            password = shuffleString(password);
            passwordInput.value = password;
            validateForm();
        }

        function getRandomChar(charSet) {
            return charSet[Math.floor(Math.random() * charSet.length)];
        }

        function shuffleString(string) {
            return string.split('').sort(() => Math.random() - 0.5).join('');
        }

        window.validateText = function(input) {
            const cleaned = input.value
                .replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')
                .replace(/\s+/g, ' ')
                .trimStart();

            input.value = cleaned.charAt(0).toUpperCase() + cleaned.slice(1);
            validateForm();
        }

        window.checkAdmin = function() {
            const disabled = isAdmin();
            [schoolSelect, gradeSelect, groupSelect, turnoSelect].forEach(select => {
                select.disabled = disabled;
            });
            validateForm();
        }

        function validateForm() {
            const allFilled = Array.from(requiredInputs).every(input =>
                input.disabled || input.value.trim() !== ''
            );
            submitButton.disabled = !(allFilled || isAdmin());
        }

        roleSelect.addEventListener("change", checkAdmin);
        requiredInputs.forEach(input => input.addEventListener("input", validateForm));

        validateForm();
    });
</script>
@stop