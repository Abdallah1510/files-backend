<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // إضافة الحقول فقط إذا لم تكن موجودة
            if (!Schema::hasColumn('properties', 'is_published')) {
                $table->boolean('is_published')->default(false)
                      ->comment('هل العقار منشور للعرض العام؟');
            }
            
            if (!Schema::hasColumn('properties', 'is_featured')) {
                $table->boolean('is_featured')->default(false)
                      ->comment('هل العقار مميز؟');
            }
            
            // محاولة إضافة index مع تجاهل الخطأ إذا كان موجوداً
            try {
                $table->index(['is_published', 'is_featured'], 'properties_published_featured_index');
            } catch (\Exception $e) {
                // تجاهل الخطأ إذا كان index موجوداً مسبقاً
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // محاولة حذف index مع تجاهل الخطأ
            try {
                $table->dropIndex('properties_published_featured_index');
            } catch (\Exception $e) {
                // تجاهل الخطأ إذا كان index غير موجود
            }
            
            // حذف الحقول إذا كانت موجودة
            if (Schema::hasColumn('properties', 'is_published')) {
                $table->dropColumn('is_published');
            }
            
            if (Schema::hasColumn('properties', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};