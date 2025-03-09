@extends('layouts.app')

@section('content')
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

    <!-- Título y botón para agregar nuevo usuario -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Usuarios</h2>
        <a href="{{ route('users.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Información de paginación -->
    <div class="row mb-3">
        <div class="col-md-7">
            @if ($users->isEmpty())
                <h5>No hay registros de usuarios</h5>
            @else
                <h5>{{ $users->total() }} Registro(s) encontrado(s). Página {{ $users->currentPage() }} de {{ $users->lastPage() }}. Registros por página: {{ $users->perPage() }}</h5>
            @endif
        </div>
        <div class="col-md-5 text-right">
            <form action="{{ route('users.index') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Buscar usuario">
                    <div class="input-group-append">
                        <button class="btn btn-outline-info" type="submit">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <table class="table table-bordered table-striped">
        <thead class="thead-light">
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
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->roles->isNotEmpty())
                        {{ $user->roles->first()->name }}
                    @else
                        Sin rol
                    @endif
                </td>
                <td>
                    <a href="{{ route('users.show', $user) }}" class="btn btn-info" title="Ver detalles del usuario">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning" title="Editar usuario">
                        <i class="fas fa-pen"></i>
                    </a>
                    <a href="{{ route('users.destroy', $user) }}" class="btn btn-danger" title="Eliminar usuario" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                        <i class="fas fa-trash"></i>
                    </a>
                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
