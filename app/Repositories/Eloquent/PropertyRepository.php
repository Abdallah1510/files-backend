<?php

namespace App\Repositories\Eloquent;

use App\Models\Property;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PropertyRepository implements PropertyRepositoryInterface
{
    /*
    |--------------------------------------------------------------------------
    | Listing With Filters
    |--------------------------------------------------------------------------
    */

    public function paginateWithFilters($request): LengthAwarePaginator
    {
        $query = Property::query()
            ->with([
                'type',
                'status',
                'finishingType',
                'images',
                'features'
            ]);

        // Soft Delete Policy
        if ($request->boolean('only_deleted')) {
            $query->onlyTrashed();
        } elseif ($request->boolean('with_deleted')) {
            $query->withTrashed();
        }

        $this->applyFilters($query, $request);
        $this->applySorting($query, $request);

        // حماية من طلب per_page ضخم
        $perPage = min($request->integer('per_page', 15), 100);

        return $query->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    private function applyFilters(Builder $query, $request): void
    {
        if ($request->filled('property_category')) {
            $query->where('property_category', $request->property_category);
        }

        if ($request->filled('property_type_id')) {
            $query->where('property_type_id', $request->property_type_id);
        }

        if ($request->filled('property_status_id')) {
            $query->where('property_status_id', $request->property_status_id);
        }

        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price_amount', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_amount', '<=', $request->max_price);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', $request->bathrooms);
        }

        if ($request->filled('min_area')) {
            $query->where('area', '>=', $request->min_area);
        }

        if ($request->filled('max_area')) {
            $query->where('area', '<=', $request->max_area);
        }

        if ($request->filled('feature_ids') && is_array($request->feature_ids)) {
            $featureIds = $request->feature_ids;

            $query->whereHas('features', function ($q) use ($featureIds) {
            $q->whereIn('features.id', $featureIds);
            }, '=', count($featureIds));
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%");
            });
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */

    private function applySorting(Builder $query, $request): void
    {
        switch ($request->get('sort')) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;

            case 'price_asc':
                $query->orderBy('price_amount', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price_amount', 'desc');
                break;

            default:
                $query->orderBy('sort_order', 'asc');
        }
    }

    /*
    |----------------------------------------------------------------------
    | Find Method
    |----------------------------------------------------------------------
    */
    public function find(int $id): ?Property
    {
        return Property::find($id);
    }

    public function findById(int $id): ?Property
    {
        return Property::find($id);
    }

    /*
    |----------------------------------------------------------------------
    | Find Methods with Relations
    |----------------------------------------------------------------------
    */
    public function findWithRelations(int $id): ?Property
    {
        return Property::with([
            'parent',
            'children',
            'type',
            'status',
            'finishingType',
            'features',
            'images',
            'videos'
        ])->find($id);
    }

    public function findWithTrashed(int $id): ?Property
    {
        return Property::withTrashed()->find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    public function create(array $data): Property
    {
        return Property::create($data);
    }

    public function update(Property $property, array $data): Property
    {
        $property->update($data);
        return $property;
    }

    public function delete(Property $property): void
    {
        $property->delete();
    }

    public function restore(Property $property): void
    {
        $property->restore();
    }
}