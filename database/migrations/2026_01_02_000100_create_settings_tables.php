<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general')->index();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('subject');
            $table->text('body');
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // One row per prefix, locked while a number is handed out, so two
        // simultaneous requests cannot be given the same reference.
        Schema::create('uid_counters', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->unsignedInteger('year');
            $table->unsignedBigInteger('value')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uid_counters');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('settings');
    }
};
