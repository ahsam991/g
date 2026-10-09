<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('contact')->index();
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone', 40)->nullable()->index();
            $table->string('country')->nullable();
            $table->string('subject')->nullable();
            $table->string('level')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_date')->nullable();
            $table->string('status')->default('new')->index();
            $table->string('source')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->foreignId('lead_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_enquiries');
    }
};
