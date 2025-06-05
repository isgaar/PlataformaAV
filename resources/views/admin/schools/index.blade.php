@extends('layouts.app')

@section('content')
@include('layouts.edit')

<div class="container">
    <!-- Mensaje de estado -->
    @if (Session::has('status'))
    <div class="alert alert-{{ Session::get('status_type') }}" role="alert">
        <strong>{{ Session::get('status') }}</strong>
    </div>
    @php
    Session::forget('status');
    @endphp
    @endif

    <!-- Título y botón para agregar escuela -->
    <div class="usuarios-header">
        <h2 class="usuarios-title">
            <i class="fas fa-school"></i> Escuelas
        </h2>
        <button class="btn-practice usuarios-btn" onclick="window.location.href='{{ route('schools.create') }}'">
            <i class="fas fa-plus"></i> Crear escuela
        </button>
    </div>

    <!-- Información de paginación y búsqueda -->
    <div class="row mb-3">
        <div class="col-md-7">
            @if ($schools->isEmpty())
            <h5>No hay registros de escuelas</h5>
            @else
            <h5>{{ $schools->total() }} Registro(s) encontrado(s). Página {{ $schools->currentPage() }} de {{ $schools->lastPage() }}. Registros por página: {{ $schools->perPage() }}</h5>
            @endif
        </div>
        <div class="col-md-6 mx-auto">
            <form action="{{ route('schools.index') }}" method="get" class="search-form">
                <div class="search-group">
                    <label for="search-input" class="visually-hidden">Buscar escuela</label>
                    <input
                        type="text"
                        id="search-input"
                        class="search-input"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar escuela">
                    <button class="search-button" type="submit">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla visible solo en escritorio -->
    <div class="table-responsive d-none d-md-block" id="usersTableContainer">
        <table class="table table-hover custom-table text-center">
            <thead class="bg-primary text-white">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                <tr>
                    <td>{{ $school->id }}</td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->address }}</td>
                    <td>
                        <button class="btn-danger" title="Eliminar escuela"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteSchoolModal"
                            data-schoolid="{{ $school->id }}"
                            data-schoolname="{{ $school->name }}">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn-success" title="Ver escuela" onclick="window.location.href='{{ route('schools.show', $school) }}'">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn-warning" title="Editar escuela" onclick="window.location.href='{{ route('schools.edit', $school) }}'">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Cards visibles solo en móvil -->
    <div class="user-cards-container d-md-none">
        @foreach($schools as $school)
        <div class="user-card">
            <!-- Botones alineados verticalmente -->
            <div class="user-card-buttons">
                <!-- Ver -->
                <a href="{{ route('schools.show', $school->id) }}" class="btn ver-btn" title="Ver escuela">
                    <i class="bi bi-eye"></i>
                </a>

                <!-- Editar -->
                <a href="{{ route('schools.edit', $school->id) }}" class="btn editar-btn" title="Editar escuela">
                    <i class="bi bi-pencil"></i>
                </a>

                <!-- Eliminar -->
                <button class="btn eliminar-btn" title="Eliminar escuela"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteSchoolModal"
                    data-schoolid="{{ $school->id }}"
                    data-schoolname="{{ $school->name }}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <!-- Contenido de la card -->
            <h4>{{ $school->name }}</h4>
            <p><strong>Dirección:</strong> {{ $school->address }}</p>
            <p><strong>ID:</strong> {{ $school->id }}</p>
        </div>
        @endforeach
    </div>


    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $schools->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection