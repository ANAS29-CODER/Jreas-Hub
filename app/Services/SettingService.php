<?php

namespace App\Services;

use App\Enums\SettingGroup;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class SettingService
{
    public function __construct(
        protected SettingRepositoryInterface $repository
    ) {}

    public function getAllAsKeyValue(): array
    {
        return $this->repository->all()->pluck('value', 'key')->all();
    }

    public function saveMany(array $data, SettingGroup $group): void
    {
        foreach ($data as $key => $value) {
            $setting = $this->repository->findBy('key', $key);

            if ($setting) {
                $this->repository->update($setting->getKey(), ['value' => $value]);

                continue;
            }

            $this->repository->create([
                'key' => $key,
                'value' => $value,
                'group' => $group->value,
            ]);
        }
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
