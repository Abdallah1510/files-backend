<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Property;

interface PropertyRepositoryInterface
{
    public function paginateWithFilters($request): LengthAwarePaginator;

    public function findById(int $id): ?Property;

    public function findWithRelations(int $id): ?Property;

    public function findWithTrashed(int $id): ?Property; // ✅ الجديد

    public function create(array $data): Property;

    public function update(Property $property, array $data): Property;

    public function delete(Property $property): void;

    public function restore(Property $property): void;
}