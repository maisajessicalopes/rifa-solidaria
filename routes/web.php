<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RifaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/rifa', [RifaController::class, 'index'])->name('rifa.index');
    Route::post('/rifa/reservar', [RifaController::class, 'reservarNumero'])->name('rifa.reservar');
    Route::get('/rifa/meus-numeros', [RifaController::class, 'meusNumeros'])->name('rifa.meus_numeros');
    Route::get('/rifa/sorteio', [RifaController::class, 'sorteio'])->name('rifa.sorteio');    
    Route::get('/rifa/sorteio-view', function () {
        return view('rifa.sorteio');
    })->name('rifa.sorteio-view');
});

Route::get('/rifa/numeros-vendidos', [RifaController::class, 'numerosVendidos'])->name('rifa.numeros-vendidos');

Auth::routes();

Route::get('/home', [RifaController::class, 'index'])->name('home');
Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
