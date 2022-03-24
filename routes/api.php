<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Products routes
Route::prefix('v1')->middleware('auth:api')->group(function (){

    // Protected Products Routes
    Route::post('/products',[ProductController::class, 'store']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::put('/products/{id}', [ProductController::class, 'update']);

    // Public Products Routes
    Route::get('/products', [ProductController::class, 'index'])->withoutMiddleware('auth:api');
    Route::get('/products/{id}', [ProductController::class, 'show'])->withoutMiddleware('auth:api');
    Route::get('/products/search/{name}', [ProductController::class, 'search'])->withoutMiddleware('auth:api');
});

// Authentication Routes
Route::prefix('v1')->middleware(['auth:api'])->group(function (){
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:api');
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth:api');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-data', [AuthController::class, 'userData']);
});
