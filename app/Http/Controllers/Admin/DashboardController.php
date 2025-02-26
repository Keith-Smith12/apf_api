<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Entrada;
use App\Models\Meta;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $usersByMonth = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get();

    return view('admin.index', [
        'usersByMonth' => $usersByMonth,
        'users' => User::count(),
        'categorias' => Categoria::count(),
        'metas' => Meta::count(),
        'entradas' => Entrada::count(),]);

    }



}
