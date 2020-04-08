<?php

use Illuminate\Http\Request;

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

Route::middleware('cors')->group(function () {
	Auth::routes();

    Route::post('login', 'UserController@authenticate'); //Faz login no sistema

    Route::group(['middleware' => ['jwt.verify']], function() {

        Route::get('/usuario-autenticado', 'UserController@getAuthenticatedUser'); //Pega as informações do usuário autenticado
        Route::get('/get-users', 'UserController@getUsers'); //Obtem a lista de todos os usuários do sistema
        Route::get('/get-leilao', 'LeilaoController@getLeilao'); //Obtem a lista de todos os leilões ativos do sistema

        Route::put('/editar_leilao', 'LeilaoController@editaLeilao'); //Altera os dados de um leilão
        Route::put('/edita-usuario', 'UserController@editaUsuario'); //Altera os dados de um usuário
        Route::put('/excluir_leilao', 'LeilaoController@excluiLeilao'); //Marca um leilão como desativado

        Route::post('/criar-leilao', 'LeilaoController@criarLeilao'); //Cria um novo leilão
        Route::post('/dar_lance', 'LeilaoController@darLance'); //Registra um lance
  
    });

});
