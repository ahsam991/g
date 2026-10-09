<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();

            // Identity
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->index();
            $table->string('phone', 40)->nullable()->index();
            $table->string('alt_phone', 40)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('nationality', 80)->nullable();
            $table->string('country_of_residence', 80)->nullable();
            $table->string('passport_number', 40)->nullable()->index();
            $table->date('passport_expiry')->nullable();
            $table->string('marital_status', 20)->nullable();

            // Study intent
            $table->string('preferred_destination', 80)->nullable()->index();
            $table->string('preferred_level', 60)->nullable();
            $table->string('preferred_subject', 120)->nullable();
            $table->string('preferred_intake', 40)->nullable();
            $table->decimal('budget_per_year', 12, 2)->nullable();
            $table->string('budget_currency', 3)->nullable();

            // Ownership and workflow
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('new')->index();
            $table->string('source')->nullable()->index();
            $table->unsignedTinyInteger('completion_percent')->default(0);
            $table->text('notes')->nullable();

            // Portal account, created when the student registers or is invited
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamp('archived_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['last_name', 'first_name']);
            $table->index(['assigned_to', 'status']);
        });

        // The portal needs to resolve a signed-in student to their record.
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('candidate_id')->nullable()->after('branch_id')
                ->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropConstrainedForeignId('candidate_id'));
        Schema::dropIfExists('candidates');
    }
};
