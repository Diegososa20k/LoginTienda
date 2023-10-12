<?php

use Illuminate\Support\Facades\Route;
use App\Models\Producto;
use App\Http\Controllers\ProductoController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/productos', [ProductoController::class, 'store']);
Route::get('/productos', [ProductoController::class, 'index']);
Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
Route::put('productos/{id}', [ProductoController::class, 'update']);
//Rutas para las pantallas
Route::get('/producto', [ProductoController::class, 'mostrarProducto'])->name('producto');
