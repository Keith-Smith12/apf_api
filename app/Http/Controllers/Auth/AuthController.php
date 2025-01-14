<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registrar um novo Usuário

    public function Register(Request $request){

        //dd($request);
        try {
            //Validação de Dados
            $request->validate([
                'name' => 'required|string|max:255',
                'sobrenome' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed'
            ]);

            $user = User::Create([
                'name' => $request->name,
                'sobrenome' => $request->sobrenome,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nivel' => 1 // Definindo nível padrão como 1 (usuário normal)
            ]);

            //Criar Tokens

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Usuário criado com sucesso',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar usuário',
                'error' => $e->getMessage()
            ], 500);
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
