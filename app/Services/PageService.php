<?php

namespace App\Services;

use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class PageService
{
    public function __construct(
        protected PageRepositoryInterface $repository
    ) {}

    public function query(): Builder
    {
        return $this->repository->query();
    }

    public function all(array $columns = ['*'])
    {
        return $this->repository->all($columns);
    }

    public function find(int|string $id, array $columns = ['*'])
    {
        return $this->repository->find($id, $columns);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int|string $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->repository->paginate($perPage, $columns);
    }

    public function findBy(string $field, mixed $value, array $columns = ['*'])
    {
        return $this->repository->findBy($field, $value, $columns);
    }
}
