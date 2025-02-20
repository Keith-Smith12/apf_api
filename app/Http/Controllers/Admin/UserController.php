<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function loggerData($mensagem)
    {
        $this->logger->Log('info', $mensagem);
    }

    public function index()
    {
        $usuarios = User::withTrashed()->get();
        $this->loggerData("Listou Usuários");
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nivel' => 'required|integer|min:1|max:2',
        ]);

        try {
            $usuario = User::create([
                'name' => $request->name,
                'sobrenome' => $request->sobrenome,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nivel' => $request->nivel,
            ]);

            $this->loggerData("Cadastrou o usuário {$usuario->name} ({$usuario->email})");
            return redirect()->back()->with('usuario.create.success', 1);
        } catch (\Throwable $th) {
            return redirect()->back()->with('usuario.create.error', 1);
        }
    }

    public function edit(int $id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.edit.index', compact('usuario'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8|confirmed',
            'nivel' => 'required|integer|min:1|max:3',
        ]);

        try {
            $usuario = User::findOrFail($id);
            $usuario->update([
                'name' => $request->name,
                'sobrenome' => $request->sobrenome,
                'email' => $request->email,
                'nivel' => $request->nivel,
            ]);

            if ($request->password) {
                $usuario->update(['password' => Hash::make($request->password)]);
            }

            $this->loggerData("Editou o usuário {$usuario->id} ({$usuario->email})");
            return redirect()->back()->with('usuario.update.success', 1);
        } catch (\Throwable $th) {
            return redirect()->back()->with('usuario.update.error', 1);
        }
    }

    public function destroy(int $id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->delete();
            $this->loggerData("Removeu o usuário {$usuario->id} ({$usuario->email})");
            return redirect()->back()->with('usuario.destroy.success', 1);
        } catch (\Throwable $th) {
            return redirect()->back()->with('usuario.destroy.error', 1);
        }
    }

    public function deletedUsers()
    {
        $this->loggerData("Listou Usuários Eliminados");
        $usuarios = User::onlyTrashed()->get();
        return view('admin.usuarios.eliminados.index', compact('usuarios'));
    }


    public function restore(int $id)
    {
        try {
            $usuario = User::onlyTrashed()->findOrFail($id);
            $usuario->restore();
            $this->loggerData("Restaurou o usuário {$usuario->id} ({$usuario->email})");
            return redirect()->back()->with('usuario.restore.success', 1);
        } catch (\Throwable $th) {
            return redirect()->back()->with('usuario.restore.error', 1);
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $usuario = User::onlyTrashed()->findOrFail($id);
            $usuario->forceDelete();
            $this->loggerData("Excluiu permanentemente o usuário {$usuario->id} ({$usuario->email})");
            return redirect()->back()->with('usuario.purge.success', 1);
        } catch (\Throwable $th) {
            return redirect()->back()->with('usuario.purge.error', 1);
        }
    }
}
