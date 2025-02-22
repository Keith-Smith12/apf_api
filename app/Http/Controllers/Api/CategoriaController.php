<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Categoria;
use App\Models\SubCategoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{

    // 🔹 Listar Categorias
    public function index()
    {
        try {
            $userId = Auth::id();
            $categorias = Categoria::where('id_users', $userId)->get();

            return response()->json($categorias, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao listar categorias', 'message' => $e->getMessage()], 500);
        }
    }

    // 🔹 Criar Categoria
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|min:3|max:30',
                'descricao' => 'required|min:5',
                'valor' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $categoria = Categoria::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'id_users' => Auth::id(),
            ]);

            return response()->json($categoria, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao criar categoria', 'message' => $e->getMessage()], 500);
        }
    }

    // 🔹 Atualizar Categoria
    public function update(Request $request, $id)
    {
        try {
            $categoria = Categoria::where('id_users', Auth::id())->find($id);
            if (!$categoria) {
                return response()->json(['error' => 'Categoria não encontrada'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nome' => 'required|min:3|max:30',
                'descricao' => 'required|min:5',
                'valor' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $categoria->update($request->all());

            return response()->json($categoria, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar categoria', 'message' => $e->getMessage()], 500);
        }
    }
    // 🔹 Mostrar uma Categoria específica
    public function show($id)
    {
        try {
            $categoria = Categoria::where('id_users', Auth::id())->find($id);
            if (!$categoria) {
                return response()->json(['error' => 'Categoria não encontrada'], 404);
            }

            return response()->json($categoria, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar categoria', 'message' => $e->getMessage()], 500);
        }
    }

    // 🔹 Deletar Categoria
    public function destroy($id)
    {
        try {
            $categoria = Categoria::where('id_users', Auth::id())->find($id);
            if (!$categoria) {
                return response()->json(['error' => 'Categoria não encontrada'], 404);
            }

            $categoria->delete();

            return response()->json(['message' => 'Categoria deletada com sucesso'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao deletar categoria', 'message' => $e->getMessage()], 500);
        }
    }

        // 🔹 Distribuir Valor
        public function distribuirValor(Request $request)
        {
            try{
                $validator = Validator::make($request->all(), [
                    'id_categoria' => 'required|exists:categorias,id',
                    'id_subcategoria' => 'required|exists:subcategorias,id',
                    'valor' => 'required|numeric|gt:0',
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                // Encontrar a categoria e a subcategoria
                $categoria = Categoria::findOrFail($request->id_categoria);
                $subcategoria = SubCategoria::findOrFail($request->id_subcategoria);

                // Verificar se a categoria tem saldo suficiente
                if ($categoria->valor < $request->valor) {
                    return response()->json(['error' => 'Saldo insuficiente na categoria'], 400);
                }
                // Iniciar transação
                 DB::beginTransaction();
                // Transferir o valor
                $categoria->valor -= $request->valor; // Subtrai da categoria
                $subcategoria->valor += $request->valor; // Adiciona à subcategoria

                // Salvar as alterações
                $categoria->save();
                $subcategoria->save();
                 // Confirmar transação
                  DB::commit();
                return response()->json([
                    'mensagem' => 'Valor distribuído com sucesso',
                    'categoria' => $categoria,
                    'subcategoria' => $subcategoria,
                ], 200);
            }catch(Exception $e){
                // Reverter a transação em caso de erro
                 DB::rollBack();
                return response()->json([
                    'error' => 'Erro ao distribuir valor',
                    'mensagem' => $e->getMessage(),
                ], 500);
            }

        }

}
