<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===========================================
        // 1. إنشاء مستخدم Admin الأساسي (مدير النظام)
        // ===========================================
        $admin = User::firstOrCreate(
            ['email' => 'elrashidyrealestate2012@gmail.com'], // البحث بالإيميل
            [
                'name' => 'Admin',
                'password' => Hash::make('EL-Rashidy++real2012EstateLOGIN'),
            ]
        );

        // تعيين دور Admin للمستخدم الأساسي
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->sync([$adminRole->id]);
            $this->command->info('✅ تم تعيين دور Admin للمستخدم الأساسي');
        }

        // ===========================================
        // 2. إنشاء مستخدمين إضافيين للاختبار (اختياري)
        // ===========================================
        
        // مستخدم Editor للاختبار
        $editor = User::firstOrCreate(
            ['email' => 'editor@realestate.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
            ]
        );
        
        $editorRole = Role::where('name', 'editor')->first();
        if ($editorRole) {
            $editor->roles()->sync([$editorRole->id]);
        }

        // مستخدم Viewer للاختبار
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@realestate.com'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password'),
            ]
        );
        
        $viewerRole = Role::where('name', 'viewer')->first();
        if ($viewerRole) {
            $viewer->roles()->sync([$viewerRole->id]);
        }

        // مستخدم Admin إضافي (احتياطي)
        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@realestate.com'],
            [
                'name' => 'Admin 2',
                'password' => Hash::make('password'),
            ]
        );
        
        if ($adminRole) {
            $admin2->roles()->sync([$adminRole->id]);
        }

        // ===========================================
        // 3. عرض ملخص النتائج
        // ===========================================
        $this->command->info('=====================================');
        $this->command->info('✅ تم إنشاء المستخدمين وتعيين الأدوار بنجاح');
        $this->command->info('=====================================');
        
        $this->command->info('📋 قائمة المستخدمين:');
        $this->command->info('-------------------------------------');
        
        // عرض بيانات المستخدم الأساسي
        $this->command->info('🔹 Admin (رئيسي):');
        $this->command->info('   📧 elrashidyrealestate2012@gmail.com');
        $this->command->info('   🔑 EL-Rashidy++real2012EstateLOGIN');
        $this->command->info('   👑 الدور: Admin');
        
        // عرض بيانات المستخدمين الإضافيين
        $this->command->info('-------------------------------------');
        $this->command->info('🔸 Editor (اختباري):');
        $this->command->info('   📧 editor@realestate.com');
        $this->command->info('   🔑 password');
        
        $this->command->info('-------------------------------------');
        $this->command->info('🔹 Viewer (اختباري):');
        $this->command->info('   📧 viewer@realestate.com');
        $this->command->info('   🔑 password');
        
        $this->command->info('-------------------------------------');
        $this->command->info('🔸 Admin 2 (احتياطي):');
        $this->command->info('   📧 admin2@realestate.com');
        $this->command->info('   🔑 password');
        
        $this->command->info('=====================================');
        $this->command->info('🎉 تم حفظ جميع البيانات بنجاح');
    }
}