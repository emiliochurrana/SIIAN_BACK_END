<?php

use App\Http\Controllers\ChatController;
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
    return view('login');
});

//Rotas login
Route::get('/usuario/login', [AuthController::class, 'createLogin'])->name('createlogin');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/usuario/logout', [AuthController::class, 'logout'])->name('logout');

//Rotas Construtoras
Route::get('/usuario/construtora/create', [UserController::class, 'createConstrutora'])->name('createconstrutora');
Route::post('/usuario/construtora/store', [UserController::class, 'storeConstrutora'])->name('storeconstrutora');
Route::get('/usuario/construtora/index', [UserController::class, 'indexConstrutora'])->name('indexconstrutora');
Route::get('/usuario/construtora/edit/{id}', [UserController::class, 'editConstrutora'])->name('editconstrutora');
Route::put('/usuario/construtora/update/{id}', [UserController::class, 'updateConstrutora'])->name('updateconstrutora');
Route::get('/usuario/construtora/show/{id}', [UserController::class, 'showConstrutora'])->name('showproprietario');
Route::delete('/usuario/construtora/delete/{id}', [UserController::class, 'destroyConstrutora'])->name('deleteconstrutora');

//Rotas Correctoras
Route::get('/usuario/correctora/create', [UserController::class, 'createCorrectora'])->name('createcorrectora');
Route::post('/usuario/correctora/store', [UserController::class, 'storeCorrectora'])->name('storecorrectora');
Route::get('/usuario/correctora/index', [UserController::class, 'indexCorrectrora'])->name('indexcorrectora');
Route::get('/usuario/correctora/edit/{id}', [UserController::class, 'editCorrectora'])->name('editcorrectora');
Route::put('/usuario/correctora/update/{id}', [UserController::class, 'updateCorrectora'])->name('updatecorrectora');
Route::get('/usuario/correctora/show/{id}', [UserController::class, 'showCorrectora'])->name('showcorrectora');
Route::delete('/usuario/correctora/delete/{id}', [UserController::class, 'destroyCorrectora'])->name('deletecorrectora');

//Rotas Agentes 
Route::get('/usuario/agencia/create', [UserController::class, 'createAgente'])->name('createagente');
Route::post('/usuario/agencia/store', [UserController::class, 'storeAgente'])->name('storeagente');
Route::get('/usuario/agencia/index', [UserController::class, 'indexAgente'])->name('indexagente');
Route::get('/usuario/agencia/edit/{id}', [UserController::class, 'editAgencia'])->name('editagencia');
Route::put('/usuario/agencia/update/{id}', [UserController::class, 'updateAgencia'])->name('updateagencia');
Route::get('/usuario/agencia/show/{id}', [UserController::class, 'showAgencia'])->name('showagencia');
Route::delete('/usuario/agencia/delete/{id}', [UserController::class, 'destroyAgencia'])->name('deleteagencia');

//Rotas Funcionario
Route::get('/usuario/funcionario/create', [UserController::class, 'createFuncionario'])->name('createfuncionario');
Route::post('/usuario/funcionario/store', [UserController::class, 'storeFuncionario'])->name('storefuncionario');
Route::get('/usuario/funcionario/index', [UserController::class, 'indexFuncionario'])->name('indexfuncionario');
Route::get('/usuario/funcionario/edit/{id}', [UserController::class, 'editFuncionario'])->name('editfuncionario');
Route::get('/usuario/funcionario/perfil/{id}', [UserController::class, 'perfilFuncionario'])->name('perfilfuncionario');
Route::get('/usuario/funcionario/edit/perfil/{id}', [UserController::class, 'editPerfilFuncionario'])->name('editperfilfuncionario');
Route::put('/usuario/funcionario/update/{id}', [UserController::class, 'updateFuncionario'])->name('updatefuncionario');
Route::put('/usuario/funcionario/update/perfil/{id}', [UserController::class, 'updatePerfilFuncionario'])->name('updateperfilfuncionario');
Route::get('/usuario/funcionario/show/{id}', [UserController::class, 'showFuncionario'])->name('showfuncionario');
Route::delete('/usuario/funcionario/delete/{id}', [UserController::class, 'destroyFuncionario'])->name('deletefuncionario');

//Rotas Anuncio
Route::get('/anuncio/create', [AnuncioController::class, 'create'])->name('newanuncio');
Route::post('/anuncio/store', [AnuncioController::class, 'store'])->name('anuncioview');
Route::get('/anuncio/show', [AnuncioController::class, 'show'])->name('anuncioshow');
Route::get('/anuncio/edit/{id}', [AnuncioController::class, 'edit'])->name('editanuncio');
Route::put('/anuncio/update/{id}', [AnuncioController::class, 'update'])->name('anuncioview');
Route::get('/anuncio/index', [AnuncioController::class, 'index'])->name('anuncioview');
Route::get('/anuncio/pesquisa', [AnuncioController::class, 'pesquisa'])->name('anuncioview');
Route::post('/anuncio/like/{id}', [AnuncioController::class, 'likeAnuncio'])->name('likeanuncio');
Route::delete('/anuncio/deslike/{id}', [AnuncioController::class, 'deslikeAnuncio'])->name('deslikeanuncio');
Route::delete('/anuncio/delete/{id}', [AnuncioController::class, 'destroy'])->name('anuncioview');

//Rotas Publicidade 
Route::get('/publicidade/create', [PublicidadeController::class, 'create'])->name('createpublicidade');
Route::post('/publicidade/store', [PublicidadeController::class, 'store'])->name('sotrepublicidade');
Route::get('/publicidade/index', [PublicidadeController::class, 'index'])->name('indexpublicidade');
Route::get('/publicidade/edit/{id}', [PublicidadeController::class, 'edit'])->name('editpublicidade');
Route::put('/publicidade/update/{id}', [PublicidadeController::class, 'update'])->name('updatepublicidade');
Route::post('/publicidade/like/{id}', [PublicidadeController::class, 'likePublicidade'])->name('likepublicidade');
Route::delete('/publicidade/deslike/{id}', [PublicidadeController::class, 'deslikePublicidade'])->name('deslikepublicidade');
Route::delete('/publicidade/delete/{id}', [PublicidadeController::class, 'destroy'])->name('deletepublicidade');

//Rotas Chat
Route::get('/chat/login', [ChatController::class, 'login'])->name('login');
route::get('/chat/signup', [ChatController::class, 'signup'])->name('signup');

