<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::post('/token', [ProductController::class, 'generateToken']);
Route::get('/', [ProductController::class, 'apiDetails']);
Route::put('/products/{code}', [ProductController::class, 'updateProduct']);
Route::delete('/products/{code}', [ProductController::class, 'deleteProduct']);
Route::get('/products/{code}', [ProductController::class, 'getProductByCode']);
Route::get('/products', [ProductController::class, 'getAllProducts']);
