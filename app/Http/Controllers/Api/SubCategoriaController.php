<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubCategoriaController extends Controller
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
            'id_categoria' => 'required|exists:categorias,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $subCategoria = SubCategoria::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'id_categoria' => $request->id_categoria
            ]);

            Cache::tags(['user_subcategorias_'.$userId])->flush();

            DB::commit();
            return response()->json($subCategoria, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $userId = Auth::id();

        return Cache::tags(['user_subcategorias_'.$userId])
            ->remember('user_subcategorias_'.$userId, now()->addHours(24), function () use ($userId) {
                return SubCategoria::with('categoria.user')
                    ->whereHas('categoria', function($query) use ($userId) {
                        $query->where('id_users', $userId);
                    })
                    ->get();
            });
    }

    public function update($id, Request $request)
    {
        $userId = Auth::id();
        $subCategoria = SubCategoria::whereHas('categoria', function($query) use ($userId) {
                $query->where('id_users', $userId);
            })
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nome' => 'sometimes|string|max:255',
            'descricao' => 'nullable|string',
            'id_categoria' => 'sometimes|exists:categorias,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $subCategoria->update($request->only(['nome', 'descricao', 'id_categoria']));

            Cache::tags(['user_subcategorias_'.$userId])->flush();

            DB::commit();
            return response()->json($subCategoria);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        $userId = Auth::id();
        $subCategoria = SubCategoria::whereHas('categoria', function($query) use ($userId) {
                $query->where('id_users', $userId);
            })
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $subCategoria->delete();

            Cache::tags(['user_subcategorias_'.$userId])->flush();

            DB::commit();
            return response()->json(['message' => 'Subcategoria removida']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function purge($id)
    {
        $userId = Auth::id();
        $subCategoria = SubCategoria::withTrashed()
            ->whereHas('categoria', function($query) use ($userId) {
                $query->where('id_users', $userId);
            })
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $subCategoria->forceDelete();

            Cache::tags(['user_subcategorias_'.$userId])->flush();

            DB::commit();
            return response()->json(['message' => 'Subcategoria removida permanentemente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
