<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('flag', 10)->nullable();
            $table->string('image')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('gradient_classes', 200)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->index(['active', 'sort_order']);
        });

        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 300);
            $table->string('slug', 300)->unique();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->string('logo')->nullable();
            $table->string('website', 500)->nullable();
            $table->string('city', 150)->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('ranking', 100)->nullable();
            $table->boolean('partner')->default(false);
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('destination_id');
            $table->index(['active', 'sort_order']);
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 300);
            $table->foreignId('university_id')->constrained()->cascadeOnDelete();
            $table->string('level', 50);
            $table->string('duration', 100)->nullable();
            $table->string('tuition_fee', 100)->nullable();
            $table->text('requirements')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('university_id');
            $table->index('level');
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('icon_svg', 2000)->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->index(['active', 'sort_order']);
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('logo')->nullable();
            $table->string('website', 500)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['active', 'sort_order']);
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('country_flag', 10)->nullable();
            $table->string('visa_type', 200)->nullable();
            $table->text('quote');
            $table->string('photo')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('services');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('universities');
        Schema::dropIfExists('destinations');
    }
};
