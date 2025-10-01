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
            // Rename the column from img_category to default_portfolio_hero_image
            $table->renameColumn('img_category', 'default_portfolio_hero_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            // Rename back to original column name
            $table->renameColumn('default_portfolio_hero_image', 'img_category');
        });
    }
};