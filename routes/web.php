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
use App\Http\Controllers\ActivityController;

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

// Autenticación
require __DIR__.'/auth.php';
Auth::routes();

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
    $sessionPath = sys_get_temp_dir() . '/session.json';
    file_put_contents($sessionPath, json_encode($user));

    $unityPath = 'C:\\Build\\Atomos Virtuales.exe';
    pclose(popen('start "" "' . $unityPath . '" "' . $sessionPath . '"', 'r'));

    return response()->json(['status' => 'ok']);
})->middleware('auth');

