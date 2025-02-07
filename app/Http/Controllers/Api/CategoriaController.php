<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoriaController extends Controller
{
    //
    /* public function __construct()
    {
        $this->middleware('auth:sanctum');
    }*/

    public function create(Request $request)

    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255|unique:categorias,nome,NULL,id,id_users,' . Auth::id(),
                'descricao' => 'nullable|string'
            ]);
            $categoria = Categoria::create([
                'nome' => $validated['nome'],
                'descricao' => $validated['descricao'],
                'id_users' => Auth::id()
            ]);
            // Limpa o cache apenas deste usuário
            Cache::forget('user_categories_' . Auth::id());
            return response()->json([
              'status' => true,
                'message' => 'Categoria criada com sucesso',
              'data' => $categoria
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar categoria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $userId = Auth::id();
        $cacheKey = 'user_categories_' . $userId;
        return Cache::remember($cacheKey, Carbon::now()->addHours(24), function () use ($userId) {
            return Categoria::with(['user:id,name', 'subCategorias:id,nome,id_categoria'])
                ->where('id_users', $userId)
                ->get();
        });
    }

    public function update(Request $request, $id)

    {
        $categoria = Categoria::where('id_users', Auth::id())
            ->findOrFail($id);
        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string'
        ]);
        $categoria->update($validated);
        // Limpa o cache quando atualiza

        Cache::forget('user_categories_' . Auth::id());
        return response()->json($categoria);

    }

    public function destroy($id)

    {
        $categoria = Categoria::where('id_users', Auth::id())
            ->findOrFail($id);
        $categoria->delete();
        // Limpa o cache quando deleta
        Cache::forget('user_categories_' . Auth::id());
        return response()->json(null, 204);
    }


    public function purge($id)
    {
        $userId = Auth::id();
        $categoria = Categoria::withTrashed()
            ->where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $categoria->forceDelete();

            Cache::tags(['user_categories_' . $userId])->flush();

            DB::commit();
            return response()->json(['message' => 'Categoria removida permanentemente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
