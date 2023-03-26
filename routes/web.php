<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

//GET
Route::get('/usuario/proprietarios', [UserController::class, 'indexProprietario'])->name('proprietarioview');
Route::get('/proprietario', [UserController::class, 'createProprietario'])->name('newproprietario');
Route::get('/usuario/editproprietario/{id}', [UserController::class, 'editProprietario'])->name('editproprietario');
Route::get('/usuario/showproprietario/{id}', [UserController::class, 'showProprietario'])->name('showproprietario');

//POST
Route::post('/usuario/proprietario', [UserController::class, 'storeProprietario'])->name('proprietarioview');

//PUT
Route::put('/usuario/updateproprietario/{id}', [UserController::class, 'updateProprietario'])->name('proprietarioview');


//DELETE
Route::delete('/usuario/deleteproprietario/{id}', [UserController::class, 'destroyProprietario'])->name('proprietarioview');