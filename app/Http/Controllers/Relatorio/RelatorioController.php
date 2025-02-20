<?php

namespace App\Http\Controllers\Relatorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RelatorioController extends Controller
{
    //
    public function resumoMensal()
    {
        $userId = auth()->id();
        $inicio = Carbon::now()->startOfMonth();
        $fim = Carbon::now()->endOfMonth();

        $entradas = DB::table('entradas')
            ->where('id_users', $userId)
            ->whereBetween('data_entrada', [$inicio, $fim])
            ->sum('valor');

        $saidas = DB::table('saidas')
            ->where('id_users', $userId)
            ->whereBetween('data_saida', [$inicio, $fim])
            ->sum('valor');

        return response()->json([
            'entradas' => $entradas,
            'saidas' => $saidas,
            'saldo' => $entradas - $saidas
        ]);
    }

    public function fluxoCaixa(Request $request)
    {
        $userId = auth()->id();
        $periodo = $request->query('periodo', 'mes');
        $dataInicial = $request->query('data_inicial');
        $dataFinal = $request->query('data_final');

        // Se não foram fornecidas datas, usa o período
        if (!$dataInicial || !$dataFinal) {
            $dataFinal = Carbon::now();
            $dataInicial = match($periodo) {
                'semana' => Carbon::now()->subDays(7),
                'mes' => Carbon::now()->subDays(30),
                'ano' => Carbon::now()->subMonths(12),
                default => Carbon::now()->subDays(30)
            };
        } else {
            $dataInicial = Carbon::parse($dataInicial);
            $dataFinal = Carbon::parse($dataFinal);
        }

        // Gera array de datas para preencher dias sem movimentação
        $periodo = CarbonPeriod::create($dataInicial, $dataFinal);
        $datas = [];
        foreach ($periodo as $data) {
            $datas[$data->format('Y-m-d')] = [
                'data' => $data->format('Y-m-d'),
                'entradas' => 0,
                'saidas' => 0,
                'saldo' => 0
            ];
        }

        // Busca entradas
        $entradas = DB::table('entradas')
            ->select(
                DB::raw('DATE(data_entrada) as data'),
                DB::raw('SUM(valor) as total')
            )
            ->where('id_users', $userId)
            ->whereBetween('data_entrada', [$dataInicial, $dataFinal])
            ->groupBy('data')
            ->get();

        // Busca saídas
        $saidas = DB::table('saidas')
            ->select(
                DB::raw('DATE(data_saida) as data'),
                DB::raw('SUM(valor) as total')
            )
            ->where('id_users', $userId)
            ->whereBetween('data_saida', [$dataInicial, $dataFinal])
            ->groupBy('data')
            ->get();

        // Preenche os valores nas datas correspondentes
        foreach ($entradas as $entrada) {
            $datas[$entrada->data]['entradas'] = $entrada->total;
            $datas[$entrada->data]['saldo'] += $entrada->total;
        }

        foreach ($saidas as $saida) {
            $datas[$saida->data]['saidas'] = $saida->total;
            $datas[$saida->data]['saldo'] -= $saida->total;
        }

        // Calcula totais
        $totais = [
            'total_entradas' => $entradas->sum('total'),
            'total_saidas' => $saidas->sum('total'),
            'saldo_periodo' => $entradas->sum('total') - $saidas->sum('total')
        ];

        // Organiza dados por categoria
        $categorias = DB::table('saidas')
            ->join('categorias', 'saidas.id_categoria', '=', 'categorias.id')
            ->select(
                'categorias.id',
                'categorias.nome',
                DB::raw('SUM(saidas.valor) as total')
            )
            ->where('saidas.id_users', $userId)
            ->whereBetween('data_saida', [$dataInicial, $dataFinal])
            ->groupBy('categorias.id', 'categorias.nome')
            ->get();

        return response()->json([
            'periodo' => [
                'inicio' => $dataInicial->format('Y-m-d'),
                'fim' => $dataFinal->format('Y-m-d')
            ],
            'movimentacoes' => array_values($datas),
            'totais' => $totais,
            'categorias' => $categorias
        ]);
    }

    public function distribuicaoGastos()
    {
        $userId = auth()->id();

        $gastosPorCategoria = DB::table('saidas')
            ->join('categorias', 'saidas.id_categoria', '=', 'categorias.id')
            ->select(
                'categorias.id',
                'categorias.nome',
                DB::raw('SUM(saidas.valor) as total')
            )
            ->where('saidas.id_users', $userId)
            ->where('saidas.data_saida', '>=', Carbon::now()->startOfMonth())
            ->groupBy('categorias.id', 'categorias.nome')
            ->get();

        return response()->json($gastosPorCategoria);
    }

    public function metasProgresso()
    {
        $userId = auth()->id();

        $metas = DB::table('metas')
            ->select(
                'id',
                'nome',
                'valor as meta',
                'valor_actual as atual'
            )
            ->where('id_users', $userId)
            ->get()
            ->map(function($meta) {
                $meta->progresso = ($meta->atual / $meta->meta) * 100;
                return $meta;
            });

        return response()->json($metas);
    }
}
