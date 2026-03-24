<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // لاحقًا سنربطها بالـ Auth
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|exists:properties,id',
            'property_category' => 'required|in:building,unit,standalone',
            'property_type_id' => 'required|exists:property_types,id',
            'property_status_id' => 'required|exists:property_statuses,id',
            'finishing_type_id' => 'nullable|exists:finishing_types,id',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'price_type' => 'required|in:cash,installment,starting_from,custom',
            'price_amount' => 'nullable|numeric',
        ];
    }
}