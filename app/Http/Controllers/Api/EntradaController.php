<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\CategoriaHistorico;
use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Meta;
use App\Models\MetaHistorico;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EntradaController extends Controller
{
    //


    public function create(Request $request)
    {
        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'required|numeric|min:0',
            'data_entrada' => 'required|date',
            'id_categoria' => 'nullable|exists:categorias,id',
            'id_meta' => 'nullable|exists:metas,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $entrada = Entrada::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'data_entrada' => $request->data_entrada,
                'id_users' => $userId,
                'id_categoria' => $request->id_categoria,
                'id_meta' => $request->id_meta
            ]);

            DB::commit();
            return response()->json($entrada, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $userId = Auth::id();

        return Entrada::with(['user', 'categoria', 'meta'])
            ->where('id_users', $userId)
            ->get();
    }

    public function update($id, Request $request)
    {
        $userId = Auth::id();
        $entrada = Entrada::where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nome' => 'sometimes|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'sometimes|numeric|min:0',
            'data_entrada' => 'sometimes|date',
            'id_categoria' => 'sometimes|exists:categorias,id',
            'id_meta' => 'nullable|exists:metas,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $entrada->update($request->only([
                'nome',
                'descricao',
                'valor',
                'data_entrada',
                'id_categoria',
                'id_meta'
            ]));

            DB::commit();
            return response()->json($entrada);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        $userId = Auth::id();
        $entrada = Entrada::where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $entrada->delete();

            DB::commit();
            return response()->json(['message' => 'Entrada removida']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function purge($id)
    {
        $userId = Auth::id();
        $entrada = Entrada::withTrashed()
            ->where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $entrada->forceDelete();

            DB::commit();
            return response()->json(['message' => 'Entrada removida permanentemente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function distribuirValores(Request $request)
    {
        $userId = Auth::id();

        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'valor_total' => 'required|numeric|min:0',
            'distribuicoes' => 'required|array',
            'distribuicoes.*.categoria_id' => 'required|exists:categorias,id',
            'distribuicoes.*.percentual' => 'required|numeric|min:0|max:100',
            'distribuicoes.*.meta' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $valorTotal = $request->valor_total;
        $distribuicoes = $request->distribuicoes;

        $totalPercentual = array_sum(array_column($distribuicoes, 'percentual'));

        if ($totalPercentual !== 100) {
            return response()->json(['error' => 'A soma dos percentuais deve ser igual a 100.'], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($distribuicoes as $distribuicao) {
                $valorDistribuido = ($distribuicao['percentual'] / 100) * $valorTotal;

                // Criar uma nova entrada para a distribuição
                Entrada::create([
                    'nome' => 'Distribuição para Categoria ' . $distribuicao['categoria_id'],
                    'valor' => $valorDistribuido,
                    'id_categoria' => $distribuicao['categoria_id'],
                    'id_users' => $userId,
                    // Outros campos conforme necessário
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Valores distribuídos com sucesso.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
