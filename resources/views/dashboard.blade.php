@extends('layouts.app')
@include('layouts.edit')

@section('content')
<div class="dashboard-container">


    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-{{ auth()->user()->hasRole('admin') ? '9' : '8' }}">

                @if(auth()->user()->hasRole('admin'))
                <div class="admin-panel">
                    <button class="btn-practice mb-3" onclick="window.location.href='{{ route('users.index') }}'">
                        <i class="bi bi-people"></i> Gestionar Usuarios
                    </button>

                    <div class="d-flex align-items-center mb-3">
                        <span class="fw-bold me-2">Mostrar:</span>
                        @foreach(['student' => 'Estudiantes', 'teacher' => 'Maestros'] as $key => $label)
                        <div class="d-flex align-items-center me-3">
                            <label class="fw-bold me-2">{{ $label }}</label>
                            <label class="switch">
                                <input type="checkbox" id="{{ $key }}Switch" onchange="toggleRole('{{ $key }}')" {{ $role == $key ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="table-responsive" id="usersTableContainer">
                        <table class="table table-hover custom-table text-center">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Escuela</th>
                                    <th>Grado</th>
                                    <th>Grupo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }} {{ $user->last_name }} {{ $user->second_last_name }}</td>
                                    <td>{{ $user->school->name ?? '-' }}</td>
                                    <td>{{ $user->grade->name ?? '-' }}</td>
                                    <td>{{ $user->group->name ?? '-' }}</td>
                                    <td>
                                        <button class="btn-success btn-sm" title="Ver usuario">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center" id="paginationContainer">
                        {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif

                @if(auth()->user()->hasRole('student') || auth()->user()->hasRole('teacher'))

                <!-- Cabecera -->
                <div class="header text-center">
                    <img src="{{ asset('images/AGUA.png') }}" alt="Molécula" class="molecule-image">
                    <h1>Bienvenido(a) a tu laboratorio virtual de química.</h1>
                </div>
                <div class="student-info text-center">
                    <p><strong>¡Hola, {{ auth()->user()->name }}!</strong></p>
                </div>

                <div class="menu text-center">
                    <span class="active">Cursos</span>
                    <span>Progreso</span>
                </div>

                <div class="practices d-flex flex-wrap justify-content-center">
                    @foreach(['AGUA', 'BUTANO', 'PROPANO'] as $molecule)
                    <div class="practice-card border border-primary m-2 text-center p-3">
                        <img src="{{ asset('images/' . $molecule . '.png') }}" alt="Molécula" class="mb-2">
                        <h3>Práctica {{ $molecule }}</h3>
                        <p>Descripción</p>
                        <p><strong>Nivel:</strong> Fácil</p>
                        <button class="btn btn-primary">
                            <i class="bi bi-play-fill"></i> Iniciar
                        </button>
                    </div>
                    @endforeach

                    @if(auth()->user()->hasRole('teacher'))
                    <div class="practice-card border border-primary m-2 text-center p-3">
                        <div class="mb-3">
                            <i class="bi bi-file-earmark-plus text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h3>Crear nueva práctica</h3>
                        <p>Define una nueva práctica para tus estudiantes.</p>
                        <p><strong>Nivel:</strong> Personalizado</p>
                        <button class="btn btn-lg btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Crear práctica
                        </button>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function toggleRole(selectedRole) {
        // Asegurar que solo un checkbox esté activo
        let roles = ['student', 'teacher'];
        roles.forEach(role => {
            document.getElementById(role + 'Switch').checked = (role === selectedRole);
        });

        // Realizar una solicitud AJAX sin recargar la página
        fetch(`/dashboard?role=${selectedRole}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Indicar que es una solicitud AJAX
                }
            })
            .then(response => response.text())
            .then(html => {
                // Crear un div temporal para procesar la respuesta y extraer solo la tabla
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                // Obtener solo la parte de la tabla de usuarios
                let newTable = tempDiv.querySelector('#usersTableContainer');
                let newPagination = tempDiv.querySelector('#paginationContainer');

                if (newTable && newPagination) {
                    document.getElementById('usersTableContainer').innerHTML = newTable.innerHTML;
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }
            })
            .catch(error => console.error('Error en la carga de usuarios:', error));
    }
</script>
@endsection