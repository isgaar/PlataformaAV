@extends('layouts.app')
@section('title', 'Crear Nueva Práctica')

@section('content')
<style>
    :root {
        --primary: #1B475D;
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

    .form-container {
        max-width: 800px;
        margin: 30px auto;
        width: 100%;
        box-sizing: border-box;
    }
    
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 30px;
        width: 100%;
    }
    
    .form-title {
        color: var(--primary);
        margin-bottom: 30px;
        text-align: center;
        font-size: clamp(22px, 5vw, 28px);
        font-weight: 600;
    }
    
    .form-section {
        margin-bottom: 30px;
    }
    
    .section-title {
        color: var(--primary);
        margin-bottom: 15px;
        font-size: clamp(16px, 4vw, 18px);
        font-weight: 500;
    }
    
    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: border 0.3s;
        box-sizing: border-box;
    }
    
    .form-group input[type="text"]:focus,
    .form-group textarea:focus {
        border-color: var(--primary);
        outline: none;
    }
    
    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    /* Dropzone styles */
    .dropzone {
        border: 2px dashed #bdc3c7;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    
    .dropzone.highlight {
        border-color: var(--primary);
        background-color: #f8fafc;
    }
    
    .dropzone-content {
        transition: opacity 0.3s;
    }
    
    .dropzone:hover {
        border-color: var(--primary);
    }
    
    .icon-large {
        font-size: clamp(36px, 8vw, 48px);
        color: #7f8c8d;
        margin-bottom: 10px;
    }
    
    .dropzone p {
        margin: 8px 0;
        color: #7f8c8d;
        font-size: clamp(14px, 3vw, 16px);
    }
    
    .dropzone span {
        color: #7f8c8d;
        display: block;
        margin: 8px 0;
        font-size: clamp(13px, 3vw, 15px);
    }
    
    .browse-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-size: clamp(14px, 3vw, 16px);
        transition: background 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    
    .browse-btn:hover {
        background: #0d2b3d;
    }
    
    .browse-btn i {
        font-size: clamp(14px, 3vw, 16px);
    }
    
    .file-info {
        font-size: clamp(12px, 3vw, 14px);
        color: #95a5a6;
    }
    
    .preview-container {
        margin-top: 15px;
        text-align: center;
    }
    
    .preview-container img {
        max-width: 100%;
        max-height: 150px;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    
    .preview-container i {
        font-size: clamp(40px, 8vw, 60px);
        color: var(--primary);
        margin-bottom: 8px;
    }
    
    .preview-container p {
        word-break: break-all;
        color: #34495e;
        font-size: clamp(13px, 3vw, 15px);
    }
    
    .progress-bar {
        height: 5px;
        background: #ecf0f1;
        border-radius: 3px;
        margin-top: 12px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        width: 0%;
        background: var(--primary);
        transition: width 0.3s;
    }
    
    .hidden {
        display: none;
    }
    
    /* Botones */
    .form-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: clamp(14px, 3vw, 16px);
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex: 1 1 45%;
        min-width: 120px;
    }
    
    .back-btn {
        background-color: #f1f1f1;
        color: #34495e;
    }
    
    .back-btn:hover {
        background-color: #e1e1e1;
    }
    
    .submit-btn {
        background-color: var(--primary);
        color: white;
    }
    
    .submit-btn:hover:not(:disabled) {
        background-color: #0d2b3d;
    }
    
    .submit-btn:disabled {
        background-color: #bdc3c7;
        cursor: not-allowed;
    }
    
    .spinner {
        width: 18px;
        height: 18px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Mensajes de error */
    .text-danger {
        color: #e74c3c;
        font-size: clamp(12px, 3vw, 14px);
        margin-top: 6px;
        display: block;
    }
    
    /* Alertas */
    .alert-section {
        margin-bottom: 20px;
    }
    
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* Media queries para ajustes específicos */
    @media (max-width: 768px) {
        .form-card {
            padding: 20px;
        }
        
        .dropzone {
            padding: 20px;
        }
    }
    
    @media (max-width: 600px) {
        .form-card {
            padding: 15px;
        }
        
        .dropzone {
            padding: 15px;
        }
        
        .form-group input[type="text"],
        .form-group textarea {
            padding: 10px 12px;
            font-size: 15px;
        }
        
        .btn {
            padding: 8px 15px;
            flex: 1 1 100%;
        }
    }
    
    @media (max-width: 400px) {
        .form-container {
            padding: 10px;
        }
        
        .dropzone p, .dropzone span {
            margin: 5px 0;
        }
        
        .browse-btn {
            padding: 8px 12px;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h3 class="form-title">Crear nueva práctica</h3>
        
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('practices.store') }}" method="POST" enctype="multipart/form-data" id="practiceForm">
            @csrf

            {{-- 1. Imagen de referencia --}}
            <div class="form-section">
                <h4 class="section-title">Imagen de referencia</h4>
                <div class="dropzone" id="imageDropzone">
                    <input type="file" name="source_reference_image" id="source_reference_image" accept="image/*" hidden>
                    <div class="dropzone-content">
                        <i class="fas fa-image icon-large"></i>
                        <p>Arrastra y suelta tu imagen aquí</p>
                        <span>o</span>
                        <button type="button" class="browse-btn" onclick="document.getElementById('source_reference_image').click()">
                            <i class="fas fa-folder-open"></i> Seleccionar archivo
                        </button>
                        <p class="file-info">Formatos soportados: JPG, PNG</p>
                    </div>
                    <div class="preview-container hidden" id="imagePreview"></div>
                    <div class="progress-bar hidden" id="imageProgress">
                        <div class="progress-fill"></div>
                    </div>
                </div>
                @error('source_reference_image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- 2. Nombre --}}
            <div class="form-section">
                <h4 class="section-title">Nombre de la práctica</h4>
                <div class="form-group">
                    <input type="text" name="name" id="name" placeholder="Ej: Práctica de física cuántica" required>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- 3. Descripción --}}
            <div class="form-section">
                <h4 class="section-title">Descripción</h4>
                <div class="form-group">
                    <textarea name="description" id="description" placeholder="Describe el contenido y objetivos de la práctica..."></textarea>
                    @error('description')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- 4. Render (Archivo WebGL) --}}
            <div class="form-section">
                <h4 class="section-title">Archivo WebGL (Render)</h4>
                <div class="dropzone" id="renderDropzone">
                    <input type="file" name="source_practice" id="source_practice" accept=".zip" hidden>
                    <div class="dropzone-content">
                        <i class="fas fa-cube icon-large"></i>
                        <p>Arrastra y suelta tu archivo ZIP aquí</p>
                        <span>o</span>
                        <button type="button" class="browse-btn" onclick="document.getElementById('source_practice').click()">
                            <i class="fas fa-folder-open"></i> Seleccionar archivo
                        </button>
                        <p class="file-info">Solo archivos .zip (máx. 50MB)</p>
                    </div>
                    <div class="preview-container hidden" id="renderPreview"></div>
                    <div class="progress-bar hidden" id="renderProgress">
                        <div class="progress-fill"></div>
                    </div>
                </div>
                @error('source_practice')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="form-buttons">
                <button type="button" onclick="window.location.href='{{ route('dashboard') }}'" class="btn back-btn">Atrás</button>
                <button type="submit" class="btn submit-btn" id="submitButton" disabled>
                    <span class="submit-text">Guardar práctica</span>
                    <div class="spinner hidden" id="submitSpinner"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Elementos del formulario
        const form = document.getElementById("practiceForm");
        const submitButton = document.getElementById("submitButton");
        const submitSpinner = document.getElementById("submitSpinner");
        const submitText = document.querySelector(".submit-text");
        
        // Dropzones
        const imageDropzone = document.getElementById("imageDropzone");
        const imageInput = document.getElementById("source_reference_image");
        const imagePreview = document.getElementById("imagePreview");
        
        const renderDropzone = document.getElementById("renderDropzone");
        const renderInput = document.getElementById("source_practice");
        const renderPreview = document.getElementById("renderPreview");
        
        // Barras de progreso
        const imageProgress = document.getElementById("imageProgress");
        const renderProgress = document.getElementById("renderProgress");

        // Configurar dropzones
        setupDropzone(imageDropzone, imageInput, imagePreview, imageProgress, 'image');
        setupDropzone(renderDropzone, renderInput, renderPreview, renderProgress, 'file');

        // Validación del formulario
        function validateForm() {
            const nameFilled = document.getElementById("name").value.trim() !== '';
            const imageUploaded = imageInput.files && imageInput.files.length > 0;
            const renderUploaded = renderInput.files && renderInput.files.length > 0;
            
            submitButton.disabled = !(nameFilled && imageUploaded && renderUploaded);
        }

        // Event listeners para validación en tiempo real
        document.getElementById("name").addEventListener('input', validateForm);
        imageInput.addEventListener('change', () => handleFileSelect(imageInput, imagePreview, imageProgress, 'image'));
        renderInput.addEventListener('change', () => handleFileSelect(renderInput, renderPreview, renderProgress, 'file'));

        // Animación de envío
        form.addEventListener('submit', (e) => {
            if (submitButton.disabled) {
                e.preventDefault();
                return;
            }
            
            submitText.classList.add('hidden');
            submitSpinner.classList.remove('hidden');
            submitButton.disabled = true;
        });

        // Funciones para manejar dropzones
        function setupDropzone(dropzone, input, preview, progress, type) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropzone.classList.add('highlight');
            }

            function unhighlight() {
                dropzone.classList.remove('highlight');
            }

            dropzone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                input.files = files;
                handleFileSelect(input, preview, progress, type);
            }
        }

        function handleFileSelect(input, preview, progress, type) {
            const file = input.files[0];
            if (!file) return;

            preview.innerHTML = '';
            preview.classList.remove('hidden');
            
            // Simular progreso de carga
            progress.classList.remove('hidden');
            const progressFill = progress.querySelector('.progress-fill');
            let progressValue = 0;
            
            const progressInterval = setInterval(() => {
                progressValue += Math.random() * 10;
                if (progressValue >= 100) {
                    progressValue = 100;
                    clearInterval(progressInterval);
                    setTimeout(() => progress.classList.add('hidden'), 500);
                }
                progressFill.style.width = `${progressValue}%`;
            }, 200);

            if (type === 'image') {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                    
                    const fileName = document.createElement('p');
                    fileName.textContent = file.name;
                    preview.appendChild(fileName);
                };
                reader.readAsDataURL(file);
            } else {
                const icon = document.createElement('i');
                icon.className = 'fas fa-file-archive';
                
                const fileName = document.createElement('p');
                fileName.textContent = file.name;
                
                preview.appendChild(icon);
                preview.appendChild(fileName);
            }
            
            validateForm();
        }
    });
</script>
@endsection