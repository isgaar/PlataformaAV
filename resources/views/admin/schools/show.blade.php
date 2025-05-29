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
        <h3 class="form-title">Detalles de la Escuela</h3>

        {{-- Nombre --}}
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" value="{{ $school->name }}" readonly>
        </div>

        {{-- Dirección --}}
        <div class="form-group">
            <label>Dirección</label>
            <input type="text" value="{{ $school->address ?? 'No registrada' }}" readonly>
        </div>

        {{-- Usuarios relacionados --}}
        <label>Usuarios Registrados</label>
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($school->users as $index => $user)
                    <tr>
                        <td>{{ $user->name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->first()->name ?? 'Sin rol' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No hay usuarios registrados en esta escuela.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>



        {{-- Botón de regreso --}}
        <div class="form-buttons">
            <a href="{{ route('schools.index') }}" class="back-btn">Atrás</a>
        </div>
    </div>
</div>
@stop