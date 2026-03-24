<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => 'required|array|min:1',

            'images.*.image_path' => 'required|string',

            'images.*.is_main' => 'nullable|boolean',

            'images.*.sort_order' => 'nullable|integer|min:0',
        ];
    }
}