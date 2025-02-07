<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Saida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaidaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function create(Request $request)
    {
        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'required|numeric|min:0',
            'data_saida' => 'required|date',
            'id_categoria' => 'required|exists:categorias,id',
            'id_subcategoria' => 'nullable|exists:sub_categorias,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $saida = Saida::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'data_saida' => $request->data_saida,
                'id_categoria' => $request->id_categoria,
                'id_subcategoria' => $request->id_subcategoria
            ]);

            Cache::tags(['user_saidas_'.$userId])->flush();

            DB::commit();
            return response()->json($saida, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $userId = Auth::id();

        return Cache::tags(['user_saidas_'.$userId])
            ->remember('user_saidas_'.$userId, now()->addHours(24), function () use ($userId) {
                return Saida::with(['categoria', 'subCategoria'])
                    ->whereHas('categoria', function($query) use ($userId) {
                        $query->where('id_users', $userId);
                    })
                    ->get();
            });
    }

    public function update($id, Request $request)
    {
        $userId = Auth::id();
        $saida = Saida::whereHas('categoria', function($query) use ($userId) {
                $query->where('id_users', $userId);
            })
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nome' => 'sometimes|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'sometimes|numeric|min:0',
            'data_saida' => 'sometimes|date',
            'id_categoria' => 'sometimes|exists:categorias,id',
            'id_subcategoria' => 'nullable|exists:sub_categorias,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $saida->update($request->only([
                'nome', 'descricao', 'valor',
                'data_saida', 'id_categoria', 'id_subcategoria'
            ]));

            Cache::tags(['user_saidas_'.$userId])->flush();

            DB::commit();
            return response()->json($saida);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        $userId = Auth::id();
        $saida = Saida::whereHas('categoria', function($query) use ($userId) {
                $query->where('id_users', $userId);
            })
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $saida->delete();

            Cache::tags(['user_saidas_'.$userId])->flush();

            DB::commit();
            return response()->json(['message' => 'Saída removida']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function purge($id)
    {
        $userId = Auth::id();
        $saida = Saida::withTrashed()
            ->whereHas('categoria', function($query) use ($userId) {
                $query->where('id_users', $userId);
            })
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $saida->forceDelete();

            Cache::tags(['user_saidas_'.$userId])->flush();

            DB::commit();
            return response()->json(['message' => 'Saída removida permanentemente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
