<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategoria extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'valor',
        'nome',
        'descricao',
        'id_users',
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

     // New method to create subcategories
     public static function createForCategoria($nome, $descricao, $categoriaId)
     {
         return static::create([
             'nome' => $nome,
             'descricao' => $descricao,
             'id_categoria' => $categoriaId
         ]);
     }
}
