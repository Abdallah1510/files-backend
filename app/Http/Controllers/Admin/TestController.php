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
use App\Models\PropertyType;
use App\Models\PropertyStatus;
use App\Models\FinishingType;
use App\Models\Feature;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\FeatureResource;

class TestController extends Controller
{
    protected PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid admin credentials'
            ], 401);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token
        ]);
    }

    public function createPropertyType(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:property_types,slug',
        ]);

        return PropertyType::create($validated);
    }

    public function createPropertyStatus(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:property_statuses,slug',
        ]);

        return PropertyStatus::create($validated);
    }

    public function createFinishingType(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:finishing_types,slug',
        ]);

        return FinishingType::create($validated);
    }

    public function createFeature(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:features,slug',
            'icon' => 'nullable|string',
        ]);

        $feature = Feature::create($validated);

        return new FeatureResource($feature);
    }

    /*
    |--------------------------------------------------------------------------
    | Properties - CRUD
    |--------------------------------------------------------------------------
    */

    public function listProperties(Request $request)
    {
        $properties = $this->propertyService->getFilteredProperties($request);
        return PropertyResource::collection($properties);
    }

    public function showProperty($id)
    {
        $property = $this->propertyService->getPropertyById($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        return new PropertyResource($property);
    }

    public function createProperty(StorePropertyRequest $request)
    {
        $property = $this->propertyService->createProperty(
            $request->validated()
        );

        return new PropertyResource($property);
    }

    public function updateProperty(UpdatePropertyRequest $request, $id)
    {
        $property = $this->propertyService->updateProperty(
            $id,
            $request->validated()
        );

        return new PropertyResource($property);
    }

    public function deleteProperty($id)
    {
        $this->propertyService->deleteProperty($id);

        return response()->json([
            'message' => 'Property deleted successfully'
        ]);
    }

    public function restoreProperty($id)
    {
        $this->propertyService->restoreProperty($id);

        return response()->json([
            'message' => 'Property restored successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Media
    |--------------------------------------------------------------------------
    */

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