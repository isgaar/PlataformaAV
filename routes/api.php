<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'role:admin'])->get('/admin-dashboard', function () {
    return response()->json(['message' => 'Bienvenido Admin']);
});

Route::middleware(['auth:api', 'role:teacher'])->get('/teacher-dashboard', function () {
    return response()->json(['message' => 'Bienvenido Teacher']);
});

Route::middleware(['auth:api', 'role:student'])->get('/student-dashboard', function () {
    return response()->json(['message' => 'Bienvenido Student']);
});

