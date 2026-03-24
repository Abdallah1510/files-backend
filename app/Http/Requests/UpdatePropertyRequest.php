<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // سنربطها لاحقًا بالـ Auth
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|exists:properties,id',
            'property_type_id' => 'sometimes|exists:property_types,id',
            'property_status_id' => 'sometimes|exists:property_statuses,id',
            'finishing_type_id' => 'nullable|exists:finishing_types,id',
            'title_ar' => 'sometimes|string|max:255',
            'title_en' => 'sometimes|string|max:255',
            'price_type' => 'sometimes|in:cash,installment,starting_from,custom',
            'price_amount' => 'nullable|numeric',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer',
            'feature_ids' => 'sometimes|array',
            'feature_ids.*' => 'exists:features,id',
        ];
    }
}