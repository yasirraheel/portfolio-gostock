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
            // English language fields
            if (!Schema::hasColumn('admin_settings', 'welcome_text_en')) {
                $table->string('welcome_text_en')->nullable()->after('keywords');
            }
            if (!Schema::hasColumn('admin_settings', 'welcome_subtitle_en')) {
                $table->string('welcome_subtitle_en')->nullable()->after('welcome_text_en');
            }
            if (!Schema::hasColumn('admin_settings', 'description_en')) {
                $table->text('description_en')->nullable()->after('welcome_subtitle_en');
            }
            if (!Schema::hasColumn('admin_settings', 'keywords_en')) {
                $table->text('keywords_en')->nullable()->after('description_en');
            }

            // Spanish language fields
            if (!Schema::hasColumn('admin_settings', 'welcome_text_es')) {
                $table->string('welcome_text_es')->nullable()->after('keywords_en');
            }
            if (!Schema::hasColumn('admin_settings', 'welcome_subtitle_es')) {
                $table->string('welcome_subtitle_es')->nullable()->after('welcome_text_es');
            }
            if (!Schema::hasColumn('admin_settings', 'description_es')) {
                $table->text('description_es')->nullable()->after('welcome_subtitle_es');
            }
            if (!Schema::hasColumn('admin_settings', 'keywords_es')) {
                $table->text('keywords_es')->nullable()->after('description_es');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->dropColumn([
                'welcome_text_en', 'welcome_subtitle_en', 'description_en', 'keywords_en',
                'welcome_text_es', 'welcome_subtitle_es', 'description_es', 'keywords_es'
            ]);
        });
    }
};
