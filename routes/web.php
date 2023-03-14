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


//POST
Route::post('/usuario/novo', [UserController::class], 'storeProprietario')->name('users.storeProprietario');

//GET
Route::get('/usuario/proprietario', [UserController::class], 'indexProprietario')->name('users.listProprietario');


//PUT
Route::put('/usuario/edit/{user}', [UserController::class], 'editProprietario')->name('users.editProprietario');


//DELETE
Route::delete('/usuario/proprietario/{user}', [UserController::class], 'destroyProprietario')->name('users.destroyProprietario');