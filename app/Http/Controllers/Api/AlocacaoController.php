<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entrada;
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
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'entrada_id' => 'required|exists:entradas,id',
            'alocacoes' => 'required|array',
            'alocacoes.*.categoria_id' => 'nullable|exists:categorias,id',
            'alocacoes.*.subcategoria_id' => 'nullable|exists:subcategorias,id',
            'alocacoes.*.meta_id' => 'nullable|exists:metas,id',
            'alocacoes.*.valor' => 'required|numeric|min:0',
        ]);

        // Se a validação falhar, retorne os erros
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Iniciar uma transação para garantir a atomicidade
        DB::beginTransaction();

        try {
            $entrada = Entrada::findOrFail($request->entrada_id);
            $valorTotalAlocado = 0;

            // Registrar cada alocação
            foreach ($request->alocacoes as $alocacao) {
                $valorTotalAlocado += $alocacao['valor'];

                Alocacoe::create([
                    'entrada_id' => $entrada->id,
                    'categoria_id' => $alocacao['categoria_id'] ?? null,
                    'subcategoria_id' => $alocacao['subcategoria_id'] ?? null,
                    'meta_id' => $alocacao['meta_id'] ?? null,
                    'valor' => $alocacao['valor'],
                ]);
            }

            // Verificar se o valor total alocado não ultrapassa o valor da entrada
            if ($valorTotalAlocado > $entrada->valor) {
                throw new \Exception('O valor total alocado não pode ser maior que o valor da entrada.');
            }

            // Commit da transação
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Valores alocados com sucesso!',
                'valor_total_alocado' => $valorTotalAlocado,
                'valor_disponivel' => $entrada->valor - $valorTotalAlocado,
            ], 200);

        } catch (\Exception $e) {
            // Rollback da transação em caso de erro
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao alocar valores: ' . $e->getMessage(),
            ], 500);
        }
    }
}
