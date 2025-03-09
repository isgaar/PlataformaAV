<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oh no! - Error 404</title>
    <!-- Fuente de Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8fafc;
            color: #2d3748;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .error-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }

        h1 {
            font-size: 48px;
            color: #e53e3e;
            margin: 0;
            font-weight: 700;
        }

        p {
            font-size: 18px;
            color: #4a5568;
            margin: 20px 0;
        }

        .dino {
            width: 150px;
            height: auto;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        .link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #e53e3e;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .link:hover {
            background-color: #c53030;
        }

        /* Animación flotante para el dinosaurio */
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <!-- Imagen del dinosaurio de KDE -->
        <img src="https://kstars.kde.org/reusable-assets/konqi-dev.png" alt="KDE Dinosaur" class="dino">
        <h1>ERROR 404</h1>
        <p>The system has encountered a problem. The requested page does not exist.</p>
        <p>Check the URL or return to the main page.</p>
        <a href="/" class="link">Go to Homepage</a>
    </div>
</body>

</html>