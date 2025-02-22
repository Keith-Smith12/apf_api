<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategoria;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubCategoriaController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $SubCategoria = SubCategoria::where('id_users', $userId)->get();

            return response()->json($SubCategoria, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao listar Subcategorias', 'message' => $e->getMessage()], 500);
        }
    }

    // 🔹 Criar Subcategoria
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|min:3|max:30',
                'descricao' => 'required|min:5',
                'valor' => 'required|numeric',
                'id_categoria'=>'resquired',
                'id_users'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $SubCategoria = SubCategoria::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'id_categoria'=>$request->id_categoria,
                'id_users' => Auth::id(),
            ]);

            return response()->json($SubCategoria, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao criar Subcategoria', 'message' => $e->getMessage()], 500);
        }
    }

    // 🔹 Atualizar Subcategoria
    public function update(Request $request, $id)
    {
        try {
            $SubCategoria = SubCategoria::where('id_users', Auth::id())->find($id);
            if (!$SubCategoria) {
                return response()->json(['error' => 'Subcategoria não encontrada'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nome' => 'required|min:3|max:30',
                'descricao' => 'required|min:5',
                'valor' => 'required|numeric',
                'id_categoria'=>'required',
                'id_users'=>'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $SubCategoria->update($request->all());

            return response()->json($SubCategoria, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar Subcategoria', 'message' => $e->getMessage()], 500);
        }
    }
    // 🔹 Mostrar uma Subcategoria específica
    public function show($id)
    {
        try {
            $SubCategoria = SubCategoria::where('id_users', Auth::id())->find($id);
            if (!$SubCategoria) {
                return response()->json(['error' => 'Subcategoria não encontrada'], 404);
            }

            return response()->json($SubCategoria, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar Subcategoria', 'message' => $e->getMessage()], 500);
        }
    }
    // 🔹 Deletar Categoria
    public function destroy($id)
    {
        try {
            $SubCategoria = SubCategoria::where('id_users', Auth::id())->find($id);
            if (!$SubCategoria) {
                return response()->json(['error' => 'Categoria não encontrada'], 404);
            }
            $SubCategoria->delete();
            
            return response()->json(['message' => 'Subcategoria deletada com sucesso'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao deletar Subcategoria', 'message' => $e->getMessage()], 500);
        }
    }
}
