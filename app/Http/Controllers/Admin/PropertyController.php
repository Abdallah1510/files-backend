<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Requests\AttachFeaturesRequest;
use App\Http\Requests\AddImagesRequest;
use App\Http\Requests\AddVideoRequest;
use App\Services\PropertyService;
use App\Http\Resources\PropertyResource;

class PropertyController extends Controller
{
    protected PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function index(Request $request)
    {
        $properties = $this->propertyService->getFilteredProperties($request);
        return PropertyResource::collection($properties);
    }

    public function show($id)
    {
        $property = $this->propertyService->getPropertyById($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        return new PropertyResource($property);
    }

    public function store(StorePropertyRequest $request)
    {
        $property = $this->propertyService->createProperty(
            $request->validated()
        );

        return new PropertyResource($property);
    }

    public function update(UpdatePropertyRequest $request, $id)
    {
        $property = $this->propertyService->updateProperty(
            $id,
            $request->validated()
        );

        return new PropertyResource($property);
    }

    public function destroy($id)
    {
        $this->propertyService->deleteProperty($id);

        return response()->json([
            'message' => 'Property deleted successfully'
        ]);
    }

    public function restore($id)
    {
        $this->propertyService->restoreProperty($id);

        return response()->json([
            'message' => 'Property restored successfully'
        ]);
    }

    public function attachFeatures(AttachFeaturesRequest $request, $propertyId)
    {
        $this->propertyService->attachFeatures(
            $propertyId,
            $request->validated()['feature_ids']
        );

        return response()->json([
            'message' => 'Features Attached Successfully'
        ]);
    }

    public function addMultipleImages(AddImagesRequest $request, $propertyId)
    {
        $this->propertyService->addImages(
            $propertyId,
            $request->validated()['images']
        );

        return response()->json([
            'message' => 'Images Added Successfully'
        ]);
    }

    public function addPropertyVideo(AddVideoRequest $request, $propertyId)
    {
        $this->propertyService->addVideo(
            $propertyId,
            $request->validated()
        );

        return response()->json([
            'message' => 'Video Added Successfully'
        ]);
    }
}