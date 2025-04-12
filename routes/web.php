<?php

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

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/rifa', 'RifaController@index')->name('rifa.index');
    Route::post('/rifa/reservar', 'RifaController@reservarNumero')->name('rifa.reservar');
    Route::get('/rifa/meus-numeros', 'RifaController@meusNumeros')->name('rifa.meus_numeros');
    Route::get('/rifa/sorteio', 'RifaController@sorteio')->name('rifa.sorteio');
});
  

    
Route::get('/rifa/numeros-vendidos', 'RifaController@numerosVendidos')->name('rifa.numeros-vendidos');


Auth::routes();

Route::get('/home', 'RifaController@index')->name('home');
