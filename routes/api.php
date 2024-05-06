<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
// use L5Swagger\Http\Controllers\SwaggerController;

Route::prefix('/v1')->middleware(['auth:sanctum'])->group(function () {
    
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');


    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');

    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::prefix('/v1')->group(function () {

    Route::get('/welcome', function () {
        return "Bienvenue sur votre interface de gestion des stocks.";
    });

    Route::post('/register', [UserController::class, 'register'])->name('users.register');

    Route::post('/login', [UserController::class, 'login'])->name('users.login');

});

