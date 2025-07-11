@extends('layouts.app')
@include('layouts.edit')

@section('content')
<div class="dashboard-container">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-{{ auth()->user()->hasRole('admin') ? '9' : '10' }}">

                {{-- PANEL DE ADMINISTRACIÓN --}}
                @if(auth()->user()->hasRole('admin'))
                <div class="admin-panel">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                        <button class="btn-practice w-100 w-md-50"
                            onclick="window.location.href='{{ route('users.index') }}'">
                            <i class="bi bi-people"></i> Gestionar Usuarios
                        </button>
                        <button class="btn-practice w-100 w-md-50"
                            onclick="window.location.href='{{ route('schools.index') }}'">
                            <i class="bi bi-building me-2"></i> Gestionar Escuelas
                        </button>
                    </div>
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

                    <div class="table-responsive d-none d-md-block" id="usersTableContainer">
                        <table class="table text-center">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Escuela</th>
                                    <th>Grado</th>
                                    <th>Grupo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }} {{ $user->last_name }} {{ $user->second_last_name }}</td>
                                    <td>{{ $user->school->name ?? '-' }}</td>
                                    <td>{{ $user->grade->name ?? '-' }}</td>
                                    <td>{{ $user->group->name ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('users.show', $user->id) }}" class="btn-success btn-sm">
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
                            <div class="user-card-buttons">
                                <a href="{{ route('users.show', $user->id) }}" class="btn ver-btn">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                            <h4>{{ $user->name }} {{ $user->last_name }} {{ $user->second_last_name }}</h4>
                            <p><strong>Escuela:</strong> {{ $user->school->name ?? '-' }}</p>
                            <p><strong>Grado:</strong> {{ $user->grade->name ?? '-' }}</p>
                            <p><strong>Grupo:</strong> {{ $user->group->name ?? '-' }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center" id="paginationContainer">
                        {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif

                {{-- VISTA ESTUDIANTE --}}
                @role('student')

                @php
                $allDone = count($donePractices) === count($practices);
                @endphp

                <div class="container-custom">
                    <div class="card-container">
                        <div class="card-custom shadow-custom border-custom">
                            <div class="card-header-custom">
                                <h3 class="card-title-custom">Mi Progreso</h3>
                                <div class="button-group-custom">
                                    @if($allDone)
                                    <button
                                        class="download-button-custom"
                                        onclick="generarCertificado(this)"
                                        data-nombre="{{ Auth::user()->name }} {{ Auth::user()->last_name }} {{ Auth::user()->second_last_name }}">
                                        <img src="{{ asset('images/download.svg') }}" alt="Descarga">
                                        <span>Obtener Certificado</span>
                                    </button>
                                    @endif

                                    <button id="btnLanzarUnity" class="practice-button-custom" onclick="lanzarUnity()">
                                        <img src="{{ asset('images/ATOMO.png') }}" alt="Átomo">
                                        <span>Prácticas</span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body-custom">
                                <div class="table-responsive-custom">
                                    <table class="table-custom" id="studentProgressTable">
                                        <thead class="table-header-custom">
                                            <tr>
                                                <th>Prácticas</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($practices as $p)
                                            @php
                                            $done = in_array($p->id, $donePractices);
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $p->name }}</strong></td>
                                                <td><strong class="{{ $done ? 'text-success' : 'text-warning' }}">
                                                    {{ $done ? 'Finalizada' : 'Pendiente' }}</strong></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endrole

                {{-- VISTA DOCENTE --}}
                @role('teacher')
                <div class="container my-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card shadow-sm border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="h5 mb-0 text-center">Progreso de Estudiantes</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-dashboard-header">
                                                <tr>
                                                    <th>Estudiante</th>
                                                    @foreach($practices as $p)
                                                    <th>{{ Str::limit($p['name'], 15) }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users as $student)
                                                <tr>
                                                    <td>{{ $student->name }} {{ $student->last_name }} {{ $student->second_last_name }}</td>
                                                    @foreach($practices as $p)
                                                    @php
                                                    $done = in_array(
                                                        $p['id'],
                                                        is_array($student->done_practices)
                                                        ? $student->done_practices
                                                        : (array)$student->done_practices
                                                    );
                                                    @endphp
                                                    <td>{{ $done ? 'Finalizada' : 'Pendiente' }}</td>
                                                    @endforeach
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <small class="text-muted">Actualizado el {{ now()->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endrole

            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
function generarCertificado(btn) {
    const nombre = btn.dataset.nombre || "Estudiante";

    // Crear dinámicamente el HTML
    const html = `
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Certificado de Reconocimiento</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@600&display=swap');
    body {
      margin:0;
      padding:0;
      background-color:#f4f4f4;
      font-family:'Playfair Display', serif;
      display:flex;
      justify-content:center;
      align-items:center;
      height:100vh;
    }
    .certificado {
      width:100%;
      max-width:1200px;
      aspect-ratio:4/3;
      background-color:white;
      border:10px solid #003f5c;
      padding:30px;
      box-sizing:border-box;
      box-shadow:0 0 20px rgba(0,0,0,0.1);
      display:flex;
      justify-content:center;
      align-items:center;
    }
    .marco {
      border:2px solid #003f5c;
      width:100%;
      height:100%;
      padding:40px;
      box-sizing:border-box;
      text-align:center;
      display:flex;
      position:relative;
      flex-direction:column;
      justify-content:flex-start;
      overflow:hidden;
      background-color:white;
    }
    .logos {
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    .logos img {
      width:110px;
      height:auto;
    }
    .titulo {
      font-size:1.3em;
      text-transform:uppercase;
      letter-spacing:1px;
      margin:0;
    }
    h1 {
      font-size:3.5em;
      margin:5px 0;
    }
    .a {
      font-size:1.2em;
      margin:10px 0;
    }
    .nombre {
      font-family:'Great Vibes', cursive;
      font-size:3em;
      margin-bottom:10px;
    }
    .linea-verde {
      border:none;
      height:2px;
      background-color:#a0b86d;
      width:60%;
      margin:0 auto 20px;
    }
    .descripcion {
      font-size:1.2em;
      line-height:1.5;
      color:#333;
      max-width:800px;
      margin:0 auto;
    }
    .firmas {
      display:flex;
      justify-content:space-around;
      align-items:flex-end;
      margin-top:15px;
      margin-bottom:40px;
      background:white;
      position:relative;
      z-index:1;
    }
    .firma {
      text-align:center;
      max-width:220px;
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:8px;
    }
    .firma img {
      width:220px;
      height:auto;
      margin-bottom:0;
      border:none;
      filter:drop-shadow(0 2px 2px rgba(0,0,0,0.15));
    }
    .linea-firma {
      height:3px;
      width:200px;
      background-color:#a0b86d;
      margin:0 auto;
      display:block;
      border-radius:2px;
    }
    .firma-aestra-img {
      width:140px !important;
      height:auto !important;
      filter:drop-shadow(0 2px 2px rgba(0,0,0,0.15));
      margin-bottom:0;
      border:none;
    }
    .firma strong {
      font-weight:700;
      font-size:1.1em;
      letter-spacing:0.03em;
    }
    .firma span {
      font-style:italic;
      font-size:0.9em;
      color:#555;
    }
    .ondas-inferiores {
      position:absolute;
      bottom:-30px;
      left:0;
      right:0;
      width:100%;
      height:200px;
      overflow:hidden;
      z-index:0;
    }
    .onda {
      position:absolute;
      width:100%;
      height:200px;
      bottom:0;
      left:0;
    }
    .onda.amarillo {
      z-index:1;
      opacity:1;
      fill:#fcbf49;
    }
    .onda.azul {
      z-index:2;
      opacity:1;
      fill:#003f5c;
    }
  </style>
</head>
<body>
  <div class="certificado">
    <div class="marco">
      <div class="logos">
        <img src="${window.location.origin}/images/logoAV.png" alt="Logo AV">
        <p class="titulo">ÁTOMOS VIRTUALES<br>OTORGA EL PRESENTE</p>
        <img src="${window.location.origin}/images/logoAestra.png" alt="Logo Aestra">
      </div>
      <div>
        <h1>RECONOCIMIENTO</h1>
        <p class="a">A</p>
        <p class="nombre">${nombre}</p>
        <hr class="linea-verde">
        <p class="descripcion">
          Por su destacada participación en las prácticas realizadas en el laboratorio virtual,<br>
          demostrando habilidades en el manejo y análisis de simulaciones científicas.
        </p>
      </div>
      <div class="firmas">
        <div class="firma">
          <p><img src="${window.location.origin}/images/firmaatomin.png" alt="Firma DR. Atomín" width="220" height="100"></p>
          <div class="linea-firma"></div>
          <p><strong>DR. Atomín</strong><br><span>Profesor Asignado</span></p>
        </div>
        <div class="firma firma-aestra">
          <p><img src="${window.location.origin}/images/image.png" alt="Firma Aestra" class="firma-aestra-img"></p>
          <div class="linea-firma"></div>
          <p><strong>AESTRA</strong><br><span>Empresa de Software</span></p>
        </div>
      </div>
      <div class="ondas-inferiores">
        <svg class="onda amarillo" viewBox="0 0 1200 150" preserveAspectRatio="none">
          <path d="M0,60 C300,130 900,0 1200,100 L1200,150 L0,150 Z"></path>
        </svg>
        <svg class="onda azul" viewBox="0 0 1200 150" preserveAspectRatio="none">
          <path d="M0,80 C300,150 900,20 1200,120 L1200,150 L0,150 Z"></path>
        </svg>
      </div>
    </div>
  </div>
</body>
</html>`;


    // Crear un div temporal visible
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    document.body.appendChild(tempDiv);

    // Log para depurar
    console.log("HTML a exportar:", tempDiv.innerHTML);

    html2pdf().from(tempDiv).set({
        margin: 0,
        filename: `Certificado_${nombre.replace(/\s+/g, '_')}.pdf`,
        image: { type: 'png', quality: 1 },
        html2canvas: { scale: 3, useCORS: true },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
    }).save().then(() => {
        // Eliminar después de generar el PDF
        document.body.removeChild(tempDiv);
    });
}

</script>



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

    async function lanzarUnity() {
        try {
            const response = await fetch('/lanzar-unity');
            if (!response.ok) {
                alert("Error al obtener la sesión: " + response.status);
                return;
            }
            const data = await response.json();
            const jsonString = JSON.stringify(data);
            const jsonBase64 = btoa(jsonString);
            window.location.href = `atomos://launch?data=${encodeURIComponent(jsonBase64)}`;
        } catch (error) {
            alert("Error al lanzar Unity: " + error);
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnUnity = document.getElementById('btnLanzarUnity');
        const platform = navigator.platform.toLowerCase();

        if (!platform.includes('win')) {
            btnUnity.disabled = true;
            btnUnity.title = `Estamos trabajando arduamente para ofrecer una amplia experiencia en tu sistema operativo (${navigator.platform})`;
            btnUnity.style.cursor = 'not-allowed';
        }
    });
    
</script>



@endsection