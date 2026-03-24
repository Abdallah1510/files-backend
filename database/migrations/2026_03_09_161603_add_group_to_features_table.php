<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('features', function (Blueprint $table) {
            if (!Schema::hasColumn('features', 'group')) {
                $table->string('group')->nullable()->after('icon');
            }
            
            if (!Schema::hasColumn('features', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('group');
            }
        });
    }

    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            if (Schema::hasColumn('features', 'group')) {
                $table->dropColumn('group');
            }
            
            if (Schema::hasColumn('features', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};