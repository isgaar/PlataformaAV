<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ str_replace('_', ' ', config('app.name')) }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="{{ asset('js/3Dmol.js') }}"></script>

</head>

<body>
    <div id="app">
        <!-- NAVBAR -->
        <nav class="navbar" id="navbar">
            <div class="navbar-left">
                <a href="/">
                    <img src="{{ asset('av.png') }}" alt="Logo" class="logo-img">
                </a>
            </div>
            <div class="navbar-toggle" onclick="toggleMenu(this)">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
            <ul class="navbar-right">
                @auth
                <li><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li><a href="#"><i class="fas fa-user"></i> {{ Auth::user()->name }}</a></li>
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt logout-icon"></i>
                        <span class="logout-text">Cerrar sesión</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>

                @endauth
                @guest
                <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
                @endguest
            </ul>
        </nav>

        <!-- CONTENIDO -->
        <main class="py-4">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer>
            <div class="footer-top">
                <div class="footer-left">
                    <img src="{{ asset('av.png') }}" alt="Logo">
                </div>
                <div class="footer-right">
                    <p>Laboratorio en química virtual, realizado sólo con fines educativos.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 - Desarrollado por estudiantes UTCV</p>
            </div>
        </footer>

        <!-- BOTÓN DE SCROLL -->
        <button class="scroll-to-top" onclick="scrollToTop()">
            <img src="{{ asset('images/up.png') }}" alt="Subir">
        </button>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        const navbar = document.getElementById("navbar");
        let lastScrollTop = 0;

        window.addEventListener("scroll", function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > lastScrollTop) {
                navbar.style.transform = "translateY(-100%)";
            } else {
                navbar.style.transform = "translateY(0)";
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });

        function toggleMenu(btn) {
            const menu = document.querySelector('.navbar-right');
            btn.classList.toggle('active');
            menu.classList.toggle('active');
        }

        document.querySelectorAll('.navbar-right a').forEach(link => {
            link.addEventListener('click', () => {
                document.querySelector('.navbar-right').classList.remove('active');
                document.querySelector('.navbar-toggle').classList.remove('active');
            });
        });

        // Scroll to top
        const scrollToTopButton = document.querySelector(".scroll-to-top");
        window.addEventListener("scroll", function() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 50) {
                scrollToTopButton.style.display = "flex";
            } else {
                scrollToTopButton.style.display = "none";
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>

</html>