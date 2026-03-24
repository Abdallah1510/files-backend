<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_videos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                  ->constrained('properties')
                  ->cascadeOnDelete();

            $table->string('video_url');

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            // Performance indexing
            $table->index('property_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_videos');
    }
};