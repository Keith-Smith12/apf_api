<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logs extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'it_id_user',
        'vc_descricao',
        'vc_endereco',
        'vc_dispositivo',
    ];
}
