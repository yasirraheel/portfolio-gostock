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
        Schema::create('custom_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('content');
            $table->string('icon', 100)->nullable();
            $table->integer('order_position')->default(0);
            $table->string('image')->nullable();
            $table->string('link_url', 500)->nullable();
            $table->string('link_text', 100)->nullable();
            $table->timestamps();

            // Create index for user_id instead of foreign key constraint
            $table->index('user_id');
            $table->index('order_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_sections');
    }
};
