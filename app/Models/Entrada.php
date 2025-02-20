<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrada extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'data_entrada',
        'id_users',
        'id_categoria',
        'id_meta',
    ];

    protected $casts = [
        'data_entrada' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function meta()
    {
        return $this->belongsTo(Meta::class, 'id_meta');
    }
}
