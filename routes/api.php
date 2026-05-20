php<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Dashboard (admin uniquement)
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Produits
    Route::get('/produits',          [ProduitController::class, 'index']);
    Route::post('/produits',         [ProduitController::class, 'store']);
    Route::get('/produits/{id}',     [ProduitController::class, 'show']);
    Route::put('/produits/{id}',     [ProduitController::class, 'update']);
    Route::delete('/produits/{id}',  [ProduitController::class, 'destroy']);
    Route::get('/alertes',           [ProduitController::class, 'alertes']);

    // Commandes
    Route::get('/commandes',                    [CommandeController::class, 'index']);
    Route::post('/commandes',                   [CommandeController::class, 'store']);
    Route::get('/commandes/{id}',               [CommandeController::class, 'show']);
    Route::put('/commandes/{id}/statut',        [CommandeController::class, 'updateStatut']);
});

