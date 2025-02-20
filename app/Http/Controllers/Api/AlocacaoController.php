<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Meta;
use App\Models\Alocacoe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AlocacaoController extends Controller
{
    //
    /**
     * Aloca valores de uma entrada para categorias, subcategorias ou metas.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function alocarValor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entrada_id' => 'required|exists:entradas,id',
            'alocacoes' => 'required|array',
            'alocacoes.*.categoria_id' => 'nullable|exists:categorias,id',
            'alocacoes.*.subcategoria_id' => 'nullable|exists:subcategorias,id',
            'alocacoes.*.meta_id' => 'nullable|exists:metas,id',
            'alocacoes.*.valor' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $entrada = Entrada::findOrFail($request->entrada_id);
            $valorTotalAlocado = 0;
            $metasAtualizadas = [];

            foreach ($request->alocacoes as $alocacao) {
                $valorTotalAlocado += $alocacao['valor'];

                if ($valorTotalAlocado > $entrada->valor) {
                    throw new \Exception('O valor total alocado não pode ser maior que o valor da entrada.');
                }

                if (!empty($alocacao['meta_id'])) {
                    if (!isset($metasAtualizadas[$alocacao['meta_id']])) {
                        $metasAtualizadas[$alocacao['meta_id']] = 0;
                    }
                    $metasAtualizadas[$alocacao['meta_id']] += $alocacao['valor'];
                }

                Alocacoe::create([
                    'entrada_id' => $entrada->id,
                    'categoria_id' => $alocacao['categoria_id'] ?? null,
                    'subcategoria_id' => $alocacao['subcategoria_id'] ?? null,
                    'meta_id' => $alocacao['meta_id'] ?? null,
                    'valor' => $alocacao['valor'],
                ]);
            }

            // Atualiza todas as metas de uma vez
            foreach ($metasAtualizadas as $metaId => $valorAdicional) {
                $meta = Meta::findOrFail($metaId);
                $meta->valor_actual += $valorAdicional;

                // Atualiza o status baseado no progresso
                $progresso = ($meta->valor_actual / $meta->valor) * 100;

                if ($progresso >= 100) {
                    $meta->status = 'concluida';
                } elseif ($progresso > 0) {
                    $meta->status = 'em_andamento';
                }

                $meta->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Valores alocados com sucesso!',
                'valor_total_alocado' => $valorTotalAlocado,
                'valor_disponivel' => $entrada->valor - $valorTotalAlocado,
                'metas_atualizadas' => $metasAtualizadas
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alocar valores: ' . $e->getMessage(),
            ], 500);
        }
    }
}
