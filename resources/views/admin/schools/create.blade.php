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
        <h3 class="form-title">Registrar nueva escuela</h3>
        <form action="{{ route('schools.store') }}" method="POST" id="schoolForm">
            @csrf

            {{-- Nombre --}}
            <div class="form-group">
                <input type="text" name="name" id="name" placeholder="Nombre de la escuela*" required oninput="validateText(this)">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Dirección --}}
            <div class="form-group">
                <input type="text" name="address" id="address" placeholder="Dirección (opcional)">
                @error('address')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="form-buttons">
                <a href="{{ route('schools.index') }}" class="back-btn">Atrás</a>
                <button type="submit" class="btn submit-btn" id="submitButton" disabled>Guardar</button>
            </div>

        </form>
    </div>
</div>

{{-- Scripts --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("schoolForm");
        const submitButton = document.getElementById("submitButton");
        const requiredInputs = form.querySelectorAll("input[required]");

        function validateText(input) {
            const cleaned = input.value
                .replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9.,'"“”‘’\s]/g, '') // permite letras, números, comas, puntos y comillas
                .replace(/\s+/g, ' ')
                .trimStart();

            input.value = cleaned.charAt(0).toUpperCase() + cleaned.slice(1);
            validateForm();
        }


        function validateForm() {
            const allFilled = Array.from(requiredInputs).every(input => input.value.trim() !== '');
            submitButton.disabled = !allFilled;
        }

        requiredInputs.forEach(input => input.addEventListener("input", validateForm));
        window.validateText = validateText;
        validateForm();
    });
</script>
@stop