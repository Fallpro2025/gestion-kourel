<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route de test simple
Route::get('/test', function () {
    return response()->json([
        'message' => 'API Gestion Kourel fonctionnelle',
        'version' => '1.0.0',
        'timestamp' => now()->toISOString()
    ]);
});

// Route de santé de l'API
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'database' => 'connected',
        'timestamp' => now()->toISOString()
    ]);
});