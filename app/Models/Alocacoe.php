<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alocacoe extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'entrada_id',
        'categoria_id',
        'subcategoria_id',
        'meta_id',
        'valor'
    ];

    /**
     * Alocacao belongs to Entrada
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entrada()
    {
        return $this->belongsTo(Entrada::class);
    }
    /**
     * Alocacao belongs to Categoria
     */

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    /*
    * Alocacao belongs to Subcategoria
    */
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    /**
     * Alocacao belongs to Meta
     */

    public function meta()
    {
        return $this->belongsTo(Meta::class);
    }
}
