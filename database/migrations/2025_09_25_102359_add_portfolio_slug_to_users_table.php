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
            // Add portfolio_slug field with unique constraint
            if (!Schema::hasColumn('users', 'portfolio_slug')) {
                $table->string('portfolio_slug', 100)->nullable()->unique()->after('username');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'portfolio_slug')) {
                $table->dropUnique(['portfolio_slug']);
                $table->dropColumn('portfolio_slug');
            }
        });
    }
};
