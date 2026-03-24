<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'property_category',
        'property_type_id',
        'property_status_id',
        'finishing_type_id',
        'title_ar',
        'title_en',
        'slug',
        'description_ar',
        'description_en',
        'price_type',
        'price_amount',
        'currency',
        'installment_details',
        'area',
        'bedrooms',
        'bathrooms',
        'address',
        'latitude',
        'longitude',
        'sort_order',
        'is_published',  // ✅ أضف هذا السطر
        'is_featured',
        'is_active',
    ];

    // أضف هذه الإضافة لتحديد أن هذه الحقول boolean
    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price_amount' => 'decimal:2',
        'area' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Model Boot (Business Rule Protection)
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::deleting(function ($property) {

            // لو مش Force Delete (يعني Soft Delete عادي)
            if (!$property->isForceDeleting()) {

                if (
                    $property->property_category === 'building' &&
                    $property->children()->exists()
                ) {
                    throw new \Exception(
                        'Cannot delete building with existing units. Please delete units first.'
                    );
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Self Relationship
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(Property::class, 'parent_id')
                    ->withTrashed(); // مهم عشان لو الأب محذوف Soft Delete
    }

    public function children()
    {
        return $this->hasMany(Property::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Other Relationships
    |--------------------------------------------------------------------------
    */

    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function status()
    {
        return $this->belongsTo(PropertyStatus::class, 'property_status_id');
    }

    public function finishingType()
    {
        return $this->belongsTo(FinishingType::class, 'finishing_type_id');
    }

    public function features()
    {
        return $this->belongsToMany(
            Feature::class,
            'property_feature',
            'property_id',
            'feature_id'
        );
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function videos()
    {
        return $this->hasMany(PropertyVideo::class);
    }
    
    // ✅ أضف هذه السكوبات لتسهيل الاستعلامات
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
    
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}