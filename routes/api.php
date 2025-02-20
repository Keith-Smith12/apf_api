<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', 'App\Http\Controllers\Auth\AuthController@register');
Route::post('/login', 'App\Http\Controllers\Auth\AuthController@login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
    Route::post('/alocacoes', 'App\Http\Controllers\Api\AlocacaoController@alocarValor');
    Route::get('/alocacoes', 'App\Http\Controllers\Api\AlocacaoController@index');

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
        Route::get('/', 'App\Http\Controllers\Api\MetaController@index');
        Route::post('/', 'App\Http\Controllers\Api\MetaController@create');
        Route::put('/{id}', 'App\Http\Controllers\Api\MetaController@update');
        Route::delete('/{id}', 'App\Http\Controllers\Api\MetaController@delete');
        Route::delete('purge/{id}', 'App\Http\Controllers\Api\MetaController@purge');
    });


    /** Rotas para o Relatório */
    Route::prefix('relatorio')->group(function () {
        Route::get('/distribuicao-gastos', 'App\Http\Controllers\Relatorio\RelatorioController@distribuicaoGastos');
        Route::get('/metas-progresso', 'App\Http\Controllers\Relatorio\RelatorioController@metasProgresso');
        Route::get('/fluxo-caixa', 'App\Http\Controllers\Relatorio\RelatorioController@fluxoCaixa');
        Route::get('/resumo-mensal', 'App\Http\Controllers\Relatorio\RelatorioController@resumoMensal');
    });
});
