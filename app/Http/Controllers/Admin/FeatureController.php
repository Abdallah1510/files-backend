<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use App\Http\Resources\FeatureResource;

class FeatureController extends Controller
{
    /**
     * Display a listing of features.
     */
    public function index(Request $request)
    {
        $features = Feature::query()
            ->when($request->has('group'), function ($query) use ($request) {
                $query->where('group', $request->group);
            })
            ->orderBy('name_ar')
            ->get();

        return FeatureResource::collection($features);
    }

    /**
     * Store a newly created feature.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:features,slug',
            'icon' => 'nullable|string|max:100',
            'group' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $feature = Feature::create($validated);

        return new FeatureResource($feature);
    }

    /**
     * Display the specified feature.
     */
    public function show($id)
    {
        $feature = Feature::find($id);

        if (!$feature) {
            return response()->json([
                'message' => 'Feature not found'
            ], 404);
        }

        return new FeatureResource($feature);
    }

    /**
     * Update the specified feature.
     */
    public function update(Request $request, $id)
    {
        $feature = Feature::find($id);

        if (!$feature) {
            return response()->json([
                'message' => 'Feature not found'
            ], 404);
        }

        $validated = $request->validate([
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:features,slug,' . $id,
            'icon' => 'nullable|string|max:100',
            'group' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $feature->update($validated);

        return new FeatureResource($feature);
    }

    /**
     * Remove the specified feature.
     */
    public function destroy($id)
    {
        $feature = Feature::find($id);

        if (!$feature) {
            return response()->json([
                'message' => 'Feature not found'
            ], 404);
        }

        // Check if feature is used by any properties
        if ($feature->properties()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete feature that is attached to properties'
            ], 422);
        }

        $feature->delete();

        return response()->json([
            'message' => 'Feature deleted successfully'
        ]);
    }

    /**
     * Restore soft deleted feature.
     */
    public function restore($id)
    {
        $feature = Feature::withTrashed()->find($id);

        if (!$feature) {
            return response()->json([
                'message' => 'Feature not found'
            ], 404);
        }

        $feature->restore();

        return response()->json([
            'message' => 'Feature restored successfully'
        ]);
    }

    /**
     * Get feature groups.
     */
    public function groups()
    {
        $groups = Feature::select('group')
            ->whereNotNull('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        return response()->json([
            'data' => $groups
        ]);
    }
}