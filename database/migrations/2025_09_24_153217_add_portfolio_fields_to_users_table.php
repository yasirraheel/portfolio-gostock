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
        Schema::table('users', function (Blueprint $table) {
            // Check and add only missing fields
            if (!Schema::hasColumn('users', 'profession')) {
                $table->string('profession')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'hero_image')) {
                $table->string('hero_image')->nullable();
            }

            // SEO/Meta fields
            if (!Schema::hasColumn('users', 'meta_title')) {
                $table->string('meta_title', 60)->nullable();
            }
            if (!Schema::hasColumn('users', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (!Schema::hasColumn('users', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable();
            }
            if (!Schema::hasColumn('users', 'og_image')) {
                $table->string('og_image')->nullable();
            }

            // Social media profiles (some may already exist)
            if (!Schema::hasColumn('users', 'linkedin')) {
                $table->string('linkedin')->nullable();
            }
            if (!Schema::hasColumn('users', 'facebook')) {
                $table->string('facebook')->nullable();
            }
            if (!Schema::hasColumn('users', 'instagram')) {
                $table->string('instagram')->nullable();
            }

            // Portfolio settings
            if (!Schema::hasColumn('users', 'profile_visibility')) {
                $table->enum('profile_visibility', ['public', 'private'])->default('public');
            }
            if (!Schema::hasColumn('users', 'available_for_hire')) {
                $table->enum('available_for_hire', ['yes', 'no'])->default('no');
            }
            if (!Schema::hasColumn('users', 'show_contact_form')) {
                $table->enum('show_contact_form', ['yes', 'no'])->default('yes');
            }
            if (!Schema::hasColumn('users', 'two_factor_auth')) {
                $table->enum('two_factor_auth', ['yes', 'no'])->default('no');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profession',
                'phone',
                'hero_image',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'og_image',
                'linkedin',
                'facebook',
                'twitter',
                'instagram',
                'profile_visibility',
                'available_for_hire',
                'show_contact_form',
                'two_factor_auth'
            ]);
        });
    }
};
