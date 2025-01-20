<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    //

    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'valor_actual',
        'data_prazo',
        'id_users',
    ];

    protected $casts = [
        'data_prazo' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
