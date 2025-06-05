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

    <!-- Título y botón para agregar usuario -->
    <!-- Título y botón para agregar usuario -->
    <div class="usuarios-header">
        <h2 class="usuarios-title">
            <i class="fas fa-users"></i> Usuarios
        </h2>
        <button class="btn-practice usuarios-btn" onclick="window.location.href='{{ route('users.create') }}'">
            <i class="fas fa-user-plus"></i> Crear usuario
        </button>
    </div>


    <!-- Resto del código permanece igual -->
    <!-- Información de paginación y búsqueda -->
    <div class="row mb-3">
        <div class="col-md-7">
            @if ($users->isEmpty())
            <h5>No hay registros de usuarios</h5>
            @else
            <h5>{{ $users->total() }} Registro(s) encontrado(s). Página {{ $users->currentPage() }} de {{ $users->lastPage() }}. Registros por página: {{ $users->perPage() }}</h5>
            @endif
        </div>
        <div class="col-md-6 mx-auto">
            <form action="{{ route('users.index') }}" method="get">
                <div class="input-group">
                    <input type="text"
                        class="form-control form-control-lg"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar usuario">
                    <button class="btn btn-lg btn-primary" type="submit">Buscar</button>
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
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }} {{ $user->last_name }} {{ $user->second_last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->roles->isNotEmpty())
                        {{ $user->roles->first()->name }}
                        @else
                        Sin rol
                        @endif
                    </td>
                    <td>
                        @if($user->id !== auth()->user()->id)
                        <!-- Botón para eliminar usuario con modal -->
                        <button class="btn-danger" title="Eliminar usuario"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteUserModal"
                            data-userid="{{ $user->id }}"
                            data-username="{{ $user->name }} {{ $user->second_last_name }} {{ $user->last_name }}">
                            <i class="bi bi-trash"></i>
                        </button>

                        <!-- Botón para ver usuario -->
                        <button class="btn-success" title="Ver usuario" onclick="window.location.href='{{ route('users.show', $user) }}'">
                            <i class="bi bi-eye"></i>
                        </button>

                        <!-- Botón para editar usuario -->
                        <button class="btn-warning" title="Editar usuario" onclick="window.location.href='{{ route('users.edit', $user) }}'">
                            <i class="bi bi-pencil"></i>
                        </button>
                        @else
                        <span class="text-muted">Tu cuenta</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Cards visibles solo en móvil --}}
    <div class="user-cards-container d-md-none">
        @foreach($users as $user)
        <div class="user-card">
            <!-- Botón VER -->
            <a href="{{ route('users.show', $user->id) }}" class="ver-btn" title="Ver detalles">
                <i class="bi bi-eye"></i>
            </a>

            <!-- Botón EDITAR -->
            <a href="{{ route('users.edit', $user->id) }}" class="editar-btn" title="Editar">
                <i class="bi bi-pencil"></i>
            </a>

            <!-- Botón ELIMINAR (solo si no es el mismo usuario autenticado) -->
            @if($user->id !== auth()->user()->id)
            <button class="eliminar-btn" title="Eliminar"
                data-bs-toggle="modal"
                data-bs-target="#deleteUserModal"
                data-userid="{{ $user->id }}"
                data-username="{{ $user->name }} {{ $user->last_name }}">
                <i class="bi bi-trash"></i>
            </button>
            @endif

            <!-- Contenido de la card -->
            <h4>{{ $user->name }} {{ $user->last_name }} {{ $user->second_last_name }}</h4>
            <p><strong>Correo:</strong> {{ $user->email }}</p>
            <p><strong>Escuela:</strong> {{ $user->school->name ?? '-' }}</p>
            <p><strong>Grado:</strong> {{ $user->grade->name ?? '-' }}</p>
            <p><strong>Grupo:</strong> {{ $user->group->name ?? '-' }}</p>
        </div>
        @endforeach
    </div>


    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Incluir el modal de eliminación -->

@endsection