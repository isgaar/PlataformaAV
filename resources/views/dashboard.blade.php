<?php /*
 *  dashboard.blade.php – Vista unificada con datos de prácticas simulados
 */ ?>

@extends('layouts.app')
@include('layouts.edit')

@section('content')
<div class="dashboard-container">
    {{-- ----------------------------------------------------
         ENCABEZADO (Común a docentes y estudiantes)
    ----------------------------------------------------- */--}}
    @if(auth()->user()->hasRole('student') || auth()->user()->hasRole('teacher'))
        <div class="header text-center">
            <img src="{{ asset('images/AGUA.png') }}" alt="Molécula" class="molecule-image">
            <h1>Bienvenido(a) a tu laboratorio virtual de química.</h1>
        </div>
    @endif

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-{{ auth()->user()->hasRole('admin') ? '9' : '10' }}">

                {{-- ----------------------------------------------------
                     PANEL DE ADMINISTRACIÓN
                ----------------------------------------------------- */--}}
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
                            @include('dashboard.partials.users-table')
                        </div>

                        <div class="d-flex justify-content-center" id="paginationContainer">
                            {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif

                {{-- ----------------------------------------------------
                     CONTENIDO  ESTUDIANTE / DOCENTE
                ----------------------------------------------------- */--}}
                @if(auth()->user()->hasRole('student') || auth()->user()->hasRole('teacher'))

                    {{-- Saludo --}}
                    <div class="student-info text-center mb-3">
                        <p class="h5">¡Hola, {{ auth()->user()->name }}!</p>
                    </div>

                    {{-- Tabs --}}
                    <div class="menu text-center mb-4">
                        <span class="tab-link active" data-target="#coursesTab">Cursos</span>
                        <span class="tab-link" data-target="#progressTab">Progreso</span>
                    </div>

                    {{-- *************************
                          TAB 1:  CURSOS
                       ************************* --}}
                    <div id="coursesTab" class="tab-pane show">
                        <div class="practices d-flex flex-wrap justify-content-center">
                            @foreach($practices as $practice)
                                <div class="practice-card border border-primary m-2 text-center p-3">
                                    <img src="{{ asset('images/' . $practice['image']) }}" alt="{{ $practice['title'] }}" class="mb-2" style="max-height: 100px;">
                                    <h3 class="h5">{{ $practice['title'] }}</h3>
                                    <div class="colored-line my-2"></div>
                                    <p class="small">{!! $practice['description'] !!}</p>
                                    <button class="btn btn-primary">
                                        <i class="bi bi-play-fill"></i> Iniciar
                                    </button>

                                    {{-- Extra buttons sólo para docentes --}}
                                    @role('teacher')
                                        <div class="mt-2">
                                            <a href="" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>
                                        </div>
                                    @endrole
                                </div>
                            @endforeach

                            {{-- Botón de "crear práctica" para docentes --}}
                            @role('teacher')
                                <div class="practice-card border border-primary m-2 text-center p-3 d-flex flex-column justify-content-center align-items-center" style="min-width: 230px;">
                                    <i class="bi bi-file-earmark-plus text-primary mb-3" style="font-size: 3rem;"></i>
                                    <h3 class="h6 mb-2">Crear nueva práctica</h3>
                                    <p class="small mb-3">Define una nueva práctica para tus estudiantes.</p>
                                    <button class="btn btn-lg btn-outline-primary" onclick="window.location.href=''">
                                        <i class="bi bi-plus-circle"></i> Crear práctica
                                    </button>
                                </div>
                            @endrole
                        </div>
                    </div>

                    {{-- *************************
                          TAB 2:  PROGRESO
                       ************************* --}}
                    <div id="progressTab" class="tab-pane" style="display:none;">
                        @role('teacher')
                            {{-- Vista de alumnos --}}
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Estudiantes</th>
                                            @foreach($practices as $p)
                                                <th>{{ $p['title'] }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $student)
                                            <tr>
                                                <td class="text-start ps-3">{{ $student->name }} {{ $student->last_name }} {{ $student->second_last_name }}</td>
                                                @foreach($practices as $p)
                                                    @php
                                                        $done = in_array($p['id'], is_array($student->done_practices) ? $student->done_practices : (array) $student->done_practices);
                                                    @endphp
                                                    <td class="fw-bold {{ $done ? 'text-success' : 'text-danger' }}">
                                                        {{ $done ? 'Realizada' : 'No realizada' }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endrole

                        @role('student')
                            {{-- Vista del propio progreso --}}
                            <h3 class="h5 mb-4 text-center">Mi progreso</h3>
                            <div class="progress-summary mx-auto" style="max-width:600px;">
                                @foreach($practices as $p)
                                    @php
                                        $done = in_array($p['id'], is_array(auth()->user()->done_practices) ? auth()->user()->done_practices : (array) auth()->user()->done_practices);
                                    @endphp
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="me-3" style="width: 180px;">{{ $p['title'] }}</span>
                                        <div class="progress flex-grow-1" role="progressbar" aria-label="{{ $p['title'] }}">
                                            <div class="progress-bar {{ $done ? 'bg-success' : 'bg-secondary' }}" style="width: {{ $done ? '100%' : '0%' }};">
                                                {{ $done ? 'Completado' : 'Pendiente' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endrole
                    </div>
                @endif  {{-- Fin contenido estudiante/docente --}}
            </div> {{-- col --}}
        </div> {{-- row --}}
    </div> {{-- container --}}
</div> {{-- dashboard-container --}}

{{-- ----------------------------------------------------
     SCRIPTS
----------------------------------------------------- --}}
<script>
    /**
     * Colorea las líneas de las cards de práctica.
     */
    document.addEventListener("DOMContentLoaded", function() {
        const colors = ["#b2be5c", "#ffd55c", "#c9c3f4"];
        document.querySelectorAll('.colored-line').forEach(line => {
            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            line.style.backgroundColor = randomColor;
        });

        // Manejo de tabs
        const tabs = document.querySelectorAll('.tab-link');
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                // Quitar "active" de todos
                tabs.forEach(t => t.classList.remove('active'));
                // Asignar "active" al clickeado
                this.classList.add('active');

                // Mostrar/Ocultar contenedores
                document.querySelectorAll('.tab-pane').forEach(p => p.style.display = 'none');
                const target = document.querySelector(this.getAttribute('data-target'));
                if(target) target.style.display = 'block';
            });
        });
    });

    /**
     * Cambia el role student/teacher en panel de admin sin recargar.
     */
    function toggleRole(selectedRole) {
        // Asegurar que sólo un switch esté activo
        ['student', 'teacher'].forEach(role => {
            document.getElementById(role + 'Switch').checked = (role === selectedRole);
        });

        // Petición AJAX
        fetch(`/dashboard?role=${selectedRole}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            const temp = document.createElement('div');
            temp.innerHTML = html;
            const newTable = temp.querySelector('#usersTableContainer');
            const newPagination = temp.querySelector('#paginationContainer');
            if(newTable && newPagination) {
                document.getElementById('usersTableContainer').innerHTML = newTable.innerHTML;
                document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
            }
        })
        .catch(err => console.error('Error en la carga de usuarios:', err));
    }
</script>
@endsection
