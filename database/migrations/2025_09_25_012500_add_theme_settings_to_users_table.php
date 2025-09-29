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
            $table->string('portfolio_logo', 100)->nullable();
            $table->string('portfolio_logo_light', 100)->nullable();
            $table->string('portfolio_favicon', 100)->nullable();
            $table->string('portfolio_primary_color', 7)->default('#268707');
            $table->string('portfolio_secondary_color', 7)->default('#f8f9fa');
            $table->string('portfolio_theme', 10)->default('light'); // light, dark, auto
            $table->string('portfolio_font_family', 50)->default('Inter');
            $table->integer('portfolio_font_size')->default(16);
            $table->text('portfolio_custom_css')->nullable();
            $table->text('portfolio_custom_js')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'portfolio_logo',
                'portfolio_logo_light',
                'portfolio_favicon',
                'portfolio_primary_color',
                'portfolio_secondary_color',
                'portfolio_theme',
                'portfolio_font_family',
                'portfolio_font_size',
                'portfolio_custom_css',
                'portfolio_custom_js'
            ]);
        });
    }
};
