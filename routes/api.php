<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', 'App\Http\Controllers\Auth\AuthController@register');
Route::post('/login', 'App\Http\Controllers\Auth\AuthController@login');

// Rotas de recuperação de senha
Route::post('/password/forgot', 'App\Http\Controllers\Auth\AuthController@forgotPassword');
Route::post('/password/reset', 'App\Http\Controllers\Auth\AuthController@resetPassword');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');

    // Outras rotas protegidas aqui
    Route::prefix('categorias')->group(function () {
        Route::get('/', 'App\Http\Controllers\Api\CategoriaController@index');
        Route::post('/', 'App\Http\Controllers\Api\CategoriaController@create');
        Route::put('/{id}', 'App\Http\Controllers\Api\CategoriaController@update');
        Route::delete('/{id}', 'App\Http\Controllers\Api\CategoriaController@delete');
        Route::delete('purge/{id}', 'App\Http\Controllers\Api\CategoriaController@purge');
    });

    // Subcategorias
    Route::prefix('subcategorias')->group(function () {
        Route::get('/', 'App\Http\Controllers\Api\SubCategoriaController@index');
        Route::post('/', 'App\Http\Controllers\Api\SubCategoriaController@create');
        Route::put('/{id}', 'App\Http\Controllers\Api\SubCategoriaController@update');
        Route::delete('/{id}', 'App\Http\Controllers\Api\SubCategoriaController@delete');
        Route::delete('purge/{id}', 'App\Http\Controllers\Api\SubCategoriaController@purge');
    });

    // Entradas
    Route::prefix('entradas')->group(function () {
        Route::get('/', 'App\Http\Controllers\Api\EntradaController@index');
        Route::post('/', 'App\Http\Controllers\Api\EntradaController@create');
        Route::put('/{id}', 'App\Http\Controllers\Api\EntradaController@update');
        Route::delete('/{id}', 'App\Http\Controllers\Api\EntradaController@delete');
        Route::delete('purge/{id}', 'App\Http\Controllers\Api\EntradaController@purge');
        Route::post('/distribuir-valores', 'App\Http\Controllers\Api\EntradaController@distribuirValores');
    });


    // Saídas
    Route::prefix('saidas')->group(function () {
        Route::get('/', 'App\Http\Controllers\Api\SaidaController@index');
        Route::post('/', 'App\Http\Controllers\Api\SaidaController@create');
        Route::put('/{id}', 'App\Http\Controllers\Api\SaidaController@update');
        Route::delete('/{id}', 'App\Http\Controllers\Api\SaidaController@delete');
        Route::delete('purge/{id}', 'App\Http\Controllers\Api\SaidaController@purge');
    });

    // Metas
    Route::prefix('metas')->group(function () {
        Route::get('/', 'App\Http\Controllers\Api\MetaController@index'); // Listar metas
        Route::post('/', 'App\Http\Controllers\Api\MetaController@create'); // Criar nova meta
        Route::put('/{id}', 'App\Http\Controllers\Api\MetaController@update'); // Atualizar meta
        Route::delete('/{id}', 'App\Http\Controllers\Api\MetaController@delete'); // Deletar meta
        Route::delete('purge/{id}', 'App\Http\Controllers\Api\MetaController@purge'); // Remover meta permanentemente
        Route::put('/{id}/valor', 'App\Http\Controllers\Api\MetaController@updateValorActual'); // Atualizar valor atual
        Route::get('/{id}/progresso', 'App\Http\Controllers\Api\MetaController@getProgress'); // Obter progresso da meta
    });

    // Rotas para distribuição de valores
Route::post('/entradas/{id_entrada}/redistribuir', 'App\Http\Controllers\Api\EntradaController@redistribuirEntrada');

Route::get('/saldo-geral', 'App\Http\Controllers\RedistribuicaoController@getSaldoGeral');
Route::post('/redistribuir', 'App\Http\Controllers\RedistribuicaoController@redistribuir');


    /** Rotas para o Relatório */
    Route::prefix('relatorio')->group(function () {
        Route::get('/distribuicao-gastos', 'App\Http\Controllers\Relatorio\RelatorioController@distribuicaoGastos');
        Route::get('/metas-progresso', 'App\Http\Controllers\Relatorio\RelatorioController@metasProgresso');
        Route::get('/fluxo-caixa', 'App\Http\Controllers\Relatorio\RelatorioController@fluxoCaixa');
        Route::get('/resumo-mensal', 'App\Http\Controllers\Relatorio\RelatorioController@resumoMensal');
    });
});
