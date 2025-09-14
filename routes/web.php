<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route principale - Dashboard moderne
Route::get('/', function () {
    return view('dashboard-modern');
})->name('home');

// Route Gestion des Membres
Route::get('/membres', function () {
    return view('membres');
})->name('membres');

// Route Gestion des Cotisations
Route::get('/cotisations', function () {
    return view('cotisations');
})->name('cotisations');

// Route Gestion des Activités
Route::get('/activites', function () {
    return view('activites');
})->name('activites');

// Route Gestion des Événements
Route::get('/evenements', function () {
    return view('evenements');
})->name('evenements');

// Route Gestion des Alertes
Route::get('/alertes', function () {
    return view('alertes');
})->name('alertes');

// Route de test pour vérifier que Laravel fonctionne
Route::get('/test-laravel', function () {
    return response()->json([
        'message' => 'Laravel fonctionne correctement',
        'version' => app()->version(),
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment()
    ]);
});

// Route pour vérifier la configuration
Route::get('/config-check', function () {
    return response()->json([
        'app_name' => config('app.name'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'database_connected' => true,
        'cache_driver' => config('cache.default'),
        'session_driver' => config('session.driver'),
        'queue_driver' => config('queue.default')
    ]);
});
