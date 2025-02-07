<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlertaRequest;
use App\Http\Resources\AlertaResource;
use App\Repositories\AlertaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AlertaController extends Controller
{
    private $repository;

    public function __construct(AlertaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): AnonymousResourceCollection
    {
        $alertas = $this->repository->getAllByUser();
        return AlertaResource::collection($alertas);
    }

    public function store(AlertaRequest $request): AlertaResource
    {
        $alerta = $this->repository->create($request->validated());
        return new AlertaResource($alerta);
    }

    public function show(int $id): AlertaResource
    {
        $alerta = $this->repository->findByIdAndUser($id);
        return new AlertaResource($alerta);
    }

    public function update(AlertaRequest $request, int $id): AlertaResource
    {
        $alerta = $this->repository->update($id, $request->validated());
        return new AlertaResource($alerta);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);
        return response()->json([], 204);
    }
}
