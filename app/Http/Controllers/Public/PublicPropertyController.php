<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PublicPropertyController extends Controller
{
    /**
     * جلب قائمة العقارات المنشورة
     */
    public function index(Request $request)
    {
        try {
            $query = Property::with([
                'type', 
                'status', 
                'finishingType',
                'images'
            ])
            ->where('is_published', true)
            ->where('is_active', true);
            
            // فلترة حسب العقارات المميزة
            if ($request->boolean('featured')) {
                $query->where('is_featured', true);
            }
            
            // فلترة حسب الأحدث
            if ($request->boolean('latest')) {
                $query->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('sort_order', 'asc')
                      ->orderBy('created_at', 'desc');
            }
            
            // تحديد عدد النتائج
            $limit = $request->integer('limit', 6);
            $properties = $query->limit($limit)->get();
            
            return response()->json([
                'success' => true,
                'data' => $properties,
                'meta' => [
                    'count' => $properties->count(),
                    'limit' => $limit,
                    'featured' => $request->boolean('featured'),
                    'latest' => $request->boolean('latest')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العقارات',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * جلب تفاصيل عقار معين
     */
    public function show($id)
    {
        try {
            $property = Property::with([
                'type', 
                'status', 
                'finishingType',
                'features',
                'images',
                'videos',
                'parent'
            ])
            ->where('is_published', true)
            ->where('is_active', true)
            ->find($id);
            
            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'العقار غير موجود'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $property
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب تفاصيل العقار'
            ], 500);
        }
    }
}