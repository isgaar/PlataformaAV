@extends('layouts.app')
@section('title', 'Visor Molecular 3D')
@section('content')
<!-- Contenedor principal de la página -->
<div class="content-container">
    <!-- Contenedor del visor 3D -->
    <div class="viewer-container">
        <div class="controls-overlay">
            <select id="renderMode">
                <option value="sphere">CPK</option>
                <option value="cartoon">Vista Cartoon</option>

                <option value="surface">Vista Superficie</option>
                <option value="stick">Vista Bastones</option>
            </select>
            <div>
                <button class="btn-fullscreen" id="fullscreenButton">
                    <i class="fas fa-expand"></i> Pantalla Completa
                </button>
                <button class="btn-reset" id="resetViewButton">
                    <i class="fas fa-sync-alt"></i> Reiniciar Vista
                </button>
            </div>
        </div>
        <div id="viewer" class="viewer"></div>
    </div>

    <!-- Botones de acción (Practicar + Ayuda) -->

    <div class="pdb-container">
        <div class="pdb-action-buttons" style="display: flex; align-items: center; justify-content: center; gap: 40%; margin-bottom: 20px;">
            <button class="btn-info" id="toggleLegend" title="Mostrar instrucciones">
                <i class="fas fa-question"></i>
            </button>
        </div>

        <!-- Información del archivo PDB -->
        <div id="pdbInfo" class="pdb-info-container">
            <h3>Información del Archivo PDB</h3>
            <!-- Formulario para cargar archivo -->
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf <!-- Token CSRF -->
                <label for="file" class="file-upload-label">Buscar</label>
                <input type="file" id="file" name="file" accept=".pdb" required>
                <button type="submit" id="renderButton">
                    <span class="button-text"><i class="fas fa-play"></i> Renderizar</span>
                    <span class="loader" style="display: none;"></span>
                </button>
            </form>
            <!-- Mensaje de error -->
            <div id="error-message" class="error-message" style="display: none;"></div>
            <div id="toast" class="toast" style="display: none;">El archivo es demasiado grande. Intente comprimirlo antes de subirlo.</div>
            <!-- Tabla de información -->
            <table class="pdb-table">
                <tr>
                    <td>Nombre del archivo:</td>
                    <td id="filename"></td>
                </tr>
                <tr>
                    <td>Átomos:</td>
                    <td id="atoms"></td>
                </tr>
                <tr>
                    <td>Cadenas:</td>
                    <td id="chains"></td>
                </tr>
                <tr>
                    <td>Residuos:</td>
                    <td id="residues"></td>
                </tr>
                <tr>
                    <td>Tamaño:</td>
                    <td id="size"></td>
                </tr>
            </table>
        </div>
    </div>


    <!-- Leyenda flotante (inicialmente oculta) -->
    <div id="floatingLegend" class="floating-legend" style="display: none;">
        <h3>Instrucciones de uso</h3>
        <ul>
            <li><i class="fas fa-expand"></i> <b>Pantalla Completa:</b> Hacer clic para ampliar la visualización.</li>
            <li><i class="fas fa-sync-alt"></i> <b>Reiniciar Vista:</b> Hacer clic para volver a la vista inicial.</li>
            <li><i class="fas fa-mouse-pointer"></i> <b>Interacción con átomos:</b> Hacer clic sobre un átomo para ver sus detalles.</li>
            <li><i class="fas fa-cogs"></i> <b>Cambiar Vista:</b> Selecciona el modo de visualización (cartoon, CPK, etc.).</li>
        </ul>
    </div>


</div>

<!-- Enlace a 3Dmol.js -->
<!-- Script para manejo del visor 3D y carga del archivo -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fullscreenButton = document.getElementById("fullscreenButton");
        const resetViewButton = document.getElementById("resetViewButton");
        const form = document.getElementById("uploadForm");
        const fileInput = document.getElementById("file");
        const renderButton = document.getElementById("renderButton");
        const buttonText = renderButton.querySelector(".button-text");
        const loader = renderButton.querySelector(".loader");
        const filenameDisplay = document.getElementById("filename");
        const MAX_FILE_SIZE = 10 * 1024 * 1024; // Tamaño máximo: 10 MB
        const viewerDiv = document.getElementById("viewer");
        const renderMode = document.getElementById("renderMode");
        const errorMessage = document.getElementById("error-message");
        const toast = document.getElementById("toast");

        // Inicializa el visor 3Dmol.js
        const viewer = $3Dmol.createViewer(viewerDiv, {
            backgroundColor: "white",
            zoomTo: false,
            showBackground: true,
        });

        // Habilitar botones al seleccionar archivo
        fileInput.addEventListener("change", function() {
            if (fileInput.files.length > 0) {
                filenameDisplay.innerText = fileInput.files[0].name;
                renderButton.disabled = false;
                fullscreenButton.disabled = false;
                resetViewButton.disabled = false;
            }
        });

        // Mostrar/ocultar la leyenda al hacer click
        document.getElementById('toggleLegend').addEventListener('click', function() {
            var legend = document.getElementById('floatingLegend');
            legend.style.display = legend.style.display === 'none' ? 'block' : 'none';
        });

        // Mostrar la leyenda al pasar el cursor sobre el botón de ayuda
        document.getElementById('toggleLegend').addEventListener('mouseenter', function() {
            document.getElementById('floatingLegend').style.display = 'block';
        });

        // Ocultar la leyenda al salir el cursor del botón
        document.getElementById('toggleLegend').addEventListener('mouseleave', function() {
            document.getElementById('floatingLegend').style.display = 'none';
        });

        // Función para mantener la proporción 16:10
        function adjustViewerAspectRatio() {
            const viewerContainer = document.querySelector('.viewer-container');
            const width = viewerContainer.offsetWidth;
            const height = (width * 10) / 16;
            viewerContainer.style.height = `${height}px`;
        }

        // Llama a la función al cargar la página y al redimensionar la ventana
        window.addEventListener('load', adjustViewerAspectRatio);
        window.addEventListener('resize', adjustViewerAspectRatio);

        // Cambiar entre modos de renderizado
        renderMode.addEventListener("change", (event) => {
            const mode = event.target.value;
            viewer.setStyle({}, {}); // Limpiar estilos actuales

            switch (mode) {
                case "cartoon":
                    viewer.setStyle({}, {
                        cartoon: {
                            color: "spectrum"
                        }
                    });
                    break;
                case "sphere":
                    viewer.setStyle({}, {
                        sphere: {
                            scale: 0.3,
                            colorscheme: "cpk"
                        },
                        stick: {
                            radius: 0.2,
                            colorscheme: "cpk"
                        }
                    });
                    break;
                case "surface":
                    viewer.addSurface($3Dmol.SurfaceType.VDW, {
                        opacity: 0.85,
                        colorscheme: "greenCarbon"
                    });
                    break;
                case "stick":
                    viewer.setStyle({}, {
                        stick: {
                            radius: 0.2,
                            colorscheme: "cpk"
                        }
                    });
                    break;
            }

            viewer.zoomTo();
            viewer.render();
        });


        resetViewButton.addEventListener("click", () => {
            viewer.zoomTo();
            viewer.render();
        });

        // Habilitar el botón si se carga un nuevo archivo
        fileInput.addEventListener("change", function() {
            renderButton.disabled = false;
            buttonText.style.display = "inline";
            loader.style.display = "none";
        });

        // Evento para procesar el archivo y mostrar la animación de carga
        form.addEventListener("submit", async function(event) {
            event.preventDefault();
            const file = fileInput.files[0];

            if (!file) return;

            // Deshabilita el botón y muestra la animación
            renderButton.disabled = true;
            buttonText.style.display = "none";
            loader.style.display = "inline-block";

            const formData = new FormData();
            formData.append("file", file);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log("CSRF Token:", csrfToken); // Verifica el token CSRF

                const response = await fetch("/pdb/upload", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                console.log("Response status:", response.status); // Verifica el código de estado HTTP
                const responseData = await response.text(); // Lee la respuesta como texto
                console.log("Response data:", responseData); // Inspecciona la respuesta

                if (response.ok) {
                    const data = JSON.parse(responseData); // Parsea la respuesta como JSON

                    // Agregar valores del PDB a la tabla
                    document.getElementById("filename").innerText = data.filename || "N/A";
                    document.getElementById("atoms").innerText = data.pdb_data.atoms || "N/A";
                    document.getElementById("chains").innerText = (data.pdb_data.chains || []).join(", ") || "N/A";
                    document.getElementById("residues").innerText = (data.pdb_data.residues || []).join(", ") || "N/A";
                    document.getElementById("size").innerText = `${data.pdb_data.size || 0} bytes`;

                    // Renderiza el archivo cargado en el visor
                    const pdbData = data.pdb_content;
                    viewer.clear();
                    viewer.addModel(pdbData, "pdb");

                    // Aplica el modo de renderizado seleccionado
                    renderMode.dispatchEvent(new Event("change"));

                    // Habilita la interacción con los átomos
                    enableAtomInteraction();

                    // Inicia la rotación automática
                    rotateModel();
                } else {
                    const errorData = JSON.parse(responseData); // Suponiendo que el servidor devuelve un JSON con el error
                    console.error("Error en la carga del archivo:", errorData);
                    errorMessage.innerText = errorData.message || "Error al procesar el archivo.";
                    errorMessage.style.display = "block";
                }
            } catch (error) {
                console.error("Error en la carga del archivo:", error);
                errorMessage.innerText = "Error al conectarse con el servidor.";
                errorMessage.style.display = "block";
            } finally {
                // Ocultar animación de carga y habilitar botón
                buttonText.style.display = "inline";
                loader.style.display = "none";
                renderButton.disabled = false;
            }
        });

        // Pantalla completa
        fullscreenButton.addEventListener("click", () => {
            if (viewerDiv.requestFullscreen) {
                viewerDiv.requestFullscreen();
            } else if (viewerDiv.webkitRequestFullscreen) {
                viewerDiv.webkitRequestFullscreen();
            } else if (viewerDiv.mozRequestFullScreen) {
                viewerDiv.mozRequestFullScreen();
            } else if (viewerDiv.msRequestFullscreen) {
                viewerDiv.msRequestFullscreen();
            }
            document.addEventListener("fullscreenchange", adjustViewerAspectRatio);
        });

        // Reiniciar vista
        resetViewButton.addEventListener("click", () => {
            viewer.zoomTo();
            viewer.render();
        });

        // Función para habilitar la interacción con los átomos
        function enableAtomInteraction() {
            const tooltip = document.createElement("div");
            tooltip.id = "tooltip";
            tooltip.style.position = "absolute";
            tooltip.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
            tooltip.style.color = "white";
            tooltip.style.padding = "10px";
            tooltip.style.borderRadius = "5px";
            tooltip.style.pointerEvents = "none";
            tooltip.style.zIndex = "1000";
            document.body.appendChild(tooltip);

            viewer.setHoverable({}, true, (atom, viewer, event) => {
                if (atom) {
                    tooltip.innerHTML = `
                    <strong>Elemento: ${atom.elem}</strong><br>
                    Residuo: ${atom.resn}<br>
                    Cadena: ${atom.chain}<br>
                    Índice: ${atom.index}<br><br>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td><b>Átomo:</b></td><td>${atom.elem}</td></tr>
                        <tr><td><b>Residuo:</b></td><td>${atom.resn}</td></tr>
                        <tr><td><b>Índice:</b></td><td>${atom.index}</td></tr>
                    </table>
                    <br>
                    <img src="https://www.example.com/images/${atom.elem}.png" alt="${atom.elem}" style="width: 50px; height: 50px; display: block; margin: auto;">
                `;
                    const rect = viewerDiv.getBoundingClientRect();
                    tooltip.style.left = `${event.clientX - rect.left + 10}px`;
                    tooltip.style.top = `${event.clientY - rect.top + 10}px`;
                    tooltip.style.display = "block";
                }
            }, (atom, viewer) => {
                tooltip.style.display = "none";
            });
        }

        // Rotar el modelo automáticamente
        function rotateModel() {
            let angle = 0;

            function rotate() {
                angle += 1;
                viewer.rotate(angle, 1);
                viewer.render();
                requestAnimationFrame(rotate);
            }
            rotate(); // Inicia la rotación
        }
    });
</script>
@endsection