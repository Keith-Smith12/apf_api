<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaHistorico extends Model
{
    //

    use HasFactory;
    
    protected $fillable = [
        'id_meta',
        'valor',
        'tipo',
        'id_entrada',
        'data',
    ];

    // Definindo a relação com a tabela 'metas'
    public function meta()
    {
        return $this->belongsTo(Meta::class, 'id_meta');
    }

    // Definindo a relação com a tabela 'entradas'
    public function entrada()
    {
        return $this->belongsTo(Entrada::class, 'id_entrada');
    }
}
