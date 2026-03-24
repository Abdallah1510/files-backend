<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // ===========================================
            // Property Permissions
            // ===========================================
            [
                'name' => 'view-properties',
                'guard_name' => 'api',
                'group' => 'properties',
                'description' => 'عرض قائمة العقارات'
            ],
            [
                'name' => 'create-properties',
                'guard_name' => 'api',
                'group' => 'properties',
                'description' => 'إنشاء عقار جديد'
            ],
            [
                'name' => 'edit-properties',
                'guard_name' => 'api',
                'group' => 'properties',
                'description' => 'تعديل العقارات'
            ],
            [
                'name' => 'delete-properties',
                'guard_name' => 'api',
                'group' => 'properties',
                'description' => 'حذف العقارات'
            ],
            [
                'name' => 'restore-properties',
                'guard_name' => 'api',
                'group' => 'properties',
                'description' => 'استعادة العقارات المحذوفة'
            ],

            // ===========================================
            // Media Permissions
            // ===========================================
            [
                'name' => 'upload-images',
                'guard_name' => 'api',
                'group' => 'media',
                'description' => 'رفع الصور'
            ],
            [
                'name' => 'delete-images',
                'guard_name' => 'api',
                'group' => 'media',
                'description' => 'حذف الصور'
            ],
            [
                'name' => 'upload-videos',
                'guard_name' => 'api',
                'group' => 'media',
                'description' => 'رفع الفيديوهات'
            ],
            [
                'name' => 'delete-videos',
                'guard_name' => 'api',
                'group' => 'media',
                'description' => 'حذف الفيديوهات'
            ],

            // ===========================================
            // Master Tables Permissions
            // ===========================================
            [
                'name' => 'manage-property-types',
                'guard_name' => 'api',
                'group' => 'master',
                'description' => 'إدارة أنواع العقارات'
            ],
            [
                'name' => 'manage-property-statuses',
                'guard_name' => 'api',
                'group' => 'master',
                'description' => 'إدارة حالات العقارات'
            ],
            [
                'name' => 'manage-finishing-types',
                'guard_name' => 'api',
                'group' => 'master',
                'description' => 'إدارة أنواع التشطيب'
            ],
            [
                'name' => 'manage-features',
                'guard_name' => 'api',
                'group' => 'master',
                'description' => 'إدارة الميزات'
            ],

            // ===========================================
            // User Management Permissions
            // ===========================================
            [
                'name' => 'view-users',
                'guard_name' => 'api',
                'group' => 'users',
                'description' => 'عرض المستخدمين'
            ],
            [
                'name' => 'create-users',
                'guard_name' => 'api',
                'group' => 'users',
                'description' => 'إنشاء مستخدمين'
            ],
            [
                'name' => 'edit-users',
                'guard_name' => 'api',
                'group' => 'users',
                'description' => 'تعديل المستخدمين'
            ],
            [
                'name' => 'delete-users',
                'guard_name' => 'api',
                'group' => 'users',
                'description' => 'حذف المستخدمين'
            ],

            // ===========================================
            // Roles & Permissions Management
            // ===========================================
            [
                'name' => 'manage-roles',
                'guard_name' => 'api',
                'group' => 'settings',
                'description' => 'إدارة الأدوار'
            ],
            [
                'name' => 'manage-permissions',
                'guard_name' => 'api',
                'group' => 'settings',
                'description' => 'إدارة الصلاحيات'
            ],

            // ===========================================
            // Reports Permissions
            // ===========================================
            [
                'name' => 'view-reports',
                'guard_name' => 'api',
                'group' => 'reports',
                'description' => 'عرض التقارير'
            ],
            [
                'name' => 'export-reports',
                'guard_name' => 'api',
                'group' => 'reports',
                'description' => 'تصدير التقارير'
            ],

            // ===========================================
            // System Settings
            // ===========================================
            [
                'name' => 'system-settings',
                'guard_name' => 'api',
                'group' => 'settings',
                'description' => 'إعدادات النظام'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('✅ تم إنشاء ' . count($permissions) . ' صلاحية بنجاح');
    }
}