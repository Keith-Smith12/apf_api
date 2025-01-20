<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'id_users',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function subCategorias()
    {
        return $this->hasMany(SubCategoria::class, 'id_categoria');
    }

    public function saidas()
    {
        return $this->hasMany(Saida::class, 'id_categoria');
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class, 'id_categoria');
    }
}
