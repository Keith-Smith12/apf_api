<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
});



/* START Rotas de Usuários */
Route::prefix('usuarios')->group(function () {
    Route::get('index', ['as' => 'admin.usuarios.index', 'uses' => 'Admin\UserController@index']);
    Route::get('create', ['as' => 'admin.usuarios.create', 'uses' => 'Admin\UserController@create']);
    Route::post('store', ['as' => 'admin.usuarios.store', 'uses' => 'Admin\UserController@store']);
    Route::get('show/{id}', ['as' => 'admin.usuarios.show', 'uses' => 'Admin\UserController@show']);
    Route::get('edit/{id}', ['as' => 'admin.usuarios.edit', 'uses' => 'Admin\UserController@edit']);
    Route::post('update/{id}', ['as' => 'admin.usuarios.update', 'uses' => 'Admin\UserController@update']);
    Route::delete('destroy/{id}', ['as' => 'admin.usuarios.destroy', 'uses' => 'Admin\UserController@destroy']); // Soft Delete
    Route::delete('purge/{id}', ['as' => 'admin.usuarios.forceDelete', 'uses' => 'Admin\UserController@forceDelete']); // Hard Delete
    Route::post('restore/{id}', ['as' => 'admin.usuarios.restore', 'uses' => 'Admin\UserController@restore']); // Restaurar usuário
    Route::get('deletados', ['as' => 'admin.usuarios.deletados', 'uses' => 'Admin\UserController@deletedUsers']); // Listar usuários eliminados
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
