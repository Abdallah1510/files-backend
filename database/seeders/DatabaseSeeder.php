<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // أولاً: البيانات الأساسية
            PermissionSeeder::class,
            RoleSeeder::class,
            
            // ثانياً: بيانات التطبيق
            AdminUserSeeder::class,
            PropertyTypeSeeder::class,
            PropertyStatusSeeder::class,
            FinishingTypeSeeder::class,
            FeatureSeeder::class,
        ]);
        
        $this->command->info('🎉 تم إضافة جميع البيانات بنجاح');
    }
}