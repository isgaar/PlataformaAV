@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-container">
    <h2 class="login-title">¡Bienvenido(a)!</h2>
    <div class="login-card">
        <div class="login-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#0A4D5F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zM4 20c0-3.313 3.134-6 7-6h2c3.866 0 7 2.687 7 6v2H4v-2z" />
            </svg>
        </div>

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            <div class="input-group">
                <label for="email" class="input-label">Correo electrónico / Usuario</label>
                <input id="email" type="email" class="input-field @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>

            <div class="input-group">
                <label for="password" class="input-label">Contraseña</label>
                <div class="password-container">
                    <input id="password" type="password" class="input-field @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    <button type="button" class="eye-button" onclick="togglePassword()">
                        <i class="fas fa-eye dark-icon"></i>
                    </button>
                </div>
                @error('password')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </div>

            <div class="login-actions">
                <a href="{{ route('password.request') }}" class="forgot-password">¿Has olvidado tu contraseña?</a>
                <button type="submit" class="login-button">
                    Iniciar Sesión
                </button>
            </div>

            <p class="register-text">¿No tienes cuenta? <br> <span>Pide al administrador te registre</span></p>
            <div class="yellow-line"></div>
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
</script>


@endsection
