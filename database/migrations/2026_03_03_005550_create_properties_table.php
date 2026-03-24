<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // Category: building | unit | standalone
            $table->enum('property_category', ['building', 'unit', 'standalone']);

            // Self Relationship (Parent)
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('properties')
                  ->nullOnDelete();

            // Foreign Keys
            $table->foreignId('property_type_id')
                  ->constrained('property_types')
                  ->restrictOnDelete();

            $table->foreignId('property_status_id')
                  ->constrained('property_statuses')
                  ->restrictOnDelete();

            $table->foreignId('finishing_type_id')
                  ->nullable()
                  ->constrained('finishing_types')
                  ->nullOnDelete();

            // Multi-language fields
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('slug')->unique();

            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();

            // Pricing
            $table->enum('price_type', ['cash', 'installment', 'starting_from', 'custom']);
            $table->decimal('price_amount', 15, 2)->nullable();
            $table->string('currency', 10)->default('EGP');
            $table->text('installment_details')->nullable();

            // Specifications
            $table->integer('area')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();

            // Location
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Control
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexing for performance
            $table->index('property_type_id');
            $table->index('property_status_id');
            $table->index('finishing_type_id');
            $table->index('parent_id');
            $table->index('price_amount');
            $table->index('area');
            $table->index('bedrooms');
            $table->index('bathrooms');
            $table->index('is_featured');
            $table->index(['property_category', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};