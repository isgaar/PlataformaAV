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

<style>
    body {
        font-family: 'Fira Sans', sans-serif;
        margin: 0;
        font-size: 1.2rem;
        padding: 0;
        background-color: #ffffff;
        color: #1B475D;
    }

    .login-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
    }


    .login-title {
        font-size: 24px;
        font-weight: bold;
        color: #003366;
        margin-bottom: 20px;
        text-align: center;
        position: relative;
    }

    .login-title::after,
    .login-title::before {
        content: "";
        display: block;
        width: 50px;
        height: 6px;
        background-color: #f4c542;
        position: absolute;
        top: 50%;
    }

    .login-title::before {
        left: -60px;
    }

    .login-title::after {
        right: -60px;
    }

    .login-icon {
        margin-bottom: 20px;
        text-align: center;
    }

    .input-group {
        text-align: left;
        width: 100%;
        margin-bottom: 15px;
    }

    .input-label {
        font-size: 14px;
        font-weight: bold;
        color: #333;
        display: block;
        margin-bottom: 5px;
    }

    .input-field {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #d6c5f0;
        font-size: 14px;
    }

    .password-container {
        display: flex;
        align-items: center;
        position: relative;
    }

    .eye-button {
        background: none;
        border: none;
        cursor: pointer;
        position: absolute;
        right: 10px;
        font-size: 1.2rem;
    }

    .eye-button i.dark-icon {
        color: #1B475D;
    }

    .login-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .login-button {
        background-color: #003366;
        color: white;
        padding: 10px;
        width: 48%;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
    }

    .login-button:hover {
        background-color: #002244;
    }

    .forgot-password {
        font-size: 12px;
        color: #555;
        text-decoration: none;
        width: 48%;
        text-align: left;
    }

    .register-text {
        font-size: 12px;
        color: #333;
        margin-top: 15px;
        text-align: center;
    }

    .register-text span {
        font-weight: bold;
        color: #003366;
    }

    .yellow-line {
        width: 350px;
        height: 3px;
        background-color: #f4c542;
        margin: 10px auto;
    }

    @media (max-width: 480px) {
        .login-actions {
            flex-direction: column;
        }

        .forgot-password {
            text-align: center;
        }

        .login-button,
        .forgot-password {
            width: 100%;
            margin-bottom: 10px;
        }


    }
</style>
@endsection