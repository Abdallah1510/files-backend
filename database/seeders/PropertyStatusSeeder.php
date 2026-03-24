<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyStatus;

class PropertyStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name_ar' => 'متاح',
                'name_en' => 'Available',
                'slug' => 'available',
                'color' => '#28a745',
                'is_default' => true
            ],
            [
                'name_ar' => 'مباع',
                'name_en' => 'Sold',
                'slug' => 'sold',
                'color' => '#dc3545',
                'is_default' => false
            ],
            [
                'name_ar' => 'مؤجر',
                'name_en' => 'Rented',
                'slug' => 'rented',
                'color' => '#ffc107',
                'is_default' => false
            ],
        ];

        foreach ($statuses as $status) {
            PropertyStatus::firstOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }

        $this->command->info('✅ تم إضافة/تحديث حالات العقارات بنجاح');
    }
}