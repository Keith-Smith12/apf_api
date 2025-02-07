<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Alocacoe extends Model
{
    //
    use HasFactory;

    protected $fillable = ['entrada_id', 'categoria_id', 'subcategoria_id', 'meta_id', 'valor'];

    public function entrada() {
        return $this->belongsTo(Entrada::class);
    }

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria() {
        return $this->belongsTo(Subcategoria::class);
    }

    public function meta() {
        return $this->belongsTo(Meta::class);
    }

}
