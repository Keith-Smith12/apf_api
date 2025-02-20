<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alerta extends Model
{
    //
    use SoftDeletes;

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
