<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meta;
use App\Models\Entrada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MetaController extends Controller
{
    //
    public function create(Request $request)
    {
        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'required|numeric|min:0',
            'valor_actual' => 'nullable|numeric|min:0',
            'data_prazo' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $meta = Meta::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'valor_actual' => $request->valor_actual ?? 0,
                'data_prazo' => $request->data_prazo,
                'id_users' => $userId
            ]);

            DB::commit();
            return response()->json($meta, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function index()
    {
        $userId = Auth::id();

        return Meta::where('id_users', $userId)->get();
    }

    public function updateValorActual($id)
    {
        $userId = Auth::id();
        $meta = Meta::where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        DB::beginTransaction();
        try {

            $totalEntradas = Entrada::where('id_users', $userId)
                ->where('created_at', '<=', $meta->data_prazo)
                ->sum('valor');


            $meta->update([
                'valor_actual' => $totalEntradas
            ]);

            DB::commit();
            return response()->json([
                'meta' => $meta,
                'total_entradas' => $totalEntradas
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProgress($id)
    {
        $userId = Auth::id();
        $meta = Meta::where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        $totalEntradas = Entrada::where('id_users', $userId)
            ->where('created_at', '<=', $meta->data_prazo)
            ->sum('valor');

        $percentageComplete = ($totalEntradas / $meta->valor) * 100;

        return response()->json([
            'meta' => $meta,
            'total_entradas' => $totalEntradas,
            'percentage_complete' => min(100, round($percentageComplete, 2)),
            'remaining_value' => max(0, $meta->valor - $totalEntradas)
        ]);
    }

    
    public function update($id, Request $request)
    {
        $userId = Auth::id();
        $meta = Meta::where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nome' => 'sometimes|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'sometimes|numeric|min:0',
            'valor_actual' => 'sometimes|numeric|min:0',
            'data_prazo' => 'sometimes|date|after:today',
            'update_from_entries' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            if ($request->update_from_entries) {
                $totalEntradas = Entrada::where('id_users', $userId)
                    ->where('created_at', '<=', $meta->data_prazo)
                    ->sum('valor');

                $request->merge(['valor_actual' => $totalEntradas]);
            }

            $meta->update($request->only([
                'nome', 'descricao', 'valor',
                'valor_actual', 'data_prazo'
            ]));

            DB::commit();
            return response()->json($meta);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        $userId = Auth::id();
        $meta = Meta::where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $meta->delete();

            DB::commit();
            return response()->json(['message' => 'Meta removida']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function purge($id)
    {
        $userId = Auth::id();
        $meta = Meta::withTrashed()
            ->where('id', $id)
            ->where('id_users', $userId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $meta->forceDelete();

            DB::commit();
            return response()->json(['message' => 'Meta removida permanentemente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
