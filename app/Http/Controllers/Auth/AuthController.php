<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\SubCategoria;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registrar um novo Usuário

    public function register(Request $request)
    {
      //  dd($request->all());
        DB::beginTransaction();
        try {
            // Validar entrada
            $request->validate([
                'name' => 'required|string|max:255',
                'sobrenome' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed'
            ]);

            // Criar usuário
            $user = User::create([
                'name' => $request->name,
                'sobrenome' => $request->sobrenome,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nivel' => 1
            ]);

            // Criar categorias padrão
            $this->createDefaultCategories($user);

            // Criar token de autenticação
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Usuário criado com sucesso',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar usuário: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function createDefaultCategories($user)
    {
        $defaultCategories = [
            [
                'nome' => 'Salário',
                'descricao' => 'Rendimentos profissionais',
                'subcategorias' => [
                    ['nome' => 'Salário Fixo', 'descricao' => 'Salário mensal'],
                    ['nome' => 'Bônus', 'descricao' => 'Bônus e gratificações']
                ]
            ],
            [
                'nome' => 'Alimentação',
                'descricao' => 'Sexta básica e refeições',
                'subcategorias' => [
                    ['nome' => 'Legumes', 'descricao' => 'Verduras e legumes'],
                    ['nome' => 'Carnes', 'descricao' => 'Carnes bovinas e suínas'],
                    ['nome' => 'Frutas', 'descricao' => 'Frutas frescas']
                ]
            ],
            // ... outras categorias
        ];

        // Criar categorias e subcategorias
        foreach ($defaultCategories as $categoryData) {
            $subcategorias = $categoryData['subcategorias'];
            unset($categoryData['subcategorias']);

            // Criar categoria
            $category = Categoria::create([
                'nome' => $categoryData['nome'],
                'descricao' => $categoryData['descricao'],
                'id_users' => $user->id
            ]);

            // Criar subcategorias
            foreach ($subcategorias as $subcategoryData) {
                SubCategoria::create([
                    'nome' => $subcategoryData['nome'],
                    'descricao' => $subcategoryData['descricao'],
                    'id_categoria' => $category->id,
                    'id_users' => $user->id // Adicionando referência ao usuário
                ]);
            }
        }
    }



    //Login e token users

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['As suas credenciais estão erradas'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Verifica o nível do usuário
        if ($user->level == 2) {
            // Redireciona para uma view se for um administrador
            return view('dashboard', ['user' => $user]);
        }

        // Retorna JSON se for um usuário normal
        return response()->json([
            'status' => true,
            'message' => 'Logado',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    //Terminar a sessão
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sessão Terminada'
        ]);
    }
}
