<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">



    <title>{{ str_replace('_', ' ', config('app.name')) }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <script src="{{ asset('js/3Dmol.js') }}"></script>

    <style>
        /* Estilo para la animación de la barra de navegación */
        .navbar {
            transition: transform 0.3s ease-in-out;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <div id="app">

        <!-- Barra de navegación -->
        <nav class="navbar" id="navbar">
            <div class="navbar-left">
                <div class="logo">
                    <a href="/">
                        <img src="{{ asset('av.png') }}" alt="Logo" class="logo-img">
                    </a>
                </div>
            </div>
            
            <ul class="navbar-right flex items-center gap-6">
                <!-- Si el usuario está autenticado -->
                @auth
                <li id="dashboard-link">
                    <a href="{{ route('dashboard') }}" class="hover:text-yellow-400">Inicio</a>
                </li>
                <li id="user-name">
                    <a href="#" class="flex items-center gap-2 hover:text-yellow-400">
                        <i class="fas fa-user"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                </li>

                <li id="logout-link">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:text-red-500" title="Cerrar sesión">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                @endauth

                <!-- Si el usuario no está autenticado -->
                @guest
                <li id="login-link">
                    <a href="{{ route('login') }}" class="hover:text-green-400">Iniciar sesión</a>
                </li>
                @endguest
            </ul>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer>
            <!-- Sección superior del footer -->
            <div class="footer-top">
                <div class="footer-left">
                    <img src="{{ asset('av.png') }}" alt="Logo">
                </div>
                <div class="footer-right">
                    <p>Laboratorio en química virtual,
                        realizado sólo con fines educativos.</p>
                </div>
            </div>

            <!-- Sección inferior del footer -->
            <div class="footer-bottom">
                <p>Copyright &copy; 2025 - Desarrollado por estudiantes UTCV</p>
            </div>
        </footer>

        <button class="scroll-to-top" onclick="scrollToTop()">
            <img src="{{ asset('images/up.png') }}" alt="Subir">
        </button>

    </div>

    <!-- Agregar Axios desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    <script>
        // Mostrar/Ocultar la navbar dependiendo del desplazamiento con animación
        let lastScrollTop = 0;
        const navbar = document.getElementById("navbar");

        window.addEventListener("scroll", function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > lastScrollTop) {
                // Desplazamiento hacia abajo -> ocultar navbar con animación
                navbar.style.transform = "translateY(-100%)"; // La barra se mueve hacia arriba fuera de la vista
            } else {
                // Desplazamiento hacia arriba -> mostrar navbar con animación
                navbar.style.transform = "translateY(0)"; // La barra vuelve a su posición original
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Para no permitir valores negativos
        });

        // Botón de scroll hacia arriba
        const scrollToTopButton = document.querySelector(".scroll-to-top");
        scrollToTopButton.style.display = "none";

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