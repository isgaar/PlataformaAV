@extends('layouts.app')
@section('title', 'Visor Molecular 3D')
@section('content')
<style>
    /* Para la mayoría de los elementos se usa 'Fira Sans' con peso bold */
    html,
    body,
    p,
    span,
    a,
    li,
    button,
    input,
    label,
    div {
        font-family: 'Fira Sans', sans-serif;
        font-weight: 700;
    }

    /* Para títulos y elementos destacados se utiliza 'Lilita One' */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: 'Lilita One', sans-serif;
        font-weight: 700;
    }
</style>

<div class="hero">
    <!-- Primera sección: Imagen y contenido -->
    <div class="hero-top">
        <img src="{{ asset('images/desk.png') }}" alt="Monitor" class="pulse">
        <div class="hero-content">
            <div class="hero-buttons">
                <button class="hero-button yellow" style="position: relative; left: -20px;">Explora</button>
                <button class="hero-button green" style="position: relative; right: -90px;">Aprende</button>
                <button class="hero-button cream" style="position: relative; left: -10px;">Crea</button>
            </div>
            <hr class="hero-divider">
            <p>¡Únete a la nueva era educativa y lleva la ciencia al siguiente nivel!</p>
        </div>
    </div>

    <!-- Segunda sección: Botón centrado -->
    <div class="hero-bottom">
        <button class="cta-button" onclick="window.location.href='/renderonline'">
            <i class="fas fa-search"></i> <!-- Ícono de lupa -->
            Descubre tu molécula
        </button>
    </div>
</div>

<!-- Nueva sección de tabla periódica -->
<div class="section table">
    
    <div>
        <h2>Explora la Tabla Periódica</h2>
        <p>Descubre todos los elementos químicos, sus propiedades y características de manera interactiva. Una herramienta esencial para estudiantes y amantes de la química.</p>
        
        <div class="button-container">
            <button class="cta-button" onclick="window.location.href='/tablaperiodica'">
                <i class="fas fa-atom"></i>
                Ver Tabla Periódica
            </button>
        </div>
    </div>
    <img src="{{ asset('images/tbpicon.png') }}" alt="Icono tabla periódica">

</div>

<div class="section mission">
    <div>
        <h2>Misión</h2>
        <p>Proporcionar a estudiantes y docentes una herramienta educativa innovadora que facilite la comprensión de conceptos abstractos, fomente el pensamiento crítico y creativo, y promueva un aprendizaje activo y significativo.</p>
    </div>
    <img src="{{ asset('images/quimico.png') }}" alt="Químico">
</div>

<div class="section vision">
    <div>
        <h2>Visión</h2>
        <p>Ser la solución educativa en química molecular para telesecundarias de la región, extendiendo el acceso a laboratorios virtuales y revolucionando la enseñanza con tecnología para impulsar la educación científica y el desarrollo en contextos diversos.</p>
    </div>
    <img src="{{ asset('images/vision.png') }}" alt="Visión">
</div>

<div class="values-container">
    <h2>Valores</h2>
    <hr class="values-divider">
    <div class="values">
        <div class="value-item">
            <div class="value-circle">
                <img src="{{ asset('images/generacion.png') }}" alt="Innovación">
            </div>
            <h3>Innovación Educación</h3>
        </div>
        <div class="value-item">
            <div class="value-circle">
                <img src="{{ asset('images/gafas-de-proteccion.png') }}" alt="Seguridad" class="svg-color">
            </div>
            <h3>Seguridad Sostenibilidad</h3>
        </div>
        <div class="value-item">
            <div class="value-circle">
                <img src="{{ asset('images/grupo.png') }}" alt="Colaboración">
            </div>
            <h3>Colaboración Accesibilidad</h3>
        </div>
    </div>
</div>
@endsection