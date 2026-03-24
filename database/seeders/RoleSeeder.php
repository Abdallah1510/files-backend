<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===========================================
        // إنشاء الأدوار
        // ===========================================
        
        // دور Admin (مدير النظام)
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'api',
            'description' => 'مدير النظام - لديه كافة الصلاحيات'
        ]);

        // دور Editor (محرر)
        $editor = Role::firstOrCreate([
            'name' => 'editor',
            'guard_name' => 'api',
            'description' => 'محرر - يمكنه إدارة العقارات والمحتوى'
        ]);

        // دور Viewer (مشاهد)
        $viewer = Role::firstOrCreate([
            'name' => 'viewer',
            'guard_name' => 'api',
            'description' => 'مشاهد - يمكنه العرض فقط'
        ]);

        // ===========================================
        // تخصيص الصلاحيات لكل دور
        // ===========================================

        // ✅ Admin: كل الصلاحيات
        $allPermissions = Permission::all();
        $admin->permissions()->sync($allPermissions->pluck('id'));

        // ✅ Editor: صلاحيات إدارة العقارات والمحتوى
        $editorPermissions = Permission::whereIn('group', [
            'properties', 'media', 'master'
        ])->get();
        $editor->permissions()->sync($editorPermissions->pluck('id'));

        // ✅ Viewer: صلاحيات العرض فقط
        $viewerPermissions = Permission::whereIn('name', [
            'view-properties',
            // 'view-users',        // (اختياري) لو عاوز يشوف المستخدمين
            // 'view-reports'       // (اختياري) لو عاوز يشوف التقارير
        ])->get();
        $viewer->permissions()->sync($viewerPermissions->pluck('id'));

        $this->command->info('✅ تم إنشاء الأدوار وتخصيص الصلاحيات بنجاح');
        
        $this->command->info('📋 الأدوار:');
        $this->command->info('   - Admin: ' . $admin->permissions->count() . ' صلاحية');
        $this->command->info('   - Editor: ' . $editor->permissions->count() . ' صلاحية');
        $this->command->info('   - Viewer: ' . $viewer->permissions->count() . ' صلاحية');
    }
}