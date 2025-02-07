<?php


namespace App\Repositories;

use App\Models\Alerta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlertaRepository
{
    private $model;

    public function __construct(Alerta $model)
    {
        $this->model = $model;
    }

    public function getAllByUser(): Collection
    {
        return $this->model
            ->where('id_users', Auth::id())
            ->latest()
            ->get();
    }

    public function findByIdAndUser(int $id): Alerta
    {
        $alerta = $this->model
            ->where('id_users', Auth::id())
            ->find($id);

        if (!$alerta) {
            throw new NotFoundHttpException('Alerta não encontrado');
        }

        return $alerta;
    }

    public function create(array $data): Alerta
    {
        $data['id_users'] = Auth::id();
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Alerta
    {
        $alerta = $this->findByIdAndUser($id);
        $alerta->update($data);
        return $alerta->fresh();
    }

    public function delete(int $id): void
    {
        $alerta = $this->findByIdAndUser($id);
        $alerta->delete();
    }
}
