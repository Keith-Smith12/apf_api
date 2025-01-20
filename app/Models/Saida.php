<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saida extends Model
{
    //

    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'data_saida',
        'id_categoria',
        'id_subcategoria',
    ];

    protected $casts = [
        'data_saida' => 'date',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function subCategoria()
    {
        return $this->belongsTo(SubCategoria::class, 'id_subcategoria');
    }
}
