<?php

use App\Http\Controllers\AnuncioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//GET
    Route::get('/usuario/proprietarios', [UserController::class, 'indexProprietario'])->name('proprietarioview');
    Route::get('/proprietario', [UserController::class, 'createProprietario'])->name('newproprietario');
    Route::get('/usuario/editproprietario/{id}', [UserController::class, 'editProprietario'])->name('editproprietario');
    Route::get('/usuario/showproprietario/{id}', [UserController::class, 'showProprietario'])->name('showproprietario');
    Route::get('/usuario/pesquisaproprietario', [UserController::class, 'pesquisaProprietario'])->name('pesquisaproprietario');

    Route::get('/usuario/construtoras', [UserController::class, 'indexConstrutora'])->name('construtorasview');
    Route::get('/construtora', [UserController::class, 'createConstrutora'])->name('newconstrutora');
    Route::get('/usuario/editconstrutora/{id}', [UserController::class, 'editConstrutora'])->name('editconstrutora');
    Route::get('/usuario/showconstrutora/{id}', [UserController::class, 'showConstrutora'])->name('showconstrutora');
    Route::get('/usuario/pesquisaconstrutora', [UserController::class, 'pesquisaConstrutora'])->name('pesquisaconstrutora');

    Route::get('/anuncio/create', [AnuncioController::class, 'create'])->name('newanuncio');
    Route::get('/anuncio/show', [AnuncioController::class, 'show'])->name('anuncioshow');
    Route::get('/anuncio/edit/{id}', [AnuncioController::class, 'edit'])->name('editanuncio');
    Route::get('/anuncio/index', [AnuncioController::class, 'index'])->name('anuncioview');
    Route::get('/anuncio/pesquisa', [AnuncioController::class, 'pesquisa'])->name('anuncioview');

//POST
    Route::post('/usuario/proprietario', [UserController::class, 'storeProprietario'])->name('proprietarioview');

    Route::post('/anuncio/store', [AnuncioController::class, 'store'])->name('anuncioview');

    Route::post('/usuario/construtora', [UserController::class, 'storeConstrutora'])->name('storeconstrutora');


//PUT
    Route::put('/usuario/updateproprietario/{id}', [UserController::class, 'updateProprietario'])->name('proprietarioview');
    
    Route::put('/anuncio/update/{id}', [AnuncioController::class, 'update'])->name('anuncioview');

    Route::put('/usuario/updateConstrutora/{id}', [UserController::class, 'updateConstrutora'])->name('');



//DELETE
    Route::delete('/usuario/deleteproprietario/{id}', [UserController::class, 'destroyProprietario'])->name('proprietarioview');

    Route::delete('/anuncio/destroy/{id}', [AnuncioController::class, 'destroy'])->name('anuncioview');

    Route::delete('/usuario/deleteconstrutora/{id}', [UserController::class, 'destroyConstrutora'])->name('');