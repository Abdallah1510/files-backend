<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinishingType;

class FinishingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name_ar' => 'تشطيب عادي',
                'name_en' => 'Regular',
                'slug' => 'regular',
                'description' => 'تشطيب أساسي بدون إضافات',
                'is_default' => true
            ],
            [
                'name_ar' => 'تشطيب لوكس',
                'name_en' => 'Lux',
                'slug' => 'lux',
                'description' => 'تشطيب بمواد وخامات جيدة',
                'is_default' => false
            ],
            [
                'name_ar' => 'تشطيب سوبر لوكس',
                'name_en' => 'Super Lux',
                'slug' => 'super-lux',
                'description' => 'تشطيب فاخر بأعلى المواصفات',
                'is_default' => false
            ],
        ];

        foreach ($types as $type) {
            FinishingType::firstOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }

        $this->command->info('✅ تم إضافة/تحديث أنواع التشطيب بنجاح');
    }
}