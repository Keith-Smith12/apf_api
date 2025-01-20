<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
    //

    protected $fillable = [
        'nome',
        'descricao',
        'id_categoria',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function saidas()
    {
        return $this->hasMany(Saida::class, 'id_subcategoria');
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class, 'id_subcategoria');
    }
}
