<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterWithTwitterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//User Management
Route::post('/users', [UserController::class, 'store'])->name('user.store');
Route::get('/users', [UserController::class, 'index'])->name('user.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
Route::delete('/users/{user}', [UserController::class, 'delete'])->name('user.delete');
Route::post('/login', [UserController::class, 'login'])->name('login');

//Product Management
Route::post('/products', [ProductController::class, 'createProduct'])->name('products');
Route::get('/products', [ProductController::class, 'getAllProduct'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'getSpecificProduct'])->name('products/{product}');
Route::put('/products/{product}', [ProductController::class, 'updateProduct'])->name('products/{product}');

//Twitter Login/Registration Management
Route::get('/login/twitter', [RegisterWithTwitterController::class, 'loginWithTwitter'])->name('login.twitter');
Route::get('/login/callback/twitter', [RegisterWithTwitterController::class, 'twitterCallBack'])->name('callback.twitter');
