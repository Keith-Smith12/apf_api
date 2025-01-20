<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    //

    protected $fillable = [
        'mensagem',
        'tipo',
        'status',
        'id_users',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
