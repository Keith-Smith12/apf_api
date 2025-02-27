<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Meta;
use App\Models\Saida;
use App\Models\SubCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class SaidaController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $Saida = Saida::where('id_users', $userId)->get();

            return response()->json($Saida, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao listar categorias', 'message' => $e->getMessage()], 500);
        }
    }

    // 🔹 Criar Categoria
    public function store(Request $request)
    {

        dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|min:3|max:30',
                'descricao' => 'required|min:5',
                'valor' => 'required|numeric',
                'data_saida'=>'required',
                'id_categoria'=>'nullable',
                'id_meta'=>'nullable',
                'id_subcategoria'=>'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            // Verifica se apenas um dos IDs foi informado
            if ((empty($request->id_categoria) && empty($request->id_meta) && empty($request->id_subcategoria)) ||
            (!empty($request->id_categoria) && !empty($request->id_meta)) && !empty($request->id_subcategoria)) {
            return response()->json(['error' => 'Você deve fornecer apenas um dos IDs: id_categoria, id_subcategoria id_meta'], 400);
            }

            $Saida = Saida::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'data_saida'=>$request->data_saida,
                'id_categoria'=>$request->id_categoria,
                'id_meta'=>$request->id_meta,
                'id_subcategoria'=>$request->id_subcategoria,
                'id_users' => Auth::id(),
            ]);
            $valorTotal = $request->valor;
            // Atualizar o valor da categoria
            if($request-> id_categoria){
            $categoria = Categoria::findOrFail($request->id_categoria);
            $categoria->valor -= $valorTotal; // Subtrair o valor da categoria
            $categoria->save(); // Salvar as alterações
            }
             // Atualizar o valor da metas
            elseif($request->id_meta){
            $valorTotalmeta = $request->valor;
            $Metas = Meta::findOrFail($request->id_meta);
            $Metas->valor_actual -= $valorTotalmeta; // Adicione o valor à meta
            $Metas->save(); // Salve as alterações
            }
             // Atualizar o valor da Subcategoria
            elseif($request->id_subcategoria){
            $valorTotalsubcategoria = $request-> valor;
            $Subcategoria = SubCategoria::findOrFail($request->id_subcategoria);
            $Subcategoria-> valor -= $valorTotalsubcategoria;
            $Subcategoria->save();
            }
            return response()->json($Saida, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao criar Saida', 'message' => $e->getMessage()], 500);
        }
    }
    // 🔹 Atualizar Saida
    public function update(Request $request, $id)
    {
        try {
            $Saida = Saida::where('id_users', Auth::id())->find($id);
            if (!$Saida) {
                return response()->json(['error' => 'Saida não encontrada'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nome' => 'required|min:3|max:30',
                'descricao' => 'required|min:5',
                'valor' => 'required|numeric',
                'data_saida'=>'required',
                'id_categoria'=>'nullable',
                'id_meta'=>'nullable',
                'id_subcategoria'=>'nullable',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            // Armazenar o valor anterior para calcular a diferença
            $valorAnterior = $Saida->valor;
            $valorNovo = $request->valor;
            // Calcular a diferença
            $diferenca = $valorNovo - $valorAnterior;
            $Saida->update($request->all());
            // Atualizar o valor da categoria
            if ($Saida->id_categoria) {
                $categoria = Categoria::findOrFail($Saida->id_categoria);
                $categoria->valor -= $diferenca; // Subtrair a diferença do valor da categoria
                $categoria->save(); // Salvar as alterações
            }

            // Atualizar o valor da meta
            if ($Saida->id_meta) {
                $metas = Meta::findOrFail($Saida->id_meta);
                $metas->valor_actual -= $diferenca; // Subtrair a diferença do valor da meta
                $metas->save(); // Salvar as alterações
            }
            // Atualizar o valor da Subcategoria
            if ($Saida->id_subcategoria) {
                $Subcategoria = SubCategoria::findOrFail($Saida->id_subcategoria);
                $Subcategoria->valor -= $diferenca; // Subtrair a diferença do valor da meta
                $Subcategoria->save(); // Salvar as alterações
            }
            return response()->json($Saida, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar Saida', 'message' => $e->getMessage()], 500);
        }
    }
    // 🔹 Mostrar uma Saida específica
    public function show($id)
    {
        try {
            $Saida = Saida::where('id_users', Auth::id())->find($id);
            if (!$Saida) {
                return response()->json(['error' => 'Saida não encontrada'], 404);
            }
            return response()->json($Saida, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar Saida', 'message' => $e->getMessage()], 500);
        }
    }
    // 🔹 Deletar Categoria
    public function destroy($id)
    {
        try {
            $Saida = Saida::where('id_users', Auth::id())->find($id);
            if (!$Saida) {
                return response()->json(['error' => 'Saida não encontrada'], 404);
            }

            $Saida->delete();

            return response()->json(['message' => 'Saida deletada com sucesso'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao deletar Saida', 'message' => $e->getMessage()], 500);
        }
    }
}
