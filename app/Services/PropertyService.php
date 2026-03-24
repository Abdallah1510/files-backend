<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyVideo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\PropertyRepositoryInterface;
use Illuminate\Validation\ValidationException;

class PropertyService
{
    protected PropertyRepositoryInterface $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /*
    |--------------------------------------------------------------------------
    | Listing
    |--------------------------------------------------------------------------
    */

    public function getFilteredProperties($request)
    {
        return $this->propertyRepository->paginateWithFilters($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function getPropertyById($id)
    {
        return $this->propertyRepository->findWithRelations($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */
    public function createProperty(array $data)
    {
        // dd($data);
        return DB::transaction(function () use ($data) {

            $this->validateParentLogic($data);

            $property = $this->propertyRepository->create([
                ...$data,
                'slug' => $this->generateUniqueSlug($data['title_en']),
                'currency' => 'EGP',
                'is_active' => true,
                'is_featured' => false,
            ]);

            return $this->propertyRepository->findWithRelations($property->id);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function updateProperty($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $property = $this->propertyRepository->findById($id);

            if (!$property) {
                throw new \Exception('Property not found.');
            }

            if (isset($data['property_category'])) {
                throw new \Exception('Property category cannot be changed.');
            }

            $this->validateParentUpdateLogic($property, $data);

            $this->propertyRepository->update($property, $data);

            if (isset($data['feature_ids'])) {
                $property->features()->sync($data['feature_ids']);
            }

            return $this->propertyRepository->findWithRelations($property->id);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteProperty($id)
    {
        return DB::transaction(function () use ($id) {

            $property = $this->propertyRepository->findById($id);

            if (!$property) {
                throw new \Exception('Property not found.');
            }

            // الحماية الأساسية موجودة داخل Model (booted)
            $this->propertyRepository->delete($property);

            return true;
        });
    }

    public function restoreProperty($id)
    {
        return DB::transaction(function () use ($id) {

            $property = $this->propertyRepository->findWithTrashed($id);

            if (!$property) {
                throw new \Exception('Property not found.');
            }

            if (!$property->trashed()) {
                throw new \Exception('Property is not deleted.');
            }

            if (
                $property->property_category === 'unit' &&
                $property->parent &&
                $property->parent->trashed()
            ) {
                throw new \Exception('Restore parent building first.');
            }

            $this->propertyRepository->restore($property);

            return true;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Attach Features
    |--------------------------------------------------------------------------
    */

    public function attachFeatures($propertyId, array $featureIds)
    {
        $property = $this->propertyRepository->findById($propertyId);

        if (!$property) {
            throw new \Exception('Property not found.');
        }

        $property->features()->sync($featureIds);
    }

    /*
    |--------------------------------------------------------------------------
    | Images
    |--------------------------------------------------------------------------
    */

public function addImages($propertyId, array $images)
{
    $property = $this->propertyRepository->findById($propertyId);

    if (!$property) {
        throw new \Exception('Property not found.');
    }

    foreach ($images as $imageData) {

        $isMain = $imageData['is_main'] ?? false;

        // إذا الصورة الجديدة رئيسية
        if ($isMain) {
            // نلغي أي صورة رئيسية سابقة
            $property->images()->update([
                'is_main' => false
            ]);
        }

        PropertyImage::create([
            'property_id' => $propertyId,
            'image_path' => $imageData['image_path'],
            'is_main' => $isMain,
            'sort_order' => $imageData['sort_order'] ?? 0,
        ]);
    }
}

    /*
    |--------------------------------------------------------------------------
    | Video
    |--------------------------------------------------------------------------
    */

    public function addVideo($propertyId, array $data)
    {
        $property = $this->propertyRepository->findById($propertyId);

        if (!$property) {
            throw new \Exception('Property not found.');
        }

        PropertyVideo::create([
            'property_id' => $propertyId,
            'video_url' => $data['video_url'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Property::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function validateParentLogic(array $data): void
{
    $category = $data['property_category'];
    $parentId = $data['parent_id'] ?? null;

    if ($category === 'unit') {

        if (!$parentId) {
            throw ValidationException::withMessages([
                'parent_id' => ['Unit must have a parent building.']
            ]);
        }

        $parent = $this->propertyRepository->find($parentId);

        if (!$parent || $parent->property_category !== 'building') {
            throw ValidationException::withMessages([
                'parent_id' => ['Parent must be a building.']
            ]);
        }
    }

    if (in_array($category, ['building', 'standalone']) && $parentId) {
        throw ValidationException::withMessages([
            'parent_id' => ['This property type cannot have a parent.']
        ]);
    }
}

    private function validateParentUpdateLogic($property, array $data): void
    {
        if ($property->property_category === 'unit') {

            if (array_key_exists('parent_id', $data)) {

                if (!$data['parent_id']) {
                    throw new \Exception('Unit must always have a parent.');
                }

                $parent = $this->propertyRepository->findById($data['parent_id']);

                if (!$parent || $parent->property_category !== 'building') {
                    throw new \Exception('Parent must be a building.');
                }
            }

        } else {

            if (array_key_exists('parent_id', $data)) {
                throw new \Exception('Only units can have a parent.');
            }
        }
    }
}