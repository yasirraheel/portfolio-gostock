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
            if (!Schema::hasColumn('admin_settings', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('admin_settings', 'welcome_text')) {
                $table->string('welcome_text')->nullable()->after('description');
            }
            if (!Schema::hasColumn('admin_settings', 'welcome_subtitle')) {
                $table->string('welcome_subtitle')->nullable()->after('welcome_text');
            }
            if (!Schema::hasColumn('admin_settings', 'keywords')) {
                $table->text('keywords')->nullable()->after('welcome_subtitle');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->dropColumn(['description', 'welcome_text', 'welcome_subtitle', 'keywords']);
        });
    }
};
