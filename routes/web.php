<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RenderOnlineController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TablaperiodicaController;
use App\Http\Controllers\CursoController;



Route::fallback(function () {
    Log::channel('graylog')->error('404 Not Found', [
        'url' => request()->fullUrl(),
        'method' => request()->method(),
        'ip' => request()->ip(),
    ]);

    return response()->view('errors.404', [], 404);
});

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para Render Online
Route::get('/renderonline', [RenderOnlineController::class, 'index'])->name('renderonline.index');
Route::post('/pdb/upload', [RenderOnlineController::class, 'upload'])->name('pdb.upload');

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/tablaperiodica', [TablaperiodicaController::class, 'index']);


Route::middleware(['auth', 'can:manage users'])->prefix('admin/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index'); // Listar Usuarios
    Route::get('/create', [UserController::class, 'create'])->name('users.create'); // Crear Usuario
    Route::post('/', [UserController::class, 'store'])->name('users.store'); // Almacenar Usuario
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Editar Usuario
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update'); // Actualizar Usuario
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show'); // Ver Usuario
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Eliminar Usuario
});

Route::middleware('auth')->group(function () {
    Route::get('/cursos/realizacion', [CursoController::class, 'realizacion'])->name('cursos.realizacion');
    Route::get('/cursos/resultado', [CursoController::class, 'resultado'])->name('cursos.resultado');
});

