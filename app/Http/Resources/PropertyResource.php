<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PropertyImageResource;
use App\Http\Resources\FeatureResource;

class PropertyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'price_amount' => $this->price_amount,
            'price_type' => $this->price_type,
            'currency' => $this->currency,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'images' => PropertyImageResource::collection($this->images),
            'features' => FeatureResource::collection($this->features),
            // حذف الفيديوهات من الـ List
            // 'videos' => PropertyVideoResource::collection($this->videos), 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'parent_id' => $this->parent_id,  // إذا كان موجود
            'children' => PropertyResource::collection($this->children),  // إذا كانت property من نوع unit وعندها children
        ];
    }
}