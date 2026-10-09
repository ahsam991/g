<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('subtitle', 500)->nullable();
            $table->string('cta_text', 100)->nullable();
            $table->string('cta_link', 500)->nullable();
            $table->string('cta2_text', 100)->nullable();
            $table->string('cta2_link', 500)->nullable();
            $table->string('image')->nullable();
            $table->string('badge_text', 200)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
