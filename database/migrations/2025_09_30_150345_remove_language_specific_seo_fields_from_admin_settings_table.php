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
        Schema::table('admin_settings', function (Blueprint $table) {
            // Remove language-specific columns since we're using main fields for all languages
            if (Schema::hasColumn('admin_settings', 'welcome_text_en')) {
                $table->dropColumn('welcome_text_en');
            }
            if (Schema::hasColumn('admin_settings', 'welcome_subtitle_en')) {
                $table->dropColumn('welcome_subtitle_en');
            }
            if (Schema::hasColumn('admin_settings', 'description_en')) {
                $table->dropColumn('description_en');
            }
            if (Schema::hasColumn('admin_settings', 'keywords_en')) {
                $table->dropColumn('keywords_en');
            }
            if (Schema::hasColumn('admin_settings', 'welcome_text_es')) {
                $table->dropColumn('welcome_text_es');
            }
            if (Schema::hasColumn('admin_settings', 'welcome_subtitle_es')) {
                $table->dropColumn('welcome_subtitle_es');
            }
            if (Schema::hasColumn('admin_settings', 'description_es')) {
                $table->dropColumn('description_es');
            }
            if (Schema::hasColumn('admin_settings', 'keywords_es')) {
                $table->dropColumn('keywords_es');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            // Re-add the columns if needed to rollback
            $table->string('welcome_text_en')->nullable();
            $table->string('welcome_subtitle_en')->nullable();
            $table->text('description_en')->nullable();
            $table->text('keywords_en')->nullable();
            $table->string('welcome_text_es')->nullable();
            $table->string('welcome_subtitle_es')->nullable();
            $table->text('description_es')->nullable();
            $table->text('keywords_es')->nullable();
        });
    }
};
