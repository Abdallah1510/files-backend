<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name_ar' => 'شقة',
                'name_en' => 'Apartment',
                'slug' => 'apartment'
            ],
            [
                'name_ar' => 'فيلا',
                'name_en' => 'Villa',
                'slug' => 'villa'
            ],
            [
                'name_ar' => 'بنتهاوس',
                'name_en' => 'Penthouse',
                'slug' => 'penthouse'
            ],
            [
                'name_ar' => 'دوبلكس',
                'name_en' => 'Duplex',
                'slug' => 'duplex'
            ],
            [
                'name_ar' => 'استوديو',
                'name_en' => 'Studio',
                'slug' => 'studio'
            ],
        ];

        foreach ($types as $type) {
            PropertyType::firstOrCreate(
                ['slug' => $type['slug']], // البحث بالـ slug
                $type // إنشاء إذا لم يوجد
            );
        }

        $this->command->info('✅ تم إضافة/تحديث أنواع العقارات بنجاح');
    }
}