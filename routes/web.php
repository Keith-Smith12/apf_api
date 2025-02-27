<?php

use Illuminate\Support\Facades\Route;




Route::get('/', 'App\Http\Controllers\Admin\DashboardController@index')->name('admin.index');

/*
// START Rotas de Usuários
Route::prefix('usuarios')->name('admin.usuarios.')->group(function () {
    Route::get('/', ['as' => 'index', 'uses' => 'Admin\UserController@index']); // Listar usuários
    Route::get('create', ['as' => 'create', 'uses' => 'Admin\UserController@create']); // Formulário de criação
    Route::post('store', ['as' => 'store', 'uses' => 'Admin\UserController@store']); // Armazenar usuário
    Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'Admin\UserController@edit']); // Formulário de edição
    Route::post('update/{id}', ['as' => 'update', 'uses' => 'Admin\UserController@update']); // Atualizar usuário
    Route::delete('destroy/{id}', ['as' => 'destroy', 'uses' => 'Admin\UserController@destroy']); // Soft Delete
    Route::get('deletados', ['as' => 'deletados', 'uses' => 'Admin\UserController@deletedUsers']); // Listar usuários deletados
    Route::post('restore/{id}', ['as' => 'restore', 'uses' => 'Admin\UserController@restore']); // Restaurar usuário
    Route::delete('purge/{id}', ['as' => 'forceDelete', 'uses' => 'Admin\UserController@forceDelete']); // Hard Delete
});
*/

Route::prefix('usuarios')->name('admin.usuarios.')->group(function () {
    Route::get('/', 'App\Http\Controllers\Admin\UserController@index')->name('index');
    Route::get('create', 'App\Http\Controllers\Admin\UserController@create')->name('create');
    Route::post('store', 'App\Http\Controllers\Admin\UserController@store')->name('store');
    Route::get('edit/{id}', 'App\Http\Controllers\Admin\UserController@edit')->name('edit');
    Route::post('update/{id}', 'App\Http\Controllers\Admin\UserController@update')->name('update');
    Route::delete('destroy/{id}', 'App\Http\Controllers\Admin\UserController@destroy')->name('destroy');
    Route::get('deletados', 'App\Http\Controllers\Admin\UserController@deletedUsers')->name('deletados');
    Route::post('restore/{id}', 'App\Http\Controllers\Admin\UserController@restore')->name('restore');
    Route::delete('purge/{id}', 'App\Http\Controllers\Admin\UserController@forceDelete')->name('forceDelete');
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
