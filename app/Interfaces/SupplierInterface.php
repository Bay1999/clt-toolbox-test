<?php

namespace App\Interfaces;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

interface SupplierInterface
{
    public function getAll(): Collection;
    public function getDataTable(?string $search = null): Builder;
    public function getById(int $id): Supplier;
    public function create(array $data): Supplier;
    public function update(int $id, array $data): Supplier;
    public function delete(int $id): Supplier;
}
