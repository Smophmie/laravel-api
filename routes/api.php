<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::prefix('/v1')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/welcome', function () {
        return "test";
    });


    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

Route::prefix('/v1')->group(function () {

    Route::post('/register', [UserController::class, 'register'])->name('users.register');

    Route::post('/login', [UserController::class, 'login'])->name('users.login');

});