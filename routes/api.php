<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SorteoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Endpoins
Route::get('/sorteos', [SorteoController::class, 'index'])->middleware('auth:sanctum');
Route::get('/usuarios', [UsuarioController::class, 'index'])->middleware('auth:sanctum');
Route::post('/sorteos', [SorteoController::class, 'store'])->middleware('auth:sanctum');
Route::post('/sorteos/{id}/apuntarse', [SorteoController::class, 'apuntarse'])->middleware('auth:sanctum');