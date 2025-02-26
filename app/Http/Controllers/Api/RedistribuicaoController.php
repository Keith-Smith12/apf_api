<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use App\Models\Categoria;
use App\Models\Entrada;
use App\Models\MetaHistorico;
use App\Models\CategoriaHistorico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RedistribuicaoController extends Controller
{
    public function getSaldoGeral()
    {
        $user = Auth::user();

        // Calcula o saldo total (todas as entradas - todas as saídas)
        $totalEntradas = Entrada::where('id_users', $user->id)->sum('valor');
        $totalSaidas = DB::table('saidas')->where('id_users', $user->id)->sum('valor');

        // Calcula valores já distribuídos
        $totalDistribuidoMetas = MetaHistorico::where('id_users', $user->id)
            ->where('tipo', 'entrada')
            ->sum('valor');

        $totalDistribuidoCategorias = CategoriaHistorico::where('id_users', $user->id)
            ->where('tipo', 'entrada')
            ->sum('valor');

        $totalDistribuido = $totalDistribuidoMetas + $totalDistribuidoCategorias;

        // Saldo disponível para distribuição
        $saldoDisponivel = $totalEntradas - $totalSaidas - $totalDistribuido;

        return response()->json([
            'saldo' => $saldoDisponivel,
            'total_entradas' => $totalEntradas,
            'total_saidas' => $totalSaidas,
            'total_distribuido' => $totalDistribuido
        ]);
    }

    public function redistribuir(Request $request)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
            'distribuicoes' => 'required|array',
            'distribuicoes.*.id' => 'required|integer',
            'distribuicoes.*.tipo' => 'required|in:meta,categoria',
            'distribuicoes.*.valor' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Verifica saldo disponível
            $totalEntradas = Entrada::where('id_users', $user->id)->sum('valor');
            $totalSaidas = DB::table('saidas')->where('id_users', $user->id)->sum('valor');
            $totalDistribuido = MetaHistorico::where('id_users', $user->id)->where('tipo', 'entrada')->sum('valor') +
                              CategoriaHistorico::where('id_users', $user->id)->where('tipo', 'entrada')->sum('valor');

            $saldoDisponivel = $totalEntradas - $totalSaidas - $totalDistribuido;

            if ($request->valor > $saldoDisponivel) {
                return response()->json([
                    'message' => 'Saldo insuficiente para redistribuição'
                ], 400);
            }

            foreach ($request->distribuicoes as $distribuicao) {
                if ($distribuicao['tipo'] === 'meta') {
                    $meta = Meta::findOrFail($distribuicao['id']);

                    if ($meta->id_users !== $user->id) {
                        throw new \Exception('Meta não pertence ao usuário');
                    }

                    // Atualiza valor atual da meta
                    $meta->valor_actual += $distribuicao['valor'];
                    if ($meta->valor_actual > $meta->valor) {
                        throw new \Exception('Valor excede o objetivo da meta');
                    }
                    $meta->save();

                    // Registra histórico
                    MetaHistorico::create([
                        'id_meta' => $meta->id,
                        'valor' => $distribuicao['valor'],
                        'tipo' => 'entrada',
                        'data' => now(),
                        'id_users' => $user->id,
                        'descricao' => 'Redistribuição de saldo'
                    ]);
                } else {
                    $categoria = Categoria::findOrFail($distribuicao['id']);

                    if ($categoria->id_users !== $user->id) {
                        throw new \Exception('Categoria não pertence ao usuário');
                    }

                    // Atualiza valor da categoria
                    $categoria->valor += $distribuicao['valor'];
                    $categoria->save();

                    // Registra histórico
                    CategoriaHistorico::create([
                        'id_categoria' => $categoria->id,
                        'valor' => $distribuicao['valor'],
                        'tipo' => 'entrada',
                        'data' => now(),
                        'id_users' => $user->id,
                        'descricao' => 'Redistribuição de saldo'
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Valores redistribuídos com sucesso',
                'saldo_atualizado' => $saldoDisponivel - $request->valor
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao redistribuir valores',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
