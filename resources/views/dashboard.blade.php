<?php /*
 *  dashboard.blade.php – Vista unificada con datos de prácticas simulados
 */ ?>

@extends('layouts.app')
@include('layouts.edit')

@section('content')
<div class="dashboard-container">
    {{-- ENCABEZADO (Común a docentes y estudiantes) --}}
    @if(auth()->user()->hasRole('student') || auth()->user()->hasRole('teacher'))
    <div class="header text-center">
        <img src="{{ asset('images/AGUA.png') }}" alt="Molécula" class="molecule-image">
        <h1>Bienvenido(a) a tu laboratorio virtual de química.</h1>
    </div>
    @endif

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-{{ auth()->user()->hasRole('admin') ? '9' : '10' }}">

                {{-- PANEL DE ADMINISTRACIÓN --}}
                @if(auth()->user()->hasRole('admin'))
                <div class="admin-panel">
                    {{-- Botones lado a lado --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                        <button class="btn-practice w-100 w-md-50"
                            onclick="window.location.href='{{ route('users.index') }}'"
                            title="Acceder a la gestión de usuarios registrados">
                            <i class="bi bi-people"></i> Gestionar Usuarios
                        </button>

                        <button class="btn-practice w-100 w-md-50"
                            onclick="window.location.href='{{ route('schools.index') }}'"
                            title="Administrar y registrar información de escuelas">
                            <i class="bi bi-building me-2"></i> Gestionar Escuelas
                        </button>
                    </div>

                    {{-- Filtros con switches --}}
                    <div class="filters-container d-flex flex-column flex-md-row align-items-center flex-wrap gap-3 mb-4">
                        <span class="fw-bold text-secondary filter-label">Mostrar:</span>

                        <div class="switches-wrapper d-flex flex-row flex-wrap gap-3">
                            @foreach(['student' => 'Estudiantes', 'teacher' => 'Maestros'] as $key => $label)
                            <div class="switch-item d-flex align-items-center justify-content-between">
                                <label for="{{ $key }}Switch" class="fw-medium me-2 mb-0">{{ $label }}</label>
                                <label class="switch mb-0">
                                    <input type="checkbox"
                                        id="{{ $key }}Switch"
                                        onchange="toggleRole('{{ $key }}')"
                                        {{ $role == $key ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tabla visible solo en escritorio --}}
                    <div class="table-responsive d-none d-md-block" id="usersTableContainer">
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
                                        <a href="{{ route('users.show', $user->id) }}" class="btn-success btn-sm" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="user-cards-container d-md-none">
                        @foreach($users as $user)
                        <div class="user-card">
                            <!-- Botones alineados verticalmente -->
                            <div class="user-card-buttons">
                                <!-- Ver -->
                                <a href="{{ route('users.show', $user->id) }}" class="btn ver-btn" title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>

                            <!-- Contenido de la card -->
                            <h4>{{ $user->name }} {{ $user->last_name }} {{ $user->second_last_name }}</h4>
                            <p><strong>Escuela:</strong> {{ $user->school->name ?? '-' }}</p>
                            <p><strong>Grado:</strong> {{ $user->grade->name ?? '-' }}</p>
                            <p><strong>Grupo:</strong> {{ $user->group->name ?? '-' }}</p>
                        </div>
                        @endforeach
                    </div>


                    {{-- Paginación --}}
                    <div class="d-flex justify-content-center" id="paginationContainer">
                        {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif


                {{-- CONTENIDO ESTUDIANTE / DOCENTE --}}
                @if(auth()->user()->hasRole('student') || auth()->user()->hasRole('teacher'))

                <div class="student-info text-center mb-3">
                    <p class="h5">¡Hola, {{ auth()->user()->name }}!</p>
                </div>

                <div class="menu text-center mb-4">
                    <span class="tab-link active" data-target="#coursesTab">Cursos</span>
                    <span class="tab-link" data-target="#progressTab">Progreso</span>
                </div>

                {{-- TAB 1: CURSOS --}}
                <div id="coursesTab" class="tab-pane show">
                    <div class="practices d-flex flex-wrap justify-content-center">
                        @foreach($practices as $practice)
                        <div class="practice-card border border-primary m-2 text-center p-3">
                            <img src="{{ asset('images/' . $practice['image']) }}" alt="{{ $practice['title'] }}" class="mb-2" style="max-height: 100px;">
                            <h3 class="h5">{{ $practice['title'] }}</h3>
                            <div class="colored-line my-2"></div>
                            <p class="small">{!! $practice['description'] !!}</p>
                            <button class="btn btn-primary" onclick="openSimulationWindow()">
                                <i class="bi bi-play-fill"></i> Iniciar
                            </button>

                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- TAB 2: PROGRESO --}}
                <div id="progressTab" class="tab-pane" style="display:none;">
                    @role('teacher')
                    <div class="container my-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-10">
                                <div class="card shadow-sm border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h3 class="h5 mb-0 text-center">Progreso de Estudiantes</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive custom-table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="bg-dashboard-header">
                                                    <tr>
                                                        <th class="text-start ps-3">Estudiante</th>
                                                        @foreach($practices as $p)
                                                        <th class="text-center">{{ Str::limit($p['title'], 15) }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>

                                                <div class="pdb-action-buttons" style="display: flex; align-items: center; justify-content: center; gap: 40%; margin-bottom: 20px;">
                                                        <button class="btn-practice" title="Ejecuta el instalador para crear una molécula!">
                                                            <i class="fas fa-atom"></i> Practicar
                                                        </button>
                                                    </div>

                                                <tbody>
                                                    @foreach($users as $student)
                                                    
                                                    <tr>
                                                        <td class="text-start ps-3 fw-medium">
                                                            {{ $student->name }} {{ $student->last_name }} {{ $student->second_last_name }}
                                                        </td>
                                                        @foreach($practices as $p)
                                                        @php
                                                        $done = in_array($p['id'], is_array($student->done_practices) ? $student->done_practices : (array) $student->done_practices);
                                                        @endphp
                                                        <td class="text-center">
                                                            <span class="status-badge {{ $done ? 'bg-success' : 'bg-danger' }}">
                                                                <i class="fas {{ $done ? 'fa-check' : 'fa-times' }}"></i>
                                                                <span class="status-text">{{ $done ? 'Realizada' : 'Pendiente' }}</span>
                                                            </span>
                                                        </td>
                                                        @endforeach
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light text-center">
                                        <small class="text-muted">Actualizado el {{ now()->format('d/m/Y') }} - Total estudiantes: {{ count($users) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        .table thead th {
                            color: #ffffff !important;
                        }
                    </style>
                    @endrole

                    @role('student')
                    <div class="container my-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="card shadow-sm border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h3 class="h5 mb-0 text-center">Mi Progreso Académico</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="bg-dashboard-header">
                                                    <tr>
                                                        <th class="w-40">Práctica</th>
                                                        <th class="w-60">Estado</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($practices as $p)
                                                    @php
                                                    $done = in_array($p['id'], is_array(auth()->user()->done_practices) ? auth()->user()->done_practices : (array) auth()->user()->done_practices);
                                                    @endphp
                                                    <tr>
                                                        <td class="align-middle">
                                                            <strong>{{ $p['title'] }}</strong>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="d-flex align-items-center">
                                                                <div class="progress flex-grow-1" style="height: 24px;">
                                                                    <div class="progress-bar {{ $done ? 'bg-success' : 'bg-warning' }}"
                                                                        role="progressbar"
                                                                        style="width: {{ $done ? '100%' : '30%' }};"
                                                                        aria-valuenow="{{ $done ? '100' : '30' }}"
                                                                        aria-valuemin="0"
                                                                        aria-valuemax="100">
                                                                        {{ $done ? 'Completado' : 'En progreso' }}
                                                                    </div>
                                                                </div>
                                                                <span class="ms-2 {{ $done ? 'text-success' : 'text-warning' }}">
                                                                    <i class="fas {{ $done ? 'fa-check-circle' : 'fa-spinner' }}"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                        .table thead th {
                            color: #ffffff !important;
                        }

                        .border-primary {
                            border-color: #0d6efd !important;
                        }

                        .text-light-primary {
                            color: rgba(13, 110, 253, 0.8);
                        }

                        .table-hover tbody tr:hover {
                            background-color: rgba(13, 110, 253, 0.05);
                        }

                        .progress {
                            border-radius: 12px;
                            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
                        }

                        .progress-bar {
                            font-size: 0.8rem;
                            font-weight: 500;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .colored-line {
                            height: 4px;
                            width: 100%;
                            border-radius: 2px;
                            margin: 0 auto;
                        }

                        @media (max-width: 768px) {

                            .w-40,
                            .w-60 {
                                width: auto !important;
                            }

                            .table-responsive {
                                border: 0;
                            }

                            .table thead {
                                display: none;
                            }

                            .table tr {
                                display: block;
                                margin-bottom: 1rem;
                                border: 1px solid #dee2e6;
                                border-radius: 0.25rem;
                            }

                            .table td {
                                display: block;
                                text-align: right;
                                padding-left: 50%;
                                position: relative;
                                border-bottom: 1px solid #dee2e6;
                            }

                            .table td::before {
                                content: attr(data-label);
                                position: absolute;
                                left: 1rem;
                                width: calc(50% - 1rem);
                                padding-right: 1rem;
                                text-align: left;
                                font-weight: bold;
                                color: #0d6efd;
                            }

                            .table td:last-child {
                                border-bottom: 0;
                            }

                            .table td[data-label] {
                                text-align: right;
                            }
                        }
                    </style>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const tableCells = document.querySelectorAll('.table td');
                            const headers = ['Práctica', 'Estado'];

                            tableCells.forEach((cell, index) => {
                                const headerIndex = index % headers.length;
                                cell.setAttribute('data-label', headers[headerIndex]);
                            });
                        });
                    </script>
                    @endrole
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const colors = ["#b2be5c", "#ffd55c", "#c9c3f4"];
        document.querySelectorAll('.colored-line').forEach(line => {
            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            line.style.backgroundColor = randomColor;
        });

        const tabs = document.querySelectorAll('.tab-link');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.tab-pane').forEach(p => p.style.display = 'none');
                const target = document.querySelector(this.getAttribute('data-target'));
                if (target) target.style.display = 'block';
            });
        });
    });

    function toggleRole(selectedRole) {
        // Activar solo el switch seleccionado
        ['student', 'teacher'].forEach(role => {
            document.getElementById(role + 'Switch').checked = (role === selectedRole);
        });

        fetch(`/dashboard?role=${selectedRole}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html;

                // Actualiza tabla (escritorio)
                const newTable = temp.querySelector('#usersTableContainer');
                if (newTable) {
                    document.getElementById('usersTableContainer').innerHTML = newTable.innerHTML;
                }

                // Actualiza cards (móvil)
                const newCards = temp.querySelector('.user-cards-container');
                if (newCards) {
                    const currentCards = document.querySelector('.user-cards-container');
                    if (currentCards) {
                        currentCards.innerHTML = newCards.innerHTML;
                    }
                }

                // Actualiza paginación
                const newPagination = temp.querySelector('#paginationContainer');
                if (newPagination) {
                    document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                }
            })
            .catch(err => console.error('Error en la carga de usuarios:', err));
    }
</script>


<script>
    function openSimulationWindow() {
        const win = window.open("", "_blank", "width=700,height=500");
        if (!win) {
            alert("Tu navegador ha bloqueado la ventana emergente.");
            return;
        }

        const loadingHTML = `
            <html>
                <head>
                    <title>Cargando simulación...</title>
                    <style>
                        body {
                            background-color: #111;
                            color: #00ffcc;
                            font-family: monospace;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            margin: 0;
                            flex-direction: column;
                        }
                        #loadingText {
                            font-size: 1.2rem;
                        }
                    </style>
                </head>
                <body>
                    <div id="loadingText">Cargando prácticas<span id="dots">.</span></div>
                </body>
            </html>
        `;

        win.document.write(loadingHTML);
        win.document.close();

        let counter = 0;
        const maxRepeats = 5;
        const dots = ['.', '..', '...', '....', '.....'];
        const interval = setInterval(() => {
            if (counter < maxRepeats) {
                win.document.getElementById('dots').textContent = dots[counter % dots.length];
                counter++;
            } else {
                clearInterval(interval);
                showErrorMessage(win);
            }
        }, 600); // Velocidad entre cada "paso" de animación
    }

    function showErrorMessage(win) {
        win.document.body.innerHTML = `
            <h1 style="color:#ff5555;">Error Crítico: Prácticas no Detectadas</h1>
            <p>La plataforma no ha podido encontrar las prácticas instaladas en el sistema.</p>
            <div style="background-color:#2d2d44;padding:15px;border-radius:6px;font-size:14px;white-space:pre-wrap;overflow-y:auto;max-height:300px;color:#f8f8f2;font-family:'Courier New', Courier, monospace;">
ErrorCode: PRT404
Timestamp: ${new Date().toISOString()}
Module: PracticeDetectorService
Path: /usr/local/platform/practices/
Expected: practice-config.json
Result: FileNotFoundException

StackTrace:
 - at scanPractices(PracticeService.js:204)
 - at initPracticeLoad(core.js:88)
 - at renderMainView(app.js:57)

Posibles causas:
 - Las prácticas no están instaladas correctamente.
 - El archivo de configuración está corrupto o mal ubicado.
 - Faltan permisos de lectura en el directorio de prácticas.

Soluciones sugeridas:
 - Verifica la existencia del archivo 'practice-config.json'.
 - Reinstala las prácticas desde el panel de administrador.
 - Asegúrate de que la ruta de instalación es accesible.
            </div>
        `;
    }
</script>




@endsection