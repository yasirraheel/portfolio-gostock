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
        Schema::create('user_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('project_name');
            $table->text('description')->nullable();
            $table->enum('project_type', ['personal', 'professional', 'open_source', 'freelance', 'startup', 'academic', 'other'])->default('personal');
            $table->enum('status', ['planning', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('in_progress');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('project_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->text('technologies')->nullable(); // JSON array of technologies used
            $table->text('project_images')->nullable(); // JSON array of image filenames
            $table->string('client_name')->nullable();
            $table->string('role')->nullable();
            $table->integer('team_size')->nullable();
            $table->text('key_features')->nullable();
            $table->text('challenges_solved')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_projects');
    }
};
