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
        Schema::create('user_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name'); // certification name
            $table->string('issuing_organization'); // organization that issued
            $table->date('issue_date');
            $table->date('expiry_date')->nullable(); // some certifications don't expire
            $table->boolean('does_not_expire')->default(false);
            $table->string('credential_id')->nullable(); // certificate ID or number
            $table->string('credential_url')->nullable(); // verification URL
            $table->text('description')->nullable();
            $table->text('skills_gained')->nullable(); // comma separated skills
            $table->string('certificate_image')->nullable(); // uploaded certificate image
            $table->string('organization_logo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'sort_order']);
            $table->index(['user_id', 'expiry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_certifications');
    }
};
