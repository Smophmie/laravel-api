<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::prefix('/v1')->group(function () {
    Route::get('/welcome', function () {
        return "test";
    });

    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');


    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/register', function () {
        return "test";
    });

    Route::get('/login', function () {
        return "test";
    });
});


// GET /api/v1/users: Récupérer la liste des utilisateurs.
// GET /api/v1/users/{id}: Récupérer un utilisateur spécifique par son identifiant.
// POST /api/v1/users: Créer un nouvel utilisateur.
// PUT /api/v1/users/{id}: Mettre à jour les informations d'un utilisateur existant.
// DELETE /api/v1/users/{id}: Supprimer un utilisateur existant.
// POST /api/v1/login: Endpoint pour l'authentification.
// POST /api/v1/register: Endpoint pour l'inscription d'un nouvel utilisateur.
