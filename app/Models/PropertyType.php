<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'is_active',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}