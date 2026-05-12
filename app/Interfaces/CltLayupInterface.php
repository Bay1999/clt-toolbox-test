<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\CltLayup;
use Illuminate\Database\Eloquent\Builder;

interface CltLayupInterface
{
    public function getAll(): Collection;
    public function getById(int $id): CltLayup;
    public function create(array $data): CltLayup;
    public function update(int $id, array $data): CltLayup;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function getBySupplierId(int $supplierId, ?string $search = null): Builder;
    public function incrementRevision(int $id): CltLayup;
}
