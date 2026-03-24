<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            // داخلي
            ['name_ar' => 'مكيفات', 'name_en' => 'Air Conditioning', 'slug' => 'ac', 'group' => 'internal'],
            ['name_ar' => 'تدفئة مركزية', 'name_en' => 'Central Heating', 'slug' => 'central-heating', 'group' => 'internal'],
            ['name_ar' => 'أرضيات باركيه', 'name_en' => 'Parquet Floors', 'slug' => 'parquet', 'group' => 'internal'],
            ['name_ar' => 'مطبخ مجهز', 'name_en' => 'Equipped Kitchen', 'slug' => 'equipped-kitchen', 'group' => 'internal'],
            
            // خارجي
            ['name_ar' => 'حمام سباحة', 'name_en' => 'Swimming Pool', 'slug' => 'pool', 'group' => 'external'],
            ['name_ar' => 'حديقة خاصة', 'name_en' => 'Private Garden', 'slug' => 'garden', 'group' => 'external'],
            ['name_ar' => 'جراج', 'name_en' => 'Garage', 'slug' => 'garage', 'group' => 'external'],
            ['name_ar' => 'شرفة', 'name_en' => 'Balcony', 'slug' => 'balcony', 'group' => 'external'],
            
            // أمن
            ['name_ar' => 'حراسة 24 ساعة', 'name_en' => '24/7 Security', 'slug' => 'security', 'group' => 'security'],
            ['name_ar' => 'كاميرات مراقبة', 'name_en' => 'CCTV', 'slug' => 'cctv', 'group' => 'security'],
            ['name_ar' => 'نظام إنذار', 'name_en' => 'Alarm System', 'slug' => 'alarm', 'group' => 'security'],
            
            // قريبة
            ['name_ar' => 'قريب من المدارس', 'name_en' => 'Near Schools', 'slug' => 'near-schools', 'group' => 'location'],
            ['name_ar' => 'قريب من المستشفيات', 'name_en' => 'Near Hospitals', 'slug' => 'near-hospitals', 'group' => 'location'],
            ['name_ar' => 'قريب من المواصلات', 'name_en' => 'Near Transport', 'slug' => 'near-transport', 'group' => 'location'],
        ];

        foreach ($features as $feature) {
            Feature::firstOrCreate(
                ['slug' => $feature['slug']],
                $feature
            );
        }

        $this->command->info('✅ تم إضافة/تحديث الميزات بنجاح');
    }
}