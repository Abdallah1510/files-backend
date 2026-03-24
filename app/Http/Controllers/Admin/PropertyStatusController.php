<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PropertyStatus;

class PropertyStatusController extends Controller
{
    /**
     * Display a listing of property statuses.
     */
    public function index()
    {
        $statuses = PropertyStatus::orderBy('name_ar')->get();

        return response()->json([
            'data' => $statuses
        ]);
    }

    /**
     * Store a newly created property status.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:property_statuses,slug',
            'color' => 'nullable|string|max:50',
            'is_default' => 'boolean',
        ]);

        // If this is default, remove default from others
        if (isset($validated['is_default']) && $validated['is_default']) {
            PropertyStatus::where('is_default', true)->update(['is_default' => false]);
        }

        $status = PropertyStatus::create($validated);

        return response()->json([
            'message' => 'Property status created successfully',
            'data' => $status
        ], 201);
    }

    /**
     * Display the specified property status.
     */
    public function show($id)
    {
        $status = PropertyStatus::find($id);

        if (!$status) {
            return response()->json([
                'message' => 'Property status not found'
            ], 404);
        }

        return response()->json([
            'data' => $status
        ]);
    }

    /**
     * Update the specified property status.
     */
    public function update(Request $request, $id)
    {
        $status = PropertyStatus::find($id);

        if (!$status) {
            return response()->json([
                'message' => 'Property status not found'
            ], 404);
        }

        $validated = $request->validate([
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:property_statuses,slug,' . $id,
            'color' => 'nullable|string|max:50',
            'is_default' => 'boolean',
        ]);

        // If this is default, remove default from others
        if (isset($validated['is_default']) && $validated['is_default']) {
            PropertyStatus::where('is_default', true)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $status->update($validated);

        return response()->json([
            'message' => 'Property status updated successfully',
            'data' => $status
        ]);
    }

    /**
     * Remove the specified property status.
     */
    public function destroy($id)
    {
        $status = PropertyStatus::find($id);

        if (!$status) {
            return response()->json([
                'message' => 'Property status not found'
            ], 404);
        }

        // Check if status is used by properties
        if ($status->properties()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete status that is used by properties'
            ], 422);
        }

        // Don't allow deleting default status
        if ($status->is_default) {
            return response()->json([
                'message' => 'Cannot delete default property status'
            ], 422);
        }

        $status->delete();

        return response()->json([
            'message' => 'Property status deleted successfully'
        ]);
    }

    /**
     * Get default property status.
     */
    public function getDefault()
    {
        $default = PropertyStatus::where('is_default', true)->first();

        if (!$default) {
            $default = PropertyStatus::first();
        }

        return response()->json([
            'data' => $default
        ]);
    }
}