<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RenderOnlineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\TablaperiodicaController;
use App\Http\Controllers\CursoController;
use Laravel\Passport\HasApiTokens;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta de fallback para errores 404
Route::fallback(function () {
    Log::channel('graylog')->error('404 Not Found', [
        'url' => request()->fullUrl(),
        'method' => request()->method(),
        'ip' => request()->ip(),
    ]);

    return response()->view('errors.404', [], 404);
});

// Página de inicio
Route::get('/', function () {
    return view('landing');
});

// Dashboard principal (autenticado y verificado)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Perfil del usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Renderizado online y carga de archivos PDB
Route::get('/renderonline', [RenderOnlineController::class, 'index'])->name('renderonline.index');
Route::post('/pdb/upload', [RenderOnlineController::class, 'upload'])->name('pdb.upload');

// Autenticación (solo esta línea para cargar rutas de auth)
require __DIR__.'/auth.php';

// Página de inicio autenticado
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Tabla periódica
Route::get('/tablaperiodica', [TablaperiodicaController::class, 'index'])->name('tablaperiodica.index');

// Administración de usuarios
Route::middleware(['auth', 'can:manage users'])->prefix('admin/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Administración de escuelas
Route::middleware(['auth', 'can:manage users'])->prefix('admin/schools')->group(function () {
    Route::get('/', [SchoolController::class, 'index'])->name('schools.index');
    Route::get('/create', [SchoolController::class, 'create'])->name('schools.create');
    Route::post('/', [SchoolController::class, 'store'])->name('schools.store');
    Route::get('/{school}', [SchoolController::class, 'show'])->name('schools.show');
    Route::get('/{school}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
    Route::put('/{school}', [SchoolController::class, 'update'])->name('schools.update');
    Route::delete('/{school}', [SchoolController::class, 'destroy'])->name('schools.destroy');
});

// Cursos: realización y resultados
Route::middleware('auth')->group(function () {
    Route::get('/cursos/realizacion', [CursoController::class, 'realizacion'])->name('cursos.realizacion');
    Route::get('/cursos/resultado', [CursoController::class, 'resultado'])->name('cursos.resultado');
});

// Ruta que devuelve la sesión actual en formato JSON para Unity
Route::get('/session-json', function () {
    return response()->json(Auth::user());
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| VERSION 1: Lanza Unity ejecutable local desde PHP
|--------------------------------------------------------------------------
| Comentada: esta versión guarda el JSON local y lanza el EXE desde PHP.
| No recomendable si usas servidor remoto.
|
| Route::get('/lanzar-unity', function () {
|     $user = Auth::user();
|     $sessionId = uniqid("sesion_");
|
|     $launchedFrom = url()->previous() ?? url()->current();
|
|     $tokenResult = $user->createToken('Unity');
|     $token = $tokenResult->accessToken;
|
|     $sessionData = [
|         'id' => $user->id,
|         'name' => $user->name,
|         'email' => $user->email,
|         'session' => $sessionId,
|         'launched_from' => $launchedFrom,
|         'token' => $token,
|     ];
|
|     $sessionPath = sys_get_temp_dir() . '/session.json';
|     file_put_contents($sessionPath, json_encode($sessionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
|
|     $unityPath = 'C:\\Build\\Atomos Virtuales.exe';
|     pclose(popen('start "" "' . $unityPath . '" "' . $sessionPath . '"', 'r'));
|
|     return response()->json([
|         'status' => 'ok',
|         'session' => $sessionId,
|         'launched_from' => $launchedFrom,
|         'token' => $token,
|     ]);
| })->middleware('auth');
*/

/*
|--------------------------------------------------------------------------
| VERSION 2: Lanza Unity y muestra datos en consola
|--------------------------------------------------------------------------
| Comentada: esta versión creaba el JSON y mostraba info en consola.
|
| Route::get('/lanzar-unity', function () {
|     $user = Auth::user();
|     $sessionId = uniqid("sesion_");
|
|     $launchedFrom = url()->previous() ?? url()->current();
|
|     $tokenResult = $user->createToken('Unity');
|     $token = $tokenResult->accessToken;
|
|     $sessionData = [
|         'id' => $user->id,
|         'name' => $user->name,
|         'email' => $user->email,
|         'session' => $sessionId,
|         'launched_from' => $launchedFrom,
|         'token' => $token,
|     ];
|
|     $sessionPath = 'C:\\Build\\session.json';
|
|     if (!is_dir('C:\\Build')) {
|         mkdir('C:\\Build', 0777, true);
|     }
|
|     file_put_contents($sessionPath, json_encode($sessionData, JSON_PRETTY_PRINT));
|
|     if (!file_exists($sessionPath)) {
|         return response()->json(['status' => 'error', 'message' => 'El archivo de sesión no se pudo crear.'], 500);
|     }
|
|     $actividades = $user->activities()->wherePivot('done', 1)->pluck('name')->toArray();
|     $actividadesTexto = implode(' ', $actividades);
|
|     $comando = 'cmd /k "echo === DATOS DE SESIÓN === && type ""' . addslashes($sessionPath) . '"" && echo. && echo === ACTIVIDADES COMPLETADAS === && echo ' . escapeshellarg($actividadesTexto) . '"';
|
|     pclose(popen("start " . $comando, 'r'));
|
|     return response()->json([
|         'status' => 'debug',
|         'session_path' => $sessionPath,
|         'token' => $token,
|         'activities' => $actividades,
|     ]);
| })->middleware('auth');
*/

/*
|--------------------------------------------------------------------------
| VERSION 3: Responder JSON con api_base_url
|--------------------------------------------------------------------------
| ACTIVA: esta es la versión recomendada.
|--------------------------------------------------------------------------
//
Route::get('/lanzar-unity', function () {
    $user = Auth::user();
    $sessionId = uniqid("sesion_");

    $launchedFrom = url()->previous() ?? url()->current();

    $tokenResult = $user->createToken('Unity');
    $token = $tokenResult->accessToken;

    $scheme = request()->getScheme();
    $host = request()->getHost();
    $port = request()->getPort();
    $apiBaseUrl = $scheme . '://' . $host . ($port == 80 || $port == 443 ? '' : ':' . $port);

    $sessionData = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'session' => $sessionId,
        'api_base_url' => $apiBaseUrl,
        'launched_from' => $launchedFrom,
        'token' => $token,
    ];

    // OJO: devolvemos JSON directamente
    return response()->json($sessionData);
})->middleware('auth');

*/

// Ruta AJAX para obtener progreso sin recargar
Route::get('/dashboard/practices-status', [DashboardController::class, 'practicesStatus'])
    ->middleware(['auth'])
    ->name('dashboard.practices-status');


Route::get('/lanzar-unity', function () {
    // Verificar si el usuario está autenticado (opcional, solo por claridad)
    if (!Auth::check()) {
        return response()->json(['error' => 'No autenticado'], 401);
    }

    $user = Auth::user();

    // Generar un identificador único para la sesión
    $sessionId = uniqid("sesion_");

    // La URL desde donde se lanzó Unity
    $launchedFrom = url()->previous() ?? url()->current();

    // Generar token con Passport
    $tokenResult = $user->createToken('Unity');
    $token = $tokenResult->accessToken;

    // Construir la URL base dinámica del servidor
    $scheme = request()->getScheme(); // http o https
    $host = request()->getHost();     // IP o dominio
    $port = request()->getPort();     // puerto (8000 en php artisan serve)
    $apiBaseUrl = $scheme . '://' . $host . ($port == 80 || $port == 443 ? '' : ':' . $port);

    // Preparar los datos de sesión que se enviarán al launcher
    $sessionData = [
        'status' => 'ok',
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'session' => $sessionId,
        'api_base_url' => $apiBaseUrl,
        'launched_from' => $launchedFrom,
        'token' => $token,
    ];

    return response()->json($sessionData);
})->middleware('auth');
