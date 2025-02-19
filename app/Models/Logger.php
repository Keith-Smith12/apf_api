<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Logs;
use App\Models\LogProfessor;

class Logger extends Model
{
    public function log($level,$descricao)
    {

        if(Auth::user())
        {
           Logs::create([
                'it_id_user' => Auth::user()->id,
                'vc_descricao' => $descricao,
                'vc_endereco' => $_SERVER['REMOTE_ADDR'],
                'vc_dispositivo' =>  $_SERVER['HTTP_USER_AGENT'],
            ]);
            $descricao = /* Auth::user()->vc_nome. */'-'.$descricao;

        }
        else{
            $descricao = 'erro'.'-'.$descricao;
        }


    Log::channel('logUser')->$level($descricao);

    }

    public  function LogsForSearch($datatime, $user)
    {
    //$datatime=null;
    //$user=null;
        $response['logs'] =DB::table('logs')
        ->join('users', 'logs.id_user', '=', 'users.id')
        ->select('logs.*','users.name');
        if($datatime !="null")
            $response['logs']->whereYear('logs.created_at', '=', $datatime);

        if($user !="null")
            $response['logs']->where([['users.name', '=', $user]]);


          return  $response['logs']->get();
        }
}
