<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20)->default('home');
            $table->string('line_1')->nullable();
            $table->string('line_2')->nullable();
            $table->string('city', 80)->nullable();
            $table->string('state', 80)->nullable();
            $table->string('postcode', 20)->nullable();
            $table->string('country', 80)->nullable();
            $table->timestamps();
        });

        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('relationship', 60)->nullable();
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::create('qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('level', 80);
            $table->string('institution');
            $table->string('subject')->nullable();
            $table->string('country', 80)->nullable();
            $table->string('grade', 40)->nullable();
            $table->decimal('gpa', 4, 2)->nullable();
            $table->decimal('gpa_scale', 4, 2)->nullable();
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            $table->timestamps();
        });

        Schema::create('english_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('type', 30);
            $table->date('test_date')->nullable();
            $table->decimal('overall', 4, 2)->nullable();
            $table->decimal('listening', 4, 2)->nullable();
            $table->decimal('reading', 4, 2)->nullable();
            $table->decimal('writing', 4, 2)->nullable();
            $table->decimal('speaking', 4, 2)->nullable();
            $table->string('certificate_number', 80)->nullable();
            $table->timestamps();
        });

        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('relationship', 60)->nullable();
            $table->string('occupation', 120)->nullable();
            $table->decimal('annual_income', 14, 2)->nullable();
            $table->string('income_currency', 3)->nullable();
            $table->string('funding_type', 60)->nullable();
            $table->text('evidence_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('counselling_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('held_at');
            $table->string('channel', 40)->nullable();
            $table->text('summary')->nullable();
            $table->text('next_action')->nullable();
            $table->timestamps();

            $table->index(['candidate_id', 'held_at']);
        });

        // Who owned the record and when, kept as history when reassigned.
        Schema::create('candidate_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at');
            $table->timestamp('unassigned_at')->nullable();
            $table->timestamps();

            $table->index(['candidate_id', 'unassigned_at']);
        });

        Schema::create('gdpr_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('consent_type', 60);
            $table->boolean('given')->default(false);
            $table->string('policy_version', 20)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('given_at')->nullable();
            $table->timestamps();

            $table->index(['candidate_id', 'consent_type']);
        });
    }

    public function down(): void
    {
        foreach ([
            'gdpr_consents', 'candidate_assignments', 'counselling_sessions', 'sponsors',
            'english_tests', 'qualifications', 'emergency_contacts', 'candidate_addresses',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
