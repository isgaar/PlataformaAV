@extends('layouts.app')
@section('title', 'Tabla Periodica')
@section('content')
<style>
    :root {
        --primary: #1B475D; /* Puedes cambiar estos valores en tu tema */
        --secondary: #6c757d;
    }

    html, body, p, span, a, li, button, input, label, div {
        font-family: 'Fira Sans', sans-serif;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Fira Sans', sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .image-container {
        position: relative;
        display: inline-block;
        margin: 20px auto;
        text-align: center;
        width: 100%;
    }

    .tabla-img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
        border: 1px solid #ccc;
        border-radius: 12px;
        cursor: zoom-in;
        transition: transform 0.3s;
    }

    .tabla-img:hover {
        transform: scale(1.02);
    }

    .zoom-icon {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background-color: var(--primary);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        overflow: auto;
    }

    .modal-content {
        display: block;
        margin: auto;
        max-width: 90%;
        max-height: 90%;
        margin-top: 50px;
    }

    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    .dropdown {
        margin: 20px 0;
        background-color: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
    }

    .dropdown-button {
        background-color: var(--primary);
        color: white;
        padding: 15px 20px;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        font-size: 18px;
        transition: background-color 0.3s;
    }

    .dropdown-button:hover {
    }

    .dropdown-content {
        padding: 20px;
        display: none;
        background-color: white;
        line-height: 1.6;
    }

    .dropdown-content ul {
        padding-left: 20px;
    }

    .dropdown-content li {
        margin-bottom: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 30px 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 12px 15px;
        text-align: left;
    }

    table th {
        background-color: var(--primary);
        color: white;
    }

    table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table tr:hover {
        background-color: #e9e9e9;
    }

    .source {
        text-align: center;
        font-style: italic;
        color: var(--secondary);
        margin-top: -15px;
        margin-bottom: 20px;
    }

    .section-title {
        margin-top: 40px;
        margin-bottom: 20px;
        color: var(--primary);
    }
</style>


<div class="container">
    <h1 class="text-3xl mb-4">Tabla Periódica</h1>

    <div class="image-container">
        <img src="{{ asset('images/tabp.jpg') }}" alt="Tabla periódica" class="tabla-img" id="zoomable-image">
        <div class="zoom-icon" onclick="openModal()">🔍</div>
        <p class="source">Fuente: (UDTcl, 2019)</p>
    </div>

    <!-- Modal para la imagen ampliada -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="fullImage">
    </div>

    @php
    $dropdowns = [
        '¿Qué es la tabla periódica?' => [
            'content' => 'Es una herramienta que organiza todos los elementos químicos conocidos, desde el más ligero (el hidrógeno) hasta los más pesados (elementos artificiales como el oganesón). Cada elemento es una sustancia pura que no puede descomponerse en otras más simples.',
            'type' => 'paragraph'
        ],
        '¿Cómo está organizada?' => [
            'content' => [
                'Por filas (llamadas periodos)' => [
                    'Hay 7 periodos (filas horizontales).',
                    'Indican el número de niveles de energía (capas electrónicas) que tiene un átomo.',
                    'Por ejemplo, el sodio (Na) está en el periodo 3, eso significa que tiene 3 niveles de energía.'
                ],
                'Por columnas (llamadas grupos o familias)' => [
                    'Hay 18 grupos (columnas verticales).',
                    'Los elementos del mismo grupo tienen propiedades químicas parecidas.',
                    'Ejemplo: El grupo 1 (metales alcalinos) incluye al litio (Li), sodio (Na) y potasio (K). Todos son muy reactivos con el agua.'
                ],
                'Por tipo de elementos' => [
                    'Metales: Buenos conductores, brillantes, maleables. (Ej. Hierro, cobre, oro)',
                    'No metales: Malos conductores, muchos son gases. (Ej. Oxígeno, nitrógeno)',
                    'Metaloides: Propiedades intermedias. (Ej. Silicio)',
                    'Gases nobles: No reaccionan fácilmente. (Ej. Helio, neón)',
                    'Lantánidos y actínidos: Aparecen abajo de la tabla, son elementos pesados.'
                ]
            ],
            'type' => 'nested'
        ],
        'Datos útiles para memorizar la tabla' => [
            'content' => [
                'Hidrógeno (H) está arriba a la izquierda, es el primero y el más ligero.',
                'El grupo 18 son los gases nobles: no reaccionan casi con nada.',
                'Los elementos en el centro (bloque d) son los metales de transición (hierro, cobre, zinc).',
                'La línea en zigzag separa metales (izquierda) de no metales (derecha).',
                'Apréndete los elementos más usados (ver elementos más usados) y ubica sus posiciones en la tabla.'
            ],
            'type' => 'list'
        ]
    ];
    @endphp

    @foreach ($dropdowns as $titulo => $data)
    <div class="dropdown">
        <button class="dropdown-button" onclick="toggleDropdown(this)">{{ $titulo }}</button>
        <div class="dropdown-content">
            @if($data['type'] == 'paragraph')
                <p>{{ $data['content'] }}</p>
            @elseif($data['type'] == 'list')
                <ul>
                    @foreach($data['content'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @elseif($data['type'] == 'nested')
                @foreach($data['content'] as $subtitulo => $items)
                    <h3 class="font-bold mt-3">{{ $subtitulo }}</h3>
                    <ul>
                        @foreach($items as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @endforeach
            @endif
        </div>
    </div>
    @endforeach

    <h2 class="section-title">Elementos más usados e importantes</h2>

    <table>
        <thead>
            <tr>
                <th>Elemento</th>
                <th>Símbolo</th>
                <th>Usos comunes</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Hidrógeno</td><td>H</td><td>Combustible limpio (hidrógeno verde), forma parte del agua, presente en el universo.</td></tr>
            <tr><td>Oxígeno</td><td>O</td><td>Vital para la respiración, se usa en hospitales, soldaduras y en la formación del agua.</td></tr>
            <tr><td>Carbono</td><td>C</td><td>Base de la vida (en moléculas orgánicas), en combustibles, lápices, plásticos y acero.</td></tr>
            <tr><td>Nitrógeno</td><td>N</td><td>78% del aire que respiramos, se usa en fertilizantes, alimentos y en medicina.</td></tr>
            <tr><td>Hierro</td><td>Fe</td><td>Construcción, herramientas, estructuras, en la sangre (hemoglobina).</td></tr>
            <tr><td>Calcio</td><td>Ca</td><td>Formación de huesos y dientes, también en cemento y materiales de construcción.</td></tr>
            <tr><td>Sodio</td><td>Na</td><td>Sal común (cloruro de sodio), importante en nervios y músculos.</td></tr>
            <tr><td>Potasio</td><td>K</td><td>Presión y ritmo cardíaco en el cuerpo humano.</td></tr>
            <tr><td>Cobre</td><td>Cu</td><td>Excelente conductor eléctrico, usado en cables, monedas y electrónica.</td></tr>
            <tr><td>Zinc</td><td>Zn</td><td>Prevención de oxidación, medicina, cuidado de la piel, sistema inmune.</td></tr>
            <tr><td>Silicio</td><td>Si</td><td>Chips, computadoras, vidrio, paneles solares.</td></tr>
        </tbody>
    </table>
</div>

<script>
    function toggleDropdown(button) {
        const content = button.nextElementSibling;
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
    }

    // Funcionalidad para el zoom de la imagen
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("fullImage");
    const img = document.getElementById("zoomable-image");

    function openModal() {
        modal.style.display = "block";
        modalImg.src = img.src;
    }

    function closeModal() {
        modal.style.display = "none";
    }

    // Cerrar al hacer clic fuera de la imagen
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endsection