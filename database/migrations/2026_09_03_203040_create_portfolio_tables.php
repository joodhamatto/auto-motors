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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('group')->index();
            $table->string('label');
            $table->longText('value_fr')->nullable();
            $table->longText('value_en')->nullable();
            $table->longText('value')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title_fr');
            $table->string('title_en');
            $table->text('description_fr');
            $table->text('description_en');
            $table->string('icon')->default('bi-gear-wide-connected');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->timestamps();
        });
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_fr');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name_fr');
            $table->string('name_en');
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('image_reference')->nullable()->index();
            $table->string('main_image')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->timestamps();
        });
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt_fr')->nullable();
            $table->string('alt_en')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
        Schema::create('vehicle_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_fr');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
        Schema::create('advantages', function (Blueprint $table) {
            $table->id();
            $table->string('title_fr');
            $table->string('title_en');
            $table->string('icon')->default('bi-check2-circle');
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question_fr');
            $table->string('question_en');
            $table->text('answer_fr');
            $table->text('answer_en');
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('advantages');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('vehicle_categories');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('services');
        Schema::dropIfExists('site_settings');
    }
};
