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
// Quitar la línea Auth::routes() para evitar duplicados
require __DIR__.'/auth.php';

// Route para /home (puede estar en auth.php también, pero se puede dejar aquí)
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

Route::get('/lanzar-unity', function () {
    $user = Auth::user();
    $sessionId = uniqid("sesion_");

    $launchedFrom = url()->previous() ?? url()->current();

    // ✅ Generar el token de acceso Passport
    $tokenResult = $user->createToken('Unity');
    $token = $tokenResult->accessToken;

    // ✅ Preparar los datos que Unity va a recibir
    $sessionData = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'session' => $sessionId,
        'launched_from' => $launchedFrom,
        'token' => $token, // Esto es clave para las peticiones Unity
    ];

    // ✅ Guardar el JSON temporal
    $sessionPath = sys_get_temp_dir() . '/session.json';
    file_put_contents($sessionPath, json_encode($sessionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    // ✅ Ruta al ejecutable Unity
    $unityPath = 'C:\\Build\\Atomos Virtuales.exe';

    // ✅ Lanzar el juego con el path al JSON como argumento
    pclose(popen('start "" "' . $unityPath . '" "' . $sessionPath . '"', 'r'));

    // ✅ También responder por si se llama desde navegador
    return response()->json([
        'status' => 'ok',
        'session' => $sessionId,
        'launched_from' => $launchedFrom,
        'token' => $token,
    ]);
})->middleware('auth');

/*
Route::get('/lanzar-unity', function () {
    $user = Auth::user();
    $sessionId = uniqid("sesion_");

    $launchedFrom = url()->previous() ?? url()->current();

    // Token Passport
    $tokenResult = $user->createToken('Unity');
    $token = $tokenResult->accessToken;

    // JSON para Unity
    $sessionData = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'session' => $sessionId,
        'launched_from' => $launchedFrom,
        'token' => $token,
    ];

    // Definir la ruta C:\Build\session.json
    $sessionPath = 'C:\\Build\\session.json';

    // Asegurarse de que la carpeta C:\Build exista
    if (!is_dir('C:\\Build')) {
        mkdir('C:\\Build', 0777, true); // Crear el directorio si no existe
    }

    // Guardar el archivo en C:\Build\session.json
    file_put_contents($sessionPath, json_encode($sessionData, JSON_PRETTY_PRINT));

    // Verificar si el archivo existe
    if (!file_exists($sessionPath)) {
        return response()->json(['status' => 'error', 'message' => 'El archivo de sesión no se pudo crear.'], 500);
    }

    // Actividades marcadas como hechas
    $actividades = $user->activities()->wherePivot('done', 1)->pluck('name')->toArray();
    $actividadesTexto = implode(' ', $actividades); // cambiar \n por espacio para cmd

    // Corregir las comillas y la ruta
    $comando = 'cmd /k "echo === DATOS DE SESIÓN === && type ""' . addslashes($sessionPath) . '"" && echo. && echo === ACTIVIDADES COMPLETADAS === && echo ' . escapeshellarg($actividadesTexto) . '"';

    // Ejecutar el comando
    pclose(popen("start " . $comando, 'r'));

    return response()->json([
        'status' => 'debug',
        'session_path' => $sessionPath,
        'token' => $token,
        'activities' => $actividades,
    ]);
})->middleware('auth');
*/