<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinishingType;

class FinishingTypeController extends Controller
{
    /**
     * Display a listing of finishing types.
     */
    public function index()
    {
        $types = FinishingType::orderBy('name_ar')->get();

        return response()->json([
            'data' => $types
        ]);
    }

    /**
     * Store a newly created finishing type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:finishing_types,slug',
            'description' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        // If this is default, remove default from others
        if (isset($validated['is_default']) && $validated['is_default']) {
            FinishingType::where('is_default', true)->update(['is_default' => false]);
        }

        $type = FinishingType::create($validated);

        return response()->json([
            'message' => 'Finishing type created successfully',
            'data' => $type
        ], 201);
    }

    /**
     * Display the specified finishing type.
     */
    public function show($id)
    {
        $type = FinishingType::find($id);

        if (!$type) {
            return response()->json([
                'message' => 'Finishing type not found'
            ], 404);
        }

        return response()->json([
            'data' => $type
        ]);
    }

    /**
     * Update the specified finishing type.
     */
    public function update(Request $request, $id)
    {
        $type = FinishingType::find($id);

        if (!$type) {
            return response()->json([
                'message' => 'Finishing type not found'
            ], 404);
        }

        $validated = $request->validate([
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:finishing_types,slug,' . $id,
            'description' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        // If this is default, remove default from others
        if (isset($validated['is_default']) && $validated['is_default']) {
            FinishingType::where('is_default', true)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $type->update($validated);

        return response()->json([
            'message' => 'Finishing type updated successfully',
            'data' => $type
        ]);
    }

    /**
     * Remove the specified finishing type.
     */
    public function destroy($id)
    {
        $type = FinishingType::find($id);

        if (!$type) {
            return response()->json([
                'message' => 'Finishing type not found'
            ], 404);
        }

        // Check if type is used by properties
        if ($type->properties()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete finishing type that is used by properties'
            ], 422);
        }

        // Don't allow deleting default type
        if ($type->is_default) {
            return response()->json([
                'message' => 'Cannot delete default finishing type'
            ], 422);
        }

        $type->delete();

        return response()->json([
            'message' => 'Finishing type deleted successfully'
        ]);
    }

    /**
     * Get default finishing type.
     */
    public function getDefault()
    {
        $default = FinishingType::where('is_default', true)->first();

        if (!$default) {
            $default = FinishingType::first();
        }

        return response()->json([
            'data' => $default
        ]);
    }
}